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
 * @copyright   2022 Your name <your@email>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/greetings/lib.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

$allowpost = has_capability('local/greetings:postmessages', $context);
$deletepost = has_capability('local/greetings:deleteownmessage', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);

$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {
    require_sesskey();

    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost || $deletepost) {
        $params = ['id' => $id];

        // Users without permission should only delete their own post.
        if (!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }

        // Todo: Confirm before deleting.
        $DB->delete_records('local_greetings_messages', $params);

        redirect($PAGE->url); // Reload this page to remove visible sesskey.
    }
}

$output = $PAGE->get_renderer('local_greetings');

echo $output->header();

// Adding simple navmenu acting as "tertiary navigation".
echo $output->render_from_template('local_greetings/navmenu', []);

if (isloggedin()) {
    echo local_greetings_get_greeting($USER);
} else {
    echo get_string('greetinguser', 'local_greetings');
}

if (has_capability('local/greetings:viewmessages', $context)) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
            FROM {local_greetings_messages} m
            LEFT JOIN {user} u ON u.id = m.userid
            ORDER BY timecreated DESC LIMIT 10";

    $messages = $DB->get_records_sql($sql);

    $renderable = new \local_greetings\output\index_page($messages);
    echo $output->render($renderable);
}

$PAGE->requires->js_call_amd(
    'local_greetings/greetings',
    'messageDynamicForm',
    ['[data-region=form]', \local_greetings\form\message_dynamic_form::class, $USER->id]
);

echo $output->footer();
