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
     * Tests translation of greeting messages using local_greetings_get_greeting.
     *
     * @covers ::local_greetings_get_greeting
     * @param string|null $country User country
     * @param string $langstring Greetings message language string
     *
     * To run this test:
     *   1. Initialise PHPUnit: php admin/tool/phpunit/cli/init.php
     *   2. Execute: vendor/bin/phpunit --filter test_local_greetings_null_user
     */
    public function test_local_greetings_null_user(): void {
        $this->resetAfterTest();

        // Test null user case.
        $result = local_greetings_get_greeting(null);
        $expected = get_string('greetinguser', 'local_greetings');
        $this->assertEquals($expected, $result);
    }

    /**
     * Tests greeting message translation for a user with country set to 'AU' using local_greetings_get_greeting.
     *
     * @covers ::local_greetings_get_greeting
     *
     * This test creates a user, sets their country to 'AU', and verifies that the returned greeting
     * matches the expected language string for Australian users.
     *
     * To run this test:
     *   1. Initialise PHPUnit: php admin/tool/phpunit/cli/init.php
     *   2. Execute: vendor/bin/phpunit --filter test_local_greetings_au_user
     */
    public function test_local_greetings_au_user(): void {
        $this->resetAfterTest();

        // Test user with country='AU'.
        $user = $this->getDataGenerator()->create_user(); // Create a new user.
        $user->country = 'AU';

        $result = local_greetings_get_greeting($user);
        $expected = get_string('greetinguserau', 'local_greetings', fullname($user));
        $this->assertEquals($expected, $result);
    }
}
