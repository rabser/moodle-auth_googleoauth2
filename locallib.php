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
 * Redirector for googleoauth2 auth plugin.
 *
 * @package    auth_googleoauth2
 * @copyright  2015 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
  *
  * Retrieve the login provider stats.
  *
  * @return object
  */
function googleoauth2_get_stats($periodindays = 60) {
    global $DB;

    // Retrieve the logins.
    $sql = 'time > :time';
    $logins = $DB->get_records_select('auth_googleoauth2_logins', $sql,
        array('time' => strtotime('-' . $periodindays . ' days', time())));

    // Retrieve the moodle auth stats.

    $loginstats = new stdClass();
    $providers = provider_list();
    foreach ($providers as $providername) {
        $loginstats->$providername = 0;
    }
    $loginstats->moodle = 0;
    $loginstats->periodindays = $periodindays;

    // Retrieve the provider stats.
    foreach ($logins as $login) {
        if ($login->auth !== 'googleoauth2') {
            $loginstats->moodle += 1;
        } else {
            $providername=$login->subtype;
            $loginstats->$providername += 1;
            }
        }
    return $loginstats;
}
