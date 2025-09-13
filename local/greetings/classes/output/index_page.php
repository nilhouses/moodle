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

namespace local_greetings\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use context_system;

/**
 * Class index_page
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class index_page implements renderable, templatable {
    /** @var string $messages Messages to display in the template. */
    private $messages = null;

    /**
     * Constructor
     *
     * @var array $messages
     */
    public function __construct($messages) {
        $this->messages = $messages;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $USER;

        $data = new stdClass();

        $cardbackgroundcolor = get_config('local_greetings', 'messagecardbgcolor');

        $context = context_system::instance();
        $deletepost = has_capability('local/greetings:deleteownmessage', $context);
        $deleteanypost = has_capability('local/greetings:deleteanymessage', $context);

        foreach ($this->messages as $m) {
            // Can this user edit/delete this post?
            $m->candelete = ($deleteanypost || ($deletepost && $m->userid == $USER->id));
        }

        $data->messages = array_values($this->messages);
        $data->sesskey = sesskey();
        $data->cardbackgroundcolor = $cardbackgroundcolor;

        $messageform = new \local_greetings\form\message_dynamic_form();
        $messageform->set_data_for_dynamic_submission();

        $data->canpost = has_capability('local/greetings:postmessages', $context);
        $data->messageform = $messageform->render();

        return $data;
    }
}
