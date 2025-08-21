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
 * Plugin strings are defined here.
 *
 * @package     local_greetings
 * @category    string
 * @copyright   2025 Nil Casas<nil.cases@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_greetings\form;
// This file should not be accessed directly.
defined('MOODLE_INTERNAL') || die();
// Load the Forms library.
require_once($CFG->libdir . '/formslib.php');

/**
 * Form class for submitting a greeting message in the local_greetings plugin.
 *
 * Extends the moodleform class to define a form with a textarea for the message
 * and a submit button.
 *
 * @package   local_greetings
 * @copyright 2025 Nil Casas<nil.cases@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class message_form extends \moodleform {
    /**
     * Constructor, defines the elements of the message form.
     *
     * Adds a textarea for the user to enter a message and a submit button to submit the form.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form; // Get the form ubject, don't forget the underscore!

        // Add a textarea for the greeting message.
        $mform->addElement('textarea', 'message', get_string('yourmessage', 'local_greetings'));
        $mform->setType('message', PARAM_TEXT); // Ensure message is plain text.

        // Add a submit button.
        $submitlabel = get_string('submit');
        $mform->addElement('submit', 'submitmessage', $submitlabel);
    }
}
