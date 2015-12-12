<?php
// This file is part of Moodle Google Oauth2 plugin
//
// It is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with it.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observer for googleoauth2 auth plugin.
 *
 * @package    auth_googleoauth2
 * @copyright  2015 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer for googleoauth2.
 */
class auth_googleoauth2_observer {

    /**
     * Triggered when '\core\event\user_loggedin' event is triggered.
     *
     * @param \core\event\user_loggedin $event
     */
    public static function userloggedin(\core\event\user_loggedin $event) {
        global $DB, $CFG;

        $user = $DB->get_record('user', array('id' => $event->userid));

        if ($user->auth != 'googleoauth2') {
            $loginrecord = array('userid' => $user->id, 'time' => time(), 'auth' => $user->auth);
            $DB->insert_record('auth_googleoauth2_logins', $loginrecord);
        }
    }

}
