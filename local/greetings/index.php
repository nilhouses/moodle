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
 * @copyright   2025 Nil Casas<nil.cases@gmail.com>
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

// This plugin should only be accessible to logged in users.
require_login();
// To avoid guest users to se the plugin.
if (isguestuser()) {
    throw new moodle_exception('noguest');
}

// Capabilities.
$allowpost = has_capability('local/greetings:postmessages', $context);
$deletepost = has_capability('local/greetings:deleteownmessage', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);
$allowviewpost = has_capability('local/greetings:viewmessages', $context);

// Create the form instance.
$messageform = new \local_greetings\form\message_form();

$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {
    // Avoid CSRF attack.
    require_sesskey();
    $id = required_param('id', PARAM_INT);

    if ($deleteanypost || $deletepost) {
        $params = ['id' => $id];

        // Users without permission can only delete their own post.
        if (!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }

        // Todo: Confirm before deleting.
        $DB->delete_records('local_greetings_messages', $params);
        redirect($PAGE->url);
    }
}

// Read the user input.
if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);

    $message = required_param('message', PARAM_TEXT);
    // Save the input on the database.
    if (!empty($message)) {
        $record = new stdClass();
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);
        // Empty form.
        redirect($PAGE->url);
    }
}

echo $OUTPUT->header();

// Output greeting message using a mustache template.
if (isloggedin()) {
    $usergreeting = local_greetings_get_greeting($USER);
} else {
    $usergreeting = get_string('greetinguser', 'local_greetings');
}

$templatedata = ['usergreeting' => $usergreeting];
echo $OUTPUT->render_from_template('local_greetings/greeting_message', $templatedata);

// Display the form.
if ($allowpost) {
    $messageform->display();
}

// Get the database stored messages.
if ($allowviewpost) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');
    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
            FROM {local_greetings_messages} m
        LEFT JOIN {user} u ON u.id = m.userid
        ORDER BY timecreated DESC";

    $messages = $DB->get_records_sql($sql);

    foreach ($messages as $m) {
        // Can this user delete this post?
        // Attach a flag to each message here because we can't do this in mustache.
        $m->candelete = ($deleteanypost || ($deletepost && $m->userid == $USER->id));
    }
    // Display them in a decent format.
    $cardbackgroundcolor = get_config('local_greetings', 'messagecardbgcolor');
    $templatedata = [
        'messages' => array_values($messages),
        'cardbackgroundcolor' => $cardbackgroundcolor,
    ];
    echo $OUTPUT->render_from_template('local_greetings/messages', $templatedata);
}

echo $OUTPUT->footer();
