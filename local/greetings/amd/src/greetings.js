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

/**
 * Greetings module
 *
 * @module     local_greetings/greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Selectors from 'local_greetings/local/greetings/selectors';
import * as Repository from 'local_greetings/local/greetings/repository';
import * as Str from 'core/str';
import Log from 'core/log';
import DynamicForm from 'core_form/dynamicform';

export const init = (userid) => {
    registerEventListeners(userid);
};

const registerEventListeners = (userid) => {
    document.addEventListener('click', e => {
        if (e.target.closest(Selectors.actions.showGreetingButton)) {
            const greetingBlock = document.querySelector(Selectors.regions.greetingBlock);

            if (greetingBlock) {
                const nameField = document.querySelector(Selectors.regions.inputField);
                const msg = document.createElement("h2");

                userGreeting(nameField.value)
                .then((greetingStr) => {
                    msg.append(greetingStr);
                    greetingBlock.append(msg);
                    return;
                })
                .catch(e);
            }

            // Example of an ajax call.
            Repository.getUser(userid)
            .then(function(response) {
                Log.debug("Logged in user: " + response[0].firstname);
                Log.debug("User country: " + response[0].country);
                return;
            })
            .catch(e);
        }

        if (e.target.closest(Selectors.actions.resetButton)) {
            const nameField = document.querySelector(Selectors.regions.inputField);
            nameField.value = '';

            const greetingBlock = document.querySelector(Selectors.regions.greetingBlock);
            greetingBlock.innerHTML = '';
        }
    });
};

/**
 * Return a personalised greeting.
 *
 * @param   {String} name The name of the person to greet
 * @returns {Promise}
 */
const userGreeting = (name) => Str.getString('greetinguserau', 'local_greetings', name);

export const messageDynamicForm = (selector, formClass, userid) => {
    const form = new DynamicForm(document.querySelector(selector), formClass);
    const formargs = {};
    form.addEventListener(form.events.FORM_SUBMITTED, async(e) => {
        e.preventDefault();
        const response = e.detail;
        form.load({...formargs});

        Repository.saveGreeting(response.message, userid)
        .then(function() {
            window.location.reload(); // Just reload the page.
            return;
        })
        .catch(e);
    });
};
