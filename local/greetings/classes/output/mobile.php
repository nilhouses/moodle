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
 * Output functions for the local_greetings mobile interface.
 *
 * This file defines the mobile output class for the local_greetings plugin,
 * providing methods to render templates and handle data for mobile views,
 * such as greeting messages and forms.
 *
 * @package     local_greetings
 * @copyright   Nil Casas <nil.cases@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_greetings\output;

use context_system;

/**
 * Class mobile
 *
 * Provides output functions for the local_greetings mobile interface.
 *
 * @package     local_greetings
 * @copyright   Nil Casas <nil.cases@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {
    /**
     * Greets the user with a hello message.
     *
     * @return void
     */
    public static function view_hello() {
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<h1 class="text-center">{{ "plugin.local_greetings.hello" | translate }}</h1>',
                ],
            ],
        ];
    }
    /**
     * Displays a list of greetings messages.
     *
     * @param mixed $args The arguments for displaying the greetings list.
     * @return array The rendered templates and associated JavaScript.
     */
    public static function mobile_view_greetings_list($args) {
        global $OUTPUT, $DB;

        $args = (object) $args;
        $context = context_system::instance();

        $messages = [];

        if (has_capability('local/greetings:viewmessages', $context)) {
            $userfields = \core_user\fields::for_name()->with_identity($context);
            $userfieldssql = $userfields->get_sql('u');

            $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
                FROM {local_greetings_messages} m
                LEFT JOIN {user} u ON u.id = m.userid
                ORDER BY timecreated DESC";

            $messages = $DB->get_records_sql($sql);
        }

        $data = [
            'messages' => array_values($messages),
            'canpost' => has_capability('local/greetings:postmessages', $context),
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('local_greetings/mobile_view_greetings_list', $data),
                ],
            ],
            'javascript' => file_get_contents(__DIR__ . '/../../js/mobile/view_greetings_list.js'),
        ];
    }
    /**
     * Processes the given selection.
     *
     * This function performs operations based on the provided selection.
     *
     * @param mixed $selection The selection to be processed.
     * @return void
     */
    public static function mobile_view_greetings_form($args) {
        global $OUTPUT, $USER;

        $context = context_system::instance();

        $data = [
            'canpost' => has_capability('local/greetings:postmessages', $context),
            'userid' => $USER->id,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('local_greetings/mobile_add_greeting', $data),
                ],
            ],
            'javascript' => file_get_contents(__DIR__ . '/../../js/mobile/add_greeting.js'),
        ];
    }
}
