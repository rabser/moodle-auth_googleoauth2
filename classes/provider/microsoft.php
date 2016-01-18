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

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;

require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');

/**
 * Oauth2 Provider implementation for Microsoft app model v2.
 */
class provideroauth2microsoft extends League\OAuth2\Client\Provider\AbstractProvider {

    /** @var string Classes to use for the login button. */
    public $sskstyle = 'microsoft';

    /** @var string Identifier of the provider. */
    public $name = 'microsoft'; // It must be the same as the XXXXX in the class name provideroauth2XXXXX.

    /** @var string Human-readable name of the provider. */
    public $readablename = 'Microsoft (v2)';

    /** @var string The authorization header to use. */
    public $authorizationHeader = 'Bearer';

    /** @var array Scopes to request. */
    public $scopes = ['https://graph.microsoft.com/User.Read'];

    /**
     * Constructor.
     *
     * @throws Exception
     * @throws dml_exception
     */
    public function __construct() {
        global $CFG;
        parent::__construct([
            'clientId' => get_config('auth/googleoauth2', $this->name.'clientid'),
            'clientSecret' => get_config('auth/googleoauth2', $this->name.'clientsecret'),
            'redirectUri' => $CFG->wwwroot.'/auth/googleoauth2/'.$this->name.'_redirect.php',
            'scopes' => $this->scopes
        ]);
    }

    /**
     * The oauth2 authorization URL.
     *
     * @return string The URL.
     */
    public function urlAuthorize() {
        return 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';
    }

    /**
     * The oauth2 token URL.
     *
     * @return string The URL.
     */
    public function urlAccessToken() {
        return 'https://login.microsoftonline.com/common/oauth2/v2.0/token';
    }

    /**
     * Get the URL to retrieve user information.
     *
     * @param AccessToken $token The access token to use.
     * @return string To user details URL.
     */
    public function urlUserDetails(AccessToken $token) {
        return 'https://graph.microsoft.com/v1.0/me';
    }

    /**
     * Parse user details response into standardized user details.
     *
     * @param \stdClass $response The user details response.
     * @param AccessToken $token The access token to use if necessary.
     * @return User User object with populated user details.
     */
    public function userDetails($response, AccessToken $token) {
        $userdata = [
            'uid' => $this->userUid($response, $token),
            'name' => $response->displayName,
            'firstname' => $response->givenName,
            'lastname' => $response->surname,
            'email' => $this->userEmail($response, $token),
        ];

        $user = new User();
        $user->exchangeArray($userdata);
        return $user;
    }

    /**
     * Extract the user ID from the user details response.
     *
     * @param \stdClass $response The user details response.
     * @param AccessToken $token The access token to use if necessary.
     * @return string The user identifier.
     */
    public function userUid($response, AccessToken $token) {
        return $response->id;
    }

    /**
     * Extract the user's email from the user details response.
     *
     * @param \stdClass $response The user details response.
     * @param AccessToken $token The access token to use if necessary.
     * @return string The user's email.
     */
    public function userEmail($response, AccessToken $token) {
        $mail = null;
        if (!empty($response->mail)) {
            $mail = $response->mail;
        } else if (!empty($response->userPrincipalName)) {
            $mail = $response->userPrincipalName;
        }
        return $mail;
    }

    /**
     * Extract the user's name from the user details response.
     *
     * @param \stdClass $response The user details response.
     * @param AccessToken $token The access token to use if necessary.
     * @return array The user's name. Items are first and last name.
     */
    public function userScreenName($response, AccessToken $token) {
        return [$response->givenName, $response->surname];
    }

    /**
     * Is the provider enabled.
     *
     * @return bool
     * @throws Exception
     * @throws dml_exception
     */
    public function isenabled() {
        return (get_config('auth/googleoauth2', $this->name.'clientid')
            && get_config('auth/googleoauth2', $this->name.'clientsecret'));
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
}
