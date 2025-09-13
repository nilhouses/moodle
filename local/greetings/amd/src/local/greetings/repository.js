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
 * Repository module
 *
 * @module     local_greetings/local/greetings/repository
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {call as fetchMany} from 'core/ajax';

/**
 * Get  user details by id.
 *
 * @param {Number} userid
 * @returns
 */
export const getUser = (userid = 0) => {
    return fetchMany([{
        methodname: 'core_user_get_users_by_field',
        args: {field: 'id', values: [userid]}
    }])[0];
};

/**
 *
 * @param {String} message Greeting message
 * @param {Number} userid ID of user
 * @returns
 */
export const saveGreeting = (message, userid = 0) => {
    return fetchMany([{
        methodname: 'local_greetings_add_greeting',
        args: {userid: userid, message: message}
    }])[0];
};
