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

 * If the email doesn't exist, then the auth plugin creates the user.
 * If the email exist (and the user has for auth plugin this current one),
 * then the plugin login the user related to this email.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}

require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');
require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');

/**
 * Google/Facebook/Messenger Oauth2 authentication plugin.
 */
class auth_plugin_googleoauth2 extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'googleoauth2';
        $this->roleauth = 'auth_googleoauth2';
        $this->errorlogtag = '[AUTH GOOGLEOAUTH2] ';
        $this->config = get_config('auth/googleoauth2');
        $this->token = null;
    }

    /**
     * Prevent authenticate_user_login() to update the password in the DB
     * @return boolean
     */
    public function prevent_local_passwords() {
        return true;
    }

    /**
     * Authenticates user against the selected authentication provide (Google, Facebook...)     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        global $DB, $CFG;

        // Retrieve the user matching username.
        $user = $DB->get_record('user', array('username' => $username,
            'mnethostid' => $CFG->mnet_localhost_id));

        // Username must exist and have the right authentication method.
        if (!empty($user) && ($user->auth == 'googleoauth2')) {
            $code = optional_param('code', false, PARAM_TEXT);
            if (empty($code)) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }

    /**
    * Return true if can create new account
    *
    * @return bool
    */
    function can_create_new_user() {
        return ( $this->config->donotcreatenewuser != 1 );
    }
 
    /**
     * Returns the user information for 'external' users.
     *
     * @return array $result Associative array of user data
     */
    function get_userinfo($username) {
	$result=array();
        // Get User Details from provider token
	if ( $this->token ) {
           $result = (array) $provider->get_user_details($this->token);
           debugging('Delivery userinfo: '.print_r($result,true), DEBUG_DEVELOPER);
        }
	return $result;
    }

    /**
     * Return true if the email domain is valid
     *
     * @return bool
    */
    function email_auth_domains($email) {
        $auth_domains = $this->config->authdomains;
        if (!empty(trim($auth_domains))) {
                $allowedpatterns = explode(',', $auth_domains);
                foreach ($allowedpatterns as $allowedpattern) {
                        $allowedpattern = trim($allowedpattern);
                        if (strlen($allowedpattern) == 0) {
                                continue;
                        }
                        if (strpos($allowedpattern, '.') === 0) {
                            if (strpos(strrev($email), strrev($allowedpattern)) === 0) {
                                // Subdomains are in a form ".example.com" - matches "xxx@anything.example.com".
                                return true;
                            }
                        } else {
                            if (strpos(strrev($email), strrev('@'.$allowedpattern)) === 0) {
                                return true;
                            }
                        }
                }
                return false;
        }
        return true;
    }

    /**
     * Authentication hook - is called every time user hit the login page
     * The code is run only if the param code is mentionned.
     */
    public function loginpage_hook() {
        global $USER, $SESSION, $CFG, $DB;

        // Check the Google authorization code.
        $authorizationcode = optional_param('code', '', PARAM_TEXT);
        if (!empty($authorizationcode)) {

            $authprovider = required_param('authprovider', PARAM_ALPHANUMEXT);
            require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/'.$authprovider.'.php');
            $providerclassname = 'provideroauth2' . $authprovider;
            $provider = new $providerclassname();

            $this->token = $provider->getAccessToken('authorization_code', [
               'code' => $authorizationcode
            ]);

            $accesstoken = $this->token->getToken();
            $refreshtoken = $this->token->getRefreshToken();
            $tokenexpires = $this->token->getExpires();

            // With access token request by curl the email address.
            if (!empty($accesstoken)) {

		// Get User Details from provider implementation
                $userdetails = $provider->get_user_details($this->token);

                // Throw an error if the email address is not passed by provider.
                if (empty($userdetails->email) || $userdetails->email != clean_param($userdetails->email, PARAM_EMAIL)) {
		    print_error(get_string('couldnotgetuseremail', 'auth_googleoauth2'));
		    die;
                }

                // Prohibit login if email belongs to the prohibited domain.
                if ($err = email_is_not_allowed($userdetails->email)) {
                    throw new moodle_exception($err, 'auth_googleoauth2');
                }
			
                // Throw an error if the email address is not verified.
                if (!$userdetails->emailverified) {
                    throw new moodle_exception('emailaddressmustbeverified', 'auth_googleoauth2');
                }

                // Check login if email belongs to authorized domains
                if (!$this->email_auth_domains($userdetails->email)) {
                    throw new moodle_exception('emailnotallowed', 'auth_googleoauth2');
                }

                // Get the user.
                // Don't bother with auth = googleoauth2 because authenticate_user_login() will fail if it's not 'googleoauth2'.
                $user = $DB->get_record('user',
                    array('email' => $userdetails->email, 'deleted' => 0, 'mnethostid' => $CFG->mnet_localhost_id));

                // Create the user if it doesn't exist.
                if (empty($user)) {
                    // Deny login if setting "Prevent account creation when authenticating" is on.
                    if ($CFG->authpreventaccountcreation) {
                        throw new moodle_exception("noaccountyet", "auth_googleoauth2");
                    }
		    // Check if the plugin can create a new account
                    if (!$this->can_create_new_user()) {
                        throw new moodle_exception('cantcreatenewuser', 'auth_googleoauth2');
                    }

                    // Get following incremented username: this has to be done in mutual esclusion.
                    $userprefix = core_text::strtolower($this->config->userprefix);

                    // Mutual Lock plugin activities
                    $timeout = 5; // 5 seconds.
                    // A namespace for the locks. Must be prefixed with the component name to prevent conflicts.
                    $locktype = 'auth_googleoauth2'; 
                    $resource = 'usercreationnumber';
 
                    // Get an instance of the currently configured lock_factory.
                    $lockfactory = \core\lock\lock_config::get_lock_factory('auth_googleoauth2');

                    // Start to count from last saved config
                    $lastusernumber = $this->config->lastusernumber;
                    $lastusernumber = empty($lastusernumber) ? 1 : $lastusernumber + 1;

                    // Get a new lock for the resource, wait for it if needed.
                    if ($lock = $lockfactory->get_lock($resource, $timeout)) {
                        // Check the user doesn't exist.
                        $nextuser = $DB->record_exists('user', array('username' => $userprefix.$lastusernumber));
                        while ($nextuser) {
                            $lastusernumber++;
                            $nextuser = $DB->record_exists('user', array('username' => $userprefix.$lastusernumber));
                        }
                        set_config('lastusernumber', $lastusernumber, 'auth/googleoauth2');

                        // Release the lock once finished.
                        $lock->release();
                    } else {
                        // We did not get access to the resource in time, give up.
                        throw new moodle_exception('errorcreatinguserlocktimeout','auth_googleoauth2');
                    }

                    $username = $userprefix . $lastusernumber;

                    // Some providers allow empty firstname and lastname.
                    if (empty($userdetails->firstname)) {
                        $userdetails->firstname = get_string('unknownfirstname', 'auth_googleoauth2');
                    }
                    if (empty($userdetails->lastname)) {
                        $userdetails->lastname = get_string('unknownlastname', 'auth_googleoauth2');
                    }

                    // Retrieve country and city if the provider failed to give it.
                    if (!isset($userdetails->country) || !isset($userdetails->city)) {
                        $googleipinfodbkey = $this->config->googleipinfodbkey;
                        if (!empty($googleipinfodbkey)) {
                            require_once($CFG->libdir . '/filelib.php');
                            $curl = new curl();
                            $locationdata = $curl->get('http://api.ipinfodb.com/v3/ip-city/?key=' .
                                $googleipinfodbkey . '&ip='. getremoteaddr() . '&format=json' );
                            $locationdata = json_decode($locationdata);
                        }
                        if (!empty($locationdata)) {
                            // TODO: check that countryCode does match the Moodle country code.
                            $userdetails->country = isset($userdetails->country) ? isset($userdetails->country) : $locationdata->countryCode;
                            $userdetails->city = isset($userdetails->city) ? isset($userdetails->city) : $locationdata->cityName;
                        }
                    }

                    create_user_record($username, '', 'googleoauth2');
                } else {
                    $username = $user->username;
                }

                // Authenticate the user.
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
                $userid = empty($user) ? 'new user' : $user->id;
                $user = authenticate_user_login($username, null);
                if (isset($user->id)) {

                    // Set a cookie to remember what auth provider was selected.
                    setcookie('MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie, $authprovider,
                            time() + (DAYSECS * 60), $CFG->sessioncookiepath,
                            $CFG->sessioncookiedomain, $CFG->cookiesecure,
                            $CFG->cookiehttponly);

                    // Prefill more user information if new user.
                    if (!empty($userdetails)) {
                        $userdetails->id = $user->id;
                        $DB->update_record('user', $userdetails);
                        $user = (object) array_merge((array) $user, (array) $userdetails);
                    }

                    complete_user_login($user);

                    // Let's save/update the access token for this user.
                    if ($this->config->saveaccesstoken) {
                        $existingaccesstoken = $DB->get_record('auth_googleoauth2_user_idps',
                            array('userid' => $user->id, 'provider' => $authprovider));
                        if (empty($existingaccesstoken)) {
                            $accesstokenrow = new stdClass();
                            $accesstokenrow->userid = $user->id;
                            $accesstokenrow->provideruserid = $userdetails->uid;
                            $accesstokenrow->provider = $authprovider;
                            $accesstokenrow->accesstoken = $accesstoken;
                            $accesstokenrow->refreshtoken = $refreshtoken;
                            $accesstokenrow->expires = $tokenexpires;

                            $DB->insert_record('auth_googleoauth2_user_idps', $accesstokenrow);
                        } else {
                            $existingaccesstoken->accesstoken = $accesstoken;
                            $DB->update_record('auth_googleoauth2_user_idps', $existingaccesstoken);
                        }
                    }

                    // Check if the user picture is the default and retrieve the provider picture.
                    if (empty($user->picture)) {
                        if (!empty($userdetails->imageUrl)) {
                            $this->set_profile_picture($user, $userdetails->imageUrl);
                        }
                    }

                    // Create event for authenticated user.
                    $event = \auth_googleoauth2\event\user_loggedin::create(
                        array('context' => context_system::instance(),
                            'objectid' => $user->id, 'relateduserid' => $user->id,
                            'other' => array('accesstoken' => $accesstoken)));
                    $event->trigger();

                    // Redirection.
                    if (user_not_fully_set_up($USER)) {
                        $urltogo = $CFG->wwwroot.'/user/edit.php';
                        // We don't delete $SESSION->wantsurl yet, so we get there later.
                    } else if (isset($SESSION->wantsurl) && (strpos($SESSION->wantsurl, $CFG->wwwroot) === 0)) {
                        $urltogo = $SESSION->wantsurl;    // Because it's an address in this site.
                        unset($SESSION->wantsurl);
                    } else {
                        // No wantsurl stored or external - go to homepage.
                        $urltogo = $CFG->wwwroot.'/';
                        unset($SESSION->wantsurl);
                    }

                    // Add my record to statistics table with subtype information
                    $loginrecord = array('userid' => $USER->id, 'time' => time(),
                        'auth' => 'googleoauth2', 'subtype' => $authprovider);
                    $DB->insert_record('auth_googleoauth2_logins', $loginrecord);

                    redirect($urltogo);
                } else {
                    // Authenticate_user_login() failure, probably email registered by another auth plugin.
                    // Do a check to confirm this hypothesis.
                    $userexist = $DB->get_record('user', array('email' => $userdetails->email));
                    if (!empty($userexist) && $userexist->auth != 'googleoauth2') {
                        $a = new stdClass();
                        $a->loginpage = (string) new moodle_url(empty($CFG->alternateloginurl) ? '/login/index.php' : $CFG->alternateloginurl);
                        $a->forgotpass = (string) new moodle_url('/login/forgot_password.php');
                        throw new moodle_exception('couldnotauthenticateuserlogin', 'auth_googleoauth2', '', $a);
                    } else {
                        throw new moodle_exception('couldnotauthenticate', 'auth_googleoauth2');
                    }
                }
                debugging('Complete User Details: '.print_r($userdetails,true), DEBUG_DEVELOPER);
            } else {
                throw new moodle_exception('couldnotgetgoogleaccesstoken', 'auth_googleoauth2');
            }
        } else {
            // If you are having issue with the display buttons option, add the button code directly in the theme login page.
            if ($this->config->displaybuttons
                // Check manual parameter that indicate that we are trying to log a manual user.
                // We can add more param check for others provider but at the end,
                // the best way may be to not use the displaybuttons option and
                // add the button code directly in the theme login page.
                && empty($_POST['username'])
                && empty($_POST['password'])) {
                // Display the button on the login page.
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');

                // Insert the html code below the login field.
                // Code/Solution from Elcentra plugin: https://moodle.org/plugins/view/auth_elcentra.
                global $PAGE, $CFG;
                $PAGE->requires->jquery();
                $content = str_replace(array("\n", "\r"), array("\\\n", "\\\r"), auth_googleoauth2_display_buttons(false));
                $PAGE->requires->css('/auth/googleoauth2/style.css');
                $PAGE->requires->js_init_code("buttonsCodeOauth2 = '$content';");
                $PAGE->requires->js(new moodle_url($CFG->httpswwwroot . "/auth/googleoauth2/script.js"));
            }
        }
    }

    /**
     * Retrieve the profile picture and save it in moodle.
     */
    private function set_profile_picture($user, $profilepicurl) {
        global $CFG, $DB;

        debugging('set user '.$user->username.' profile picture from oauth2 provider to url: '.$profilepicurl, DEBUG_DEVELOPER);
        require_once($CFG->libdir . '/filelib.php');
        require_once($CFG->libdir . '/gdlib.php');
        $imagefilename = $CFG->tempdir . '/googleoauth2-portrait-' . $user->id;
        $imagecontents = download_file_content($profilepicurl);
        file_put_contents($imagefilename, $imagecontents);
        if ($newrev = process_new_icon(context_user::instance($user->id),
            'user', 'icon', 0, $imagefilename)) {
            $DB->set_field('user', 'picture', $newrev, array('id' => $user->id));
        }
        unlink($imagefilename);
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $config An object containing all the data for this page.
     * @param string $error
     * @param array $user_fields
     * @return void
     */
    function config_form($config, $err, $user_fields) {
        include 'config.html';
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    public function process_config($config) {
        // Set to defaults if undefined.

        $providers = provider_list();

        foreach ($providers as $providername) {
            $clientidname = $providername . 'clientid';
            $clientsecretname = $providername . 'clientsecret';

            // Set to defaults if undefined.
            if (!isset($config->{$clientidname})) {
                $config->{$clientidname} = '';
            }
            if (!isset($config->{$clientsecretname})) {
                $config->{$clientsecretname} = '';
            }

            // Save settings.
            set_config($clientidname, $config->{$clientidname}, 'auth/googleoauth2');
            set_config($clientsecretname, $config->{$clientsecretname}, 'auth/googleoauth2');
        }

        if (!isset ($config->userprefix)) {
            $config->userprefix = 'social_user_';
        }
        if (!isset ($config->displaybuttons)) {
            $config->displaybuttons = 0;
        }
        if (!isset ($config->authdomains)) {
            $config->authdomains = '';
        }
        if (!isset ($config->donotcreatenewuser)) {
            $config->donotcreatenewuser = 0;
        }
        if (!isset ($config->saveaccesstoken)) {
            $config->saveaccesstoken = 0;
        }
        if (!isset ($config->providerlinksstyle)) {
            $config->providerlinksstyle = 'horizontal';
        }

        set_config('googleipinfodbkey', $config->googleipinfodbkey, 'auth/googleoauth2');
        set_config('userprefix', core_text::strtolower($config->userprefix), 'auth/googleoauth2');
        set_config('displaybuttons', $config->displaybuttons, 'auth/googleoauth2');
        set_config('authdomains', $config->authdomains, 'auth/googleoauth2');
        set_config('donotcreatenewuser', $config->donotcreatenewuser, 'auth/googleoauth2');
        set_config('saveaccesstoken', $config->donotcreatenewuser, 'auth/googleoauth2');
        set_config('providerlinksstyle', $config->providerlinksstyle, 'auth/googleoauth2');

        return true;
    }

    /**
     * Called when the user record is updated.
     *
     * We check there is no hack-attempt by a user to change his/her email address
     *
     * @param mixed $olduser     Userobject before modifications    (without system magic quotes)
     * @param mixed $newuser     Userobject new modified userobject (without system magic quotes)
     * @return boolean result
     *
     */
    public function user_update($olduser, $newuser) {
        if ($olduser->email != $newuser->email) {
            return false;
        }
        return true;
    }
}
