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

namespace local_greetings;

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/tablelib.php");

/**
 * Class messageslist
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class messageslist extends \table_sql {
    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = ['message', 'userid', 'timecreated'];
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = [
            get_string('message'),
            get_string('user'),
            get_string('timecreated'),
        ];
        $this->define_headers($headers);
    }

    /**
     * Display the user
     *
     * @param stdClass $row - The row of data
     * @return string Link to user profile
     */
    public function col_userid($row) {
        return \html_writer::link(
            new \moodle_url('/user/view.php',
            ['id' => $row->userid]), fullname($row)
        );
    }

    /**
     * Display a human-friendly date
     *
     * @param stdClass $row - The row of data
     * @return string Formatted date
     */
    public function col_timecreated($row) {
        return userdate($row->timecreated);
    }

    /**
     * Display the message
     *
     * @param stdClass $row - The row of data
     * @return string The message
     */
    public function col_message($row) {
        return format_text($row->message);
    }
}
