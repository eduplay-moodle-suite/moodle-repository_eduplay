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

namespace repository_eduplay;

use local_eduplay\local\api_client;
use repository_eduplay as repository_class;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/repository/lib.php');
require_once(__DIR__ . '/../lib.php');

/**
 * Tests for the EduPlay repository (no real HTTP request is made).
 *
 * @package    repository_eduplay
 * @category   test
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \repository_eduplay
 */
final class repository_test extends \advanced_testcase {
    /** @var string[] URLs requested by the fake EduPlay API. */
    private array $urls = [];

    /**
     * Start from a clean cache and a clean fetcher.
     */
    protected function setUp(): void {
        parent::setUp();
        \cache::make('local_eduplay', 'metadata')->purge();
        $this->urls = [];
    }

    /**
     * Restore the real fetcher.
     */
    protected function tearDown(): void {
        api_client::set_default_fetcher(null);
        \cache::make('local_eduplay', 'metadata')->purge();
        parent::tearDown();
    }

    /**
     * Make new API clients answer with the given responses (the last one is repeated), recording the URLs.
     *
     * @param array $responses List of [code, body] or \Exception.
     */
    private function fake_api(array $responses): void {
        $i = 0;
        api_client::set_default_fetcher(function (string $url) use ($responses, &$i): array {
            $this->urls[] = $url;
            $response = $responses[min($i++, count($responses) - 1)];
            if ($response instanceof \Exception) {
                throw $response;
            }
            return $response;
        });
    }

    /**
     * A search item as returned by EduPlay.
     *
     * @param int $id
     * @param string $name
     * @param array $override
     * @return array
     */
    private static function item(int $id, string $name, array $override = []): array {
        return $override + [
            'contentType' => 'VIDEO', 'id' => $id, 'name' => $name, 'duration' => 100, 'visibility' => 1,
            'status' => 'ACTIVE', 'requiredAuthentication' => false,
            'image' => 'https://eduplay.rnp.br/api/v1/assets/videos/images/' . $id . '.jpg',
        ];
    }

    /**
     * Search response body.
     *
     * @param array $items
     * @param int $page
     * @param int $last
     * @return string
     */
    private static function body(array $items, int $page = 1, int $last = 1): string {
        return json_encode(['contents' => $items, 'pageInfo' => [
            'currentPage' => $page, 'lastPage' => $last, 'totalResults' => 99, 'quantity' => count($items)]]);
    }

    /**
     * Create a repository instance.
     *
     * @return repository_class
     */
    private function get_repository(): repository_class {
        $this->resetAfterTest();
        $this->setAdminUser();
        $generator = $this->getDataGenerator();
        $generator->create_repository_type('eduplay');
        $record = $generator->create_repository('eduplay');
        return \repository::get_repository_by_id($record->id, \context_system::instance());
    }

    /**
     * Only external links are supported and the initial listing is empty with a hint.
     */
    public function test_returntype_and_listing(): void {
        $repo = $this->get_repository();
        $this->assertSame(FILE_EXTERNAL, $repo->supported_returntypes());
        $listing = $repo->get_listing();
        $this->assertSame([], $listing['list']);
        $this->assertNotEmpty($listing['message']);
        $this->assertFalse($listing['nosearch']);
    }

    /**
     * Search by title returns external links with title, thumbnail and paging.
     */
    public function test_title_search(): void {
        $repo = $this->get_repository();
        $this->fake_api([[200, self::body([self::item(353479, 'Documentário Eduplay 20 anos'), self::item(7, 'Outro')], 2, 9)]]);

        $result = $repo->search('Documentário Eduplay', 2);

        $this->assertSame(
            ['https://eduplay.rnp.br/api/v1/search?term=Document%C3%A1rio%20Eduplay&page=2'],
            $this->urls
        );
        $this->assertCount(2, $result['list']);
        $this->assertSame('Documentário Eduplay 20 anos', $result['list'][0]['title']);
        $this->assertSame('https://eduplay.rnp.br/app/video/353479', $result['list'][0]['source']);
        $this->assertSame('https://eduplay.rnp.br/api/v1/assets/videos/images/353479.jpg', $result['list'][0]['thumbnail']);
        $this->assertSame([2, 9, true], [$result['page'], $result['pages'], $result['dynload']]);
    }

    /**
     * The file picker asks for the next page without the search text: the repository remembers it.
     */
    public function test_next_page_reuses_the_search_text(): void {
        $repo = $this->get_repository();
        $this->fake_api([
            [200, self::body([self::item(1, 'Primeira')], 1, 3)],
            [200, self::body([self::item(2, 'Segunda')], 2, 3)],
        ]);

        $first = $repo->search('aula de matemática', 1);
        $second = $repo->search('', 2);

        $this->assertSame([
            'https://eduplay.rnp.br/api/v1/search?term=aula%20de%20matem%C3%A1tica&page=1',
            'https://eduplay.rnp.br/api/v1/search?term=aula%20de%20matem%C3%A1tica&page=2',
        ], $this->urls);
        $this->assertSame('Primeira', $first['list'][0]['title']);
        $this->assertSame('Segunda', $second['list'][0]['title']);
        $this->assertSame([2, 3], [$second['page'], $second['pages']]);

        // A new search replaces the remembered text.
        $repo->search('física', 1);
        $repo->search('', 2);
        $this->assertStringContainsString('term=f%C3%ADsica&page=2', end($this->urls));
    }

    /**
     * A video without usable thumbnail gets the generic icon.
     */
    public function test_generic_thumbnail(): void {
        $repo = $this->get_repository();
        $this->fake_api([[200, self::body([self::item(5, 'Sem miniatura', ['image' => 'https://iptv.usp.br/a.jpg'])])]]);
        $result = $repo->search('sem');
        $this->assertCount(1, $result['list']);
        $this->assertStringNotContainsString('iptv.usp.br', $result['list'][0]['thumbnail']);
        $this->assertNotEmpty($result['list'][0]['thumbnail']);
    }

    /**
     * No results gives a message, not an error.
     */
    public function test_no_results(): void {
        $repo = $this->get_repository();
        $this->fake_api([[200, self::body([])]]);
        $result = $repo->search('xyzzyqwerty');
        $this->assertSame([], $result['list']);
        $this->assertSame(get_string('noresults', 'repository_eduplay'), $result['message']);
    }

    /**
     * A failing EduPlay service does not break the file picker.
     */
    public function test_service_failure(): void {
        $repo = $this->get_repository();
        $this->fake_api([[500, '{}']]);
        $result = $repo->search('aula');
        $this->assertSame([], $result['list']);
        $this->assertSame(get_string('searcherror', 'repository_eduplay'), $result['message']);
    }

    /**
     * Links that are not canonical EduPlay links, and empty text, are never sent to EduPlay.
     *
     * @dataProvider not_sent_provider
     * @param string $text
     */
    public function test_text_not_sent(string $text): void {
        $repo = $this->get_repository();
        $this->fake_api([[200, self::body([self::item(1, 'x')])]]);
        $this->assertSame([], $repo->search($text)['list']);
        $this->assertSame([], $this->urls);
    }

    /**
     * Texts that must not reach the EduPlay API.
     *
     * @return array
     */
    public static function not_sent_provider(): array {
        return [
            'other host' => ['https://evil.example/app/video/1'],
            'http link' => ['http://eduplay.rnp.br/app/video/1'],
            'embed route' => ['https://eduplay.rnp.br/app/video/embed/1'],
            'userinfo trick' => ['https://eduplay.rnp.br@evil.example/app/video/1'],
            'empty' => [''],
            'spaces' => ['   '],
        ];
    }

    /**
     * A pasted canonical link shows the real title and thumbnail.
     */
    public function test_pasted_link_with_metadata(): void {
        $repo = $this->get_repository();
        $this->fake_api([[200, json_encode(self::item(353479, 'Documentário Eduplay 20 anos', ['contentType' => 'video']))]]);
        $result = $repo->search('https://eduplay.rnp.br/app/video/353479');
        $this->assertSame(['https://eduplay.rnp.br/api/v1/videos/353479'], $this->urls);
        $this->assertCount(1, $result['list']);
        $this->assertSame('Documentário Eduplay 20 anos', $result['list'][0]['title']);
        $this->assertSame('https://eduplay.rnp.br/app/video/353479', $result['list'][0]['source']);
    }

    /**
     * A pasted link to a video that does not exist or is not public returns nothing.
     */
    public function test_pasted_link_not_available(): void {
        $repo = $this->get_repository();
        $this->fake_api([[404, '{}']]);
        $result = $repo->search('https://eduplay.rnp.br/app/video/999999999');
        $this->assertSame([], $result['list']);
        $this->assertSame(get_string('noresults', 'repository_eduplay'), $result['message']);
    }

    /**
     * If EduPlay cannot be reached, a pasted link still works with a generic title.
     */
    public function test_pasted_link_service_down(): void {
        $repo = $this->get_repository();
        $this->fake_api([[503, '']]);
        $result = $repo->search('https://eduplay.rnp.br/app/video/353479');
        $this->assertCount(1, $result['list']);
        $this->assertSame('EduPlay video 353479', $result['list'][0]['title']);
    }

    /**
     * With remote lookups disabled, no request is made and only pasted links work.
     */
    public function test_remote_disabled(): void {
        $repo = $this->get_repository();
        set_config('enableremote', 0, 'local_eduplay');
        $this->fake_api([[200, self::body([self::item(1, 'x')])]]);

        $this->assertSame([], $repo->search('aula')['list']);
        $this->assertSame(get_string('searchhintpasted', 'repository_eduplay'), $repo->search('aula')['message']);
        $this->assertSame(get_string('searchhintpasted', 'repository_eduplay'), $repo->get_listing()['message']);

        $pasted = $repo->search('https://eduplay.rnp.br/app/video/353479');
        $this->assertSame('EduPlay video 353479', $pasted['list'][0]['title']);
        $this->assertSame([], $this->urls);
    }
}
