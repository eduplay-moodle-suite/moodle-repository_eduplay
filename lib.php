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
 * EduPlay repository: lets the user add a link to an EduPlay video from the file picker.
 *
 * Proof of concept. EduPlay has no officially confirmed listing or search API, so the repository does not browse
 * the catalogue: the user pastes a canonical video link in the search box and gets an external link back
 * (never a copy of the media).
 *
 * @package    repository_eduplay
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/repository/lib.php');

use local_eduplay\local\url_parser;

/**
 * Repository plugin class.
 *
 * @package    repository_eduplay
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_eduplay extends repository {
    /**
     * Initial listing: empty, with the search box available.
     *
     * @param string $path
     * @param string $page
     * @return array
     */
    public function get_listing($path = '', $page = '') {
        return [
            'list' => [],
            'nologin' => true,
            'norefresh' => true,
            'nosearch' => false,
        ];
    }

    /**
     * Search by pasted link: returns the video as an external link when the text is a canonical EduPlay URL.
     *
     * @param string $searchtext
     * @param int $page
     * @return array
     */
    public function search($searchtext, $page = 0) {
        $list = [];
        $reference = url_parser::parse_reference((string) $searchtext);
        if ($reference !== null) {
            $list[] = [
                'title' => get_string('videotitle', 'repository_eduplay', $reference->videoid),
                'source' => $reference->canonical_url(),
                'url' => $reference->canonical_url(),
            ];
        }
        return [
            'list' => $list,
            'nologin' => true,
            'norefresh' => true,
            'nosearch' => false,
        ];
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
}
