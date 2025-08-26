<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Main file to view greetings
 *
 * @package     local_greetings
 * @copyright   2025 Nil Casas <nil.cases@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_greetings;

defined('MOODLE_INTERNAL') || die();

global $CFG;

// File to test (lib.php).
require_once($CFG->dirroot . '/local/greetings/lib.php');

/**
 * Greetings library tests
 *
 * @package     local_greetings
 * @copyright   2025 Nil Casas <nil.cases@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class lib_test extends \advanced_testcase {
    /**
     * Testing the translation of greeting messages.
     *
     * @covers ::local_greetings_get_greeting
     *
     * @dataProvider local_greetings_get_greeting_provider
     * @param string|null $country User country
     * @param string $langstring Greetings message language string
     * To run this test:
     *   1. Initialise PHPUnit: php admin/tool/phpunit/cli/init.php
     *   2. Execute: vendor/bin/phpunit --filter test_local_greetings_get_greeting
     */
    public function test_local_greetings_get_greeting(?string $country, string $langstring): void {
        $user = null;
        if (!empty($country)) {
            $this->resetAfterTest(true);
            $user = $this->getDataGenerator()->create_user();
            $user->country = $country;
        }

        $this->assertSame(get_string($langstring, 'local_greetings', fullname($user)), local_greetings_get_greeting($user));
    }

    /**
     * Data provider for {@see test_local_greetings_get_greeting()}.
     *
     * @return array List of data sets - (string) data set name => (array) data
     */
    public static function local_greetings_get_greeting_provider(): array {
        return [
            'No user' => [ // Not logged in.
                'country' => null,
                'langstring' => 'greetinguser',
            ],
            'AU user' => [
                'country' => 'AU',
                'langstring' => 'greetinguserau',
            ],
            'ES user' => [
                'country' => 'ES',
                'langstring' => 'greetinguseres',
            ],
            'VU user' => [ // Logged in user, but no local greeting.
                'country' => 'VU',
                'langstring' => 'greetingloggedinuser',
            ],
            // This case will fail, just to see the output result.
            'UK user' => [
                'country' => 'UK',
                'langstring' => 'greetinguseres',
            ],
        ];
    }
}
