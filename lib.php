<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * EduPlay repository: search EduPlay videos by title (or paste a video link) from the file picker.
 *
 * The repository returns external links only (never a copy of the media). Searching uses the public EduPlay API through
 * the client of local_eduplay; when remote lookups are disabled in the site settings, only pasted links work.
 *
 * @package    repository_eduplay
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/repository/lib.php');

use local_eduplay\local\api_client;
use local_eduplay\local\url_parser;
use local_eduplay\local\video_info;
use local_eduplay\local\video_reference;

/**
 * Repository plugin class.
 *
 * @package    repository_eduplay
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_eduplay extends repository {
    /** @var int Width of the thumbnails shown in the file picker. */
    private const THUMBNAIL_WIDTH = 150;

    /** @var int Height of the thumbnails shown in the file picker. */
    private const THUMBNAIL_HEIGHT = 84;

    /**
     * Initial listing: empty, with the search box available and a hint.
     *
     * @param string $path
     * @param string $page
     * @return array
     */
    public function get_listing($path = '', $page = '') {
        return $this->response([], api_client::is_enabled() ? 'searchhint' : 'searchhintpasted');
    }

    /**
     * Search by title, or return the video of a pasted canonical link.
     *
     * @param string $searchtext
     * @param int $page Page, starting at 1.
     * @return array
     */
    public function search($searchtext, $page = 0) {
        global $SESSION;

        $searchtext = trim((string) $searchtext);
        $page = max(1, (int) $page);

        // The file picker does not send the search text again when it asks for the next page: remember it.
        $sessionkey = 'repository_eduplay_term_' . $this->id;
        if ($searchtext === '' && $page > 1) {
            $searchtext = (string) ($SESSION->{$sessionkey} ?? '');
        } else {
            $SESSION->{$sessionkey} = $searchtext;
        }

        $reference = url_parser::parse_reference($searchtext);
        if ($reference !== null) {
            return $this->pasted_link($reference);
        }
        // Other links are never sent to EduPlay as search text.
        if ($searchtext === '' || preg_match('~^[a-z][a-z0-9+.-]*://~i', $searchtext)) {
            return $this->response([], api_client::is_enabled() ? 'searchhint' : 'searchhintpasted');
        }
        if (!api_client::is_enabled()) {
            return $this->response([], 'searchhintpasted');
        }

        try {
            $result = (new api_client())->search($searchtext, $page);
        } catch (\moodle_exception $e) {
            return $this->response([], 'searcherror');
        }
        $items = array_map(fn(video_info $video): array => $this->item($video->name, $video->reference(), $video->thumbnail),
            $result->videos);
        $response = $this->response($items, $items ? null : 'noresults');
        $response['page'] = $result->page;
        $response['pages'] = $result->lastpage;
        $response['dynload'] = true;
        return $response;
    }

    /**
     * Only external links are returned: the media is never copied into Moodle.
     *
     * @return int
     */
    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }

    /**
     * Whether the repository needs no login.
     *
     * @return bool
     */
    public function check_login() {
        return true;
    }

    /**
     * The repository has no global search.
     *
     * @return bool
     */
    public function global_search() {
        return false;
    }

    /**
     * Result for a pasted canonical link: title and thumbnail come from EduPlay when possible.
     *
     * @param video_reference $reference
     * @return array
     */
    private function pasted_link(video_reference $reference): array {
        $title = get_string('videotitle', 'repository_eduplay', $reference->videoid);
        $thumbnail = null;
        if (api_client::is_enabled()) {
            try {
                $video = (new api_client())->get_video($reference->videoid);
            } catch (\moodle_exception $e) {
                // EduPlay cannot be reached: the link is still valid, only the title is generic.
                $video = false;
            }
            if ($video === null) {
                return $this->response([], 'noresults');
            }
            if ($video !== false) {
                $title = $video->name;
                $thumbnail = $video->thumbnail;
            }
        }
        return $this->response([$this->item($title, $reference, $thumbnail)]);
    }

    /**
     * Build one file picker item.
     *
     * @param string $title Title, used as the link text.
     * @param video_reference $reference
     * @param string|null $thumbnail Thumbnail URL, or null for the generic video icon.
     * @return array
     */
    private function item(string $title, video_reference $reference, ?string $thumbnail): array {
        global $OUTPUT;

        $url = $reference->canonical_url();
        return [
            'title' => $title,
            'source' => $url,
            'url' => $url,
            'thumbnail' => $thumbnail ?? $OUTPUT->image_url(file_extension_icon('video.mp4'))->out(false),
            'thumbnail_width' => self::THUMBNAIL_WIDTH,
            'thumbnail_height' => self::THUMBNAIL_HEIGHT,
        ];
    }

    /**
     * Common structure of the answers to the file picker.
     *
     * @param array $list Items.
     * @param string|null $messagestring Identifier of a message string of this plugin, or null for no message.
     * @return array
     */
    private function response(array $list, ?string $messagestring = null): array {
        $response = [
            'list' => $list,
            'nologin' => true,
            'norefresh' => true,
            'nosearch' => false,
        ];
        if ($messagestring !== null) {
            $response['message'] = get_string($messagestring, 'repository_eduplay');
        }
        return $response;
    }
}
