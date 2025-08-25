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
 * Unit test for local_greetings_get_greeting() function.
 *
 * This test verifies that when a null user is provided to
 * local_greetings_get_greeting(),
 * the function returns the generic greeting string as expected.
 *
 * The test resets the environment before execution to ensure isolation.
 * It asserts that the returned greeting matches the localized string for generic
 * users.
 *
 * @covers ::local_greetings_get_greeting
 */
final class lib_test extends \advanced_testcase {
    /**
     * cd /path-to-www/moodle
     * php admin/tool/phpunit/cli/init.php
     * vendor/bin/phpunit --filter test_local_greetings_null_user
     */
    public function test_local_greetings_null_user(): void {
        $this->resetAfterTest();

        // Test null user case.
        $result = local_greetings_get_greeting(null);
        $expected = get_string('greetinguser', 'local_greetings');
        $this->assertEquals($expected, $result);
    }
}