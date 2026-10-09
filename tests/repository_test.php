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

use repository_eduplay as repository_class;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/repository/lib.php');
require_once(__DIR__ . '/../lib.php');

/**
 * Tests for the EduPlay repository.
 *
 * @package    repository_eduplay
 * @category   test
 * @copyright  2026 Kelson da Costa Medeiros <kelsoncm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \repository_eduplay
 */
final class repository_test extends \advanced_testcase {
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
     * A pasted canonical link is returned as a single external item.
     */
    public function test_search_canonical_url(): void {
        $repo = $this->get_repository();
        $result = $repo->search('https://eduplay.rnp.br/app/video/353479');
        $this->assertCount(1, $result['list']);
        $this->assertSame('https://eduplay.rnp.br/app/video/353479', $result['list'][0]['source']);
        $this->assertSame('EduPlay video 353479', $result['list'][0]['title']);
    }

    /**
     * Unsupported text returns no results.
     *
     * @dataProvider unsupported_provider
     * @param string $text
     */
    public function test_search_unsupported(string $text): void {
        $repo = $this->get_repository();
        $this->assertSame([], $repo->search($text)['list']);
    }

    /**
     * Unsupported search texts.
     *
     * @return array
     */
    public static function unsupported_provider(): array {
        return [
            'other host' => ['https://evil.example/app/video/1'],
            'http' => ['http://eduplay.rnp.br/app/video/1'],
            'plain text' => ['lecture'],
            'empty' => [''],
        ];
    }

    /**
     * Only external links are supported and the listing starts empty.
     */
    public function test_returntype_and_listing(): void {
        $repo = $this->get_repository();
        $this->assertSame(FILE_EXTERNAL, $repo->supported_returntypes());
        $listing = $repo->get_listing();
        $this->assertSame([], $listing['list']);
        $this->assertNotEmpty($listing['message']);
    }
}
