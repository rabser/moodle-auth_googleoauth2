<?php
// This file is part of Oauth2 authentication plugin for Moodle.
//
// Oauth2 authentication plugin for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Oauth2 authentication plugin for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Oauth2 authentication plugin for Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Facebook provider for googleoauth2 auth plugin.
 *
 * @package    auth_googleoauth2
 * @copyright  2015 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');

class provideroauth2facebook extends League\OAuth2\Client\Provider\Facebook {

    /** @var string Classes to use for the login button. */
    public $sskstyle = 'facebook';

    /** @var string Identifier of the provider. */
    public $name = 'facebook';

    /** @var string Human-readable name of the provider. */
    public $readablename = 'Facebook';

    /** @var array Scopes to request. */
    public $scopes = array('email');

    /** @var string raw token data */
    public $rawdata = null;

    /**
     * Constructor.
     *
     * @throws Exception
     * @throws dml_exception
     */
    public function __construct() {
        global $CFG;

        $url = preg_replace("/^http:/i", "https:", $CFG->wwwroot);
        parent::__construct([
            'clientId'      => get_config('auth/googleoauth2', $this->name . 'clientid'),
            'clientSecret'  => get_config('auth/googleoauth2', $this->name . 'clientsecret'),
            'redirectUri'   => $url .'/auth/googleoauth2/' . $this->name . '_redirect.php',
            'graphApiVersion'        => "v2.8",
            'scopes'        => $this->scopes
        ]);
    }

    /**
     * Is the provider enabled.
     *
     * @return bool
     * @throws Exception
     * @throws dml_exception
     */
    public function isenabled() {
        return (get_config('auth/googleoauth2', $this->name . 'clientid')
            && get_config('auth/googleoauth2', $this->name . 'clientsecret'));
    }

    /**
     * The html button.
     *
     * @param $authurl
     * @param $providerdisplaystyle
     * @return string
     * @throws coding_exception
     */
    public function html_button($authurl, $providerdisplaystyle) {
        return googleoauth2_html_button($authurl, $providerdisplaystyle, $this);
    }

    /**
     * The user details
     *
     * @return object
     * @throws coding_exception
     */
    public function get_user_details($token) {
        $userdetails = new stdClass();

        debugging('Extract user information from '.$this->readablename.' token' , DEBUG_DEVELOPER);
        try {
            if (!empty($token->getToken())) {
  		    $resource = $this->getResourceOwner($token);
                    $resourcearray = $resource->toArray();
                    $this->rawdata = $resourcearray;
                    $userdetails->email = $resourcearray['email'];
                    $userdetails->firstname = $resourcearray['first_name'];
                    $userdetails->lastname = $resourcearray['last_name'];
                    $userdetails->imageUrl = $resourcearray['picture']['data']['url'];
                    $userdetails->emailverified = 1;
                    $userdetails->uid = $resourcearray['id'];
            }
        } catch (Exception $e) {
            // Failed to get user details.
            throw new moodle_exception('faileduserdetails', 'auth_googleoauth2');
        }

        debugging('Raw DATA: '.print_r($this->rawdata,true), DEBUG_DEVELOPER);
        debugging('User Details: '.print_r($userdetails,true), DEBUG_DEVELOPER);
	return $userdetails;
    }
}
