<?php

/**
 * @author Jerome Mouneyrac
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle multiauth
 *
 * Authentication Plugin: Google/Facebook/Messenger Authentication
 * If the email doesn't exist, then the auth plugin creates the user.
 * If the email exist (and the user has for auth plugin this current one),
 * then the plugin login the user related to this email.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');
require_once $CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php';
require_once $CFG->dirroot . '/auth/googleoauth2/lib.php';

/**
 * Google/Facebook/Messenger Oauth2 authentication plugin.
 */
class auth_plugin_googleoauth2 extends auth_plugin_base {

    /**
     * Constructor.
     */
    function auth_plugin_googleoauth2() {
        $this->authtype = 'googleoauth2';
        $this->roleauth = 'auth_googleoauth2';
        $this->errorlogtag = '[AUTH GOOGLEOAUTH2] ';
        $this->config = get_config('auth/googleoauth2');
    }

    /**
     * Prevent authenticate_user_login() to update the password in the DB
     * @return boolean
     */
    function prevent_local_passwords() {
        return true;
    }

    /**
     * Authenticates user against the selected authentication provide (Google, Facebook...)
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        global $DB, $CFG;

        //retrieve the user matching username
        $user = $DB->get_record('user', array('username' => $username,
            'mnethostid' => $CFG->mnet_localhost_id));

        //username must exist and have the right authentication method
        if (!empty($user) && ($user->auth == 'googleoauth2')) {
            $code = optional_param('code', false, PARAM_TEXT);
            if(empty($code)){
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
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Authentication hook - is called every time user hit the login page
     * The code is run only if the param code is mentionned.
     */
    function loginpage_hook() {
        global $USER, $SESSION, $CFG, $DB;

        //check the Google authorization code
        $authorizationcode = optional_param('code', '', PARAM_TEXT);
        if (!empty($authorizationcode)) {

            $authprovider = required_param('authprovider', PARAM_ALPHANUMEXT);
            require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/'.$authprovider.'.php');
            $providerclassname = 'provideroauth2' . $authprovider;
            $provider = new $providerclassname();

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $authorizationcode
            ]);
            error_log(print_r($token, true));
            $accesstoken = $token->accessToken;
            $refreshtoken = $token->refreshToken;
            $tokenexpires = $token->expires;

            //with access token request by curl the email address
            if (!empty($accesstoken)) {

                $provideruserid = '';

                try {
                    // We got an access token, let's now get the user's details
                    $userDetails = $provider->getUserDetails($token);
                    // Use these details to create a new profile
                    error_log(print_r($authprovider . ' user details', true));
                    error_log(print_r($userDetails, true));

                    switch ($authprovider) {
                        case 'battlenet':
                            // Battlenet as no email notion - TODO: need to check the idp table for matching user and request user to add his email. It will be similar logic for twitter.
                            $useremail = $userDetails->id . '@fakebattle.net';
                            break;
                        case 'github':
                            $useremails = $provider->getUserEmails($token);
                            error_log(print_r($authprovider . ' user emails', true));
                            error_log(print_r($useremails, true));
                            // Going to try to find someone with a similar email using googleoauth2 auth.
                            $fallbackuseremail = '';
                            foreach($useremails as $githubuseremail) {
                                if ($githubuseremail->verified) {
                                    if ($DB->record_exists('user', array('auth' => 'googleoauth2', 'email' => $githubuseremail->email))) {
                                        $useremail = $githubuseremail->email;
                                    }
                                    $fallbackuseremail = $githubuseremail->email;
                                }
                            }
                            // If we didn't find anyone then we take a verified email address.
                            if (empty($useremail)) {
                                $useremail = $fallbackuseremail;
                            }
                            error_log(print_r($useremail, true));
                            break;
                        case 'vk':
                            // vk doesn't return the email address?
                            if ($userDetails->uid) {
                                $useremail = 'id'.$userDetails->uid.'@vkmessenger.com';
                            };
                            break;
                        default:
                            $useremail = $userDetails->email;
                            break;
                    }

                    $verified = 1;
                } catch (Exception $e) {
                    // Failed to get user details
                    error_log(print_r('Failed Google user details', true));
                    error_log(print_r($e, true));
                }

                //throw an error if the email address is not verified
                if (!$verified) {
                    throw new moodle_exception('emailaddressmustbeverified', 'auth_googleoauth2');
                }

                // Prohibit login if email belongs to the prohibited domain
                if ($err = email_is_not_allowed($useremail)) {
                   throw new moodle_exception($err, 'auth_googleoauth2');
                }

                //if email not existing in user database then create a new username (userX).
                if (empty($useremail) or $useremail != clean_param($useremail, PARAM_EMAIL)) {
                    throw new moodle_exception('couldnotgetuseremail');
                    //TODO: display a link for people to retry
                }
                //get the user - don't bother with auth = googleoauth2 because
                //authenticate_user_login() will fail it if it's not 'googleoauth2'
                $user = $DB->get_record('user', array('email' => $useremail, 'deleted' => 0, 'mnethostid' => $CFG->mnet_localhost_id));

                //create the user if it doesn't exist
                if (empty($user)) {

                    error_log(print_r('Creating a new user!', true));

                    // deny login if setting "Prevent account creation when authenticating" is on
                    if($CFG->authpreventaccountcreation) throw new moodle_exception("noaccountyet", "auth_googleoauth2");


                    //get following incremented username
                    $googleuserprefix = core_text::strtolower(get_config('auth/googleoauth2', 'googleuserprefix'));
                    $lastusernumber = get_config('auth/googleoauth2', 'lastusernumber');
                    $lastusernumber = empty($lastusernumber)? 1 : $lastusernumber++;
                    //check the user doesn't exist
                    $nextuser = $DB->record_exists('user', array('username' => $googleuserprefix.$lastusernumber));
                    while ($nextuser) {
                        $lastusernumber++;
                        $nextuser = $DB->record_exists('user', array('username' => $googleuserprefix.$lastusernumber));
                    }
                    set_config('lastusernumber', $lastusernumber, 'auth/googleoauth2');
                    $username = $googleuserprefix . $lastusernumber;

                    //retrieve more information from the provider
                    $newuser = new stdClass();
                    $newuser->email = $useremail;

                    switch ($authprovider) {
                        case 'battlenet':
                            // Battlenet as no firstname/lastname notion.
                            $newuser->firstname =  $userDetails->display_name;
                            $newuser->lastname =  '['.$userDetails->clan_tag.']';
                            break;
                        case 'github':
                        case 'dropbox':
                            //As Github/Dropbox doesn't provide firstname/lastname, we'll split the name at the first whitespace.
                            $githubusername = explode(' ', $userDetails->name, 2);
                            $newuser->firstname =  $githubusername[0];
                            $newuser->lastname =  $githubusername[1];
                            break;
                        default:
                            $newuser->firstname =  $userDetails->firstName;
                            $newuser->lastname =  $userDetails->lastName;
                            break;
                    }

                    // Some providers allow empty firstname and lastname.
                    if (empty($newuser->firstname)) {
                        $newuser->firstname = get_string('unknownfirstname', 'auth_googleoauth2');
                    }
                    if (empty($newuser->lastname)) {
                        $newuser->lastname = get_string('unknownlastname', 'auth_googleoauth2');
                    }

                    //retrieve country and city if the provider failed to give it
                    if (!isset($newuser->country) or !isset($newuser->city)) {
                        $googleipinfodbkey = get_config('auth/googleoauth2', 'googleipinfodbkey');
                        if (!empty($googleipinfodbkey)) {
                            require_once($CFG->libdir . '/filelib.php');
                            $curl = new curl();
                            $locationdata = $curl->get('http://api.ipinfodb.com/v3/ip-city/?key=' .
                                $googleipinfodbkey . '&ip='. getremoteaddr() . '&format=json' );
                            $locationdata = json_decode($locationdata);
                        }
                        if (!empty($locationdata)) {
                            //TODO: check that countryCode does match the Moodle country code
                            $newuser->country = isset($newuser->country)?isset($newuser->country):$locationdata->countryCode;
                            $newuser->city = isset($newuser->city)?isset($newuser->city):$locationdata->cityName;
                        }
                    }

                    create_user_record($username, '', 'googleoauth2');
                } else {
                    $username = $user->username;
                }

                //authenticate the user
                //TODO: delete this log later
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
                $userid = empty($user)?'new user':$user->id;
                oauth_add_to_log(SITEID, 'auth_googleoauth2', '', '', $username . '/' . $useremail . '/' . $userid);
                $user = authenticate_user_login($username, null);
                if ($user) {

                    //set a cookie to remember what auth provider was selected
                    setcookie('MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie, $authprovider,
                            time()+(DAYSECS*60), $CFG->sessioncookiepath,
                            $CFG->sessioncookiedomain, $CFG->cookiesecure,
                            $CFG->cookiehttponly);

                    //prefill more user information if new user
                    if (!empty($newuser)) {
                        $newuser->id = $user->id;
                        $DB->update_record('user', $newuser);
                        $user = (object) array_merge((array) $user, (array) $newuser);
                    }

                    complete_user_login($user);

                    // Let's save/update the access token for this user.
                    $existingaccesstoken = $DB->get_record('auth_googleoauth2_user_idps', 
                        array('userid' => $user->id, 'provider' => $authprovider));
                    if (empty($existingaccesstoken)) {
                        $accesstokenrow = new stdClass();
                        $accesstokenrow->userid = $user->id;
                        switch ($authprovider) {
                            case 'battlenet':
                                $accesstokenrow->provideruserid = $userDetails->id;
                                break;
                            default:
                                $accesstokenrow->provideruserid = $userDetails->uid;
                                break;
                        }

                        $accesstokenrow->provider = $authprovider;
                        $accesstokenrow->accesstoken = $accesstoken;
                        $accesstokenrow->refreshtoken = $refreshtoken;
                        $accesstokenrow->expires = $tokenexpires;
                        $DB->insert_record('auth_googleoauth2_user_idps', $accesstokenrow);
                    } else {
                        $existingaccesstoken->accesstoken = $accesstoken;
                        $DB->update_record('auth_googleoauth2_user_idps', $existingaccesstoken);
                    }

                    // Check if the user picture is the default and retrieve the provider picture.
                    if (empty($user->picture)) {
                        switch ($authprovider) {
                            case 'battlenet':
                                error_log(print_r('Downloading profile picture', true));
                                require_once($CFG->libdir . '/filelib.php');
                                require_once($CFG->libdir . '/gdlib.php');
                                $imagefilename = $CFG->tempdir . '/googleoauth2-portrait-' . $user->id;
                                $imagecontents = download_file_content($userDetails->portrait_url);
                                file_put_contents($imagefilename, $imagecontents);
                                if ($newrev = process_new_icon(context_user::instance($user->id), 'user', 'icon', 0, $imagefilename)) {
                                    $DB->set_field('user', 'picture', $newrev, array('id' => $user->id));
                                }
                                unlink($imagefilename);
                                break;
                            default:
                                // TODO
                                break;
                        }
                    }

                    // Create event for authenticated user.
                    $event = \auth_googleoauth2\event\user_loggedin::create(
                        array('context'=>context_system::instance(),
                            'objectid'=>$user->id, 'relateduserid'=>$user->id,
                            'other'=>array('accesstoken' => $accesstoken)));
                    $event->trigger();

                    // Redirection
                    if (user_not_fully_set_up($USER)) {
                        $urltogo = $CFG->wwwroot.'/user/edit.php';
                        // We don't delete $SESSION->wantsurl yet, so we get there later
                    } else if (isset($SESSION->wantsurl) and (strpos($SESSION->wantsurl, $CFG->wwwroot) === 0)) {
                        $urltogo = $SESSION->wantsurl;    // Because it's an address in this site
                        unset($SESSION->wantsurl);
                    } else {
                        // No wantsurl stored or external - go to homepage
                        $urltogo = $CFG->wwwroot.'/';
                        unset($SESSION->wantsurl);
                    }
                    redirect($urltogo);
                } else {
                    // authenticate_user_login() failure, probably email registered by another auth plugin
                    // Do a check to confirm this hypothesis.
                    $userexist = $DB->get_record('user', array('email' => $useremail));
                    if (!empty($userexist) and $userexist->auth != 'googleoauth2') {
                        $a = new stdClass();
                        $a->loginpage = (string) new moodle_url(empty($CFG->alternateloginurl) ? '/login/index.php' : $CFG->alternateloginurl);
                        $a->forgotpass = (string) new moodle_url('/login/forgot_password.php');
                        throw new moodle_exception('couldnotauthenticateuserlogin', 'auth_googleoauth2', '', $a);
                    } else {
                        throw new moodle_exception('couldnotauthenticate', 'auth_googleoauth2');
                    }
                }
            } else {
                throw new moodle_exception('couldnotgetgoogleaccesstoken', 'auth_googleoauth2',
                    '', null, print_r($token, true));
            }
        } else {
            // If you are having issue with the display buttons option, add the button code directly in the theme login page.
            if (get_config('auth/googleoauth2', 'oauth2displaybuttons')
                // Check manual parameter that indicate that we are trying to log a manual user.
                // We can add more param check for others provider but at the end,
                // the best way may be to not use the oauth2displaybuttons option and
                // add the button code directly in the theme login page.
                and empty($_POST['username'])
                and empty($_POST['password'])) {
                // Display the button on the login page.
                require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
                auth_googleoauth2_display_buttons();
            }
        }
    }


    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * TODO: as print_auth_lock_options() core function displays an old-fashion HTML table, I didn't bother writing
     * some proper Moodle code. This code is similar to other auth plugins (04/09/11)
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
        global $OUTPUT, $CFG;

        echo '<table cellspacing="0" cellpadding="5" border="0">
            <tr>
               <td colspan="3">
                    <h2 class="main">';

        print_string('auth_googlesettings', 'auth_googleoauth2');

        $providers = provider_list();

        foreach($providers as $providername) {

            $clientidname = $providername . 'clientid';
            $clientsecretname = $providername . 'clientsecret';

            // set to defaults if undefined
            if (!isset($config->{$clientidname})) {
                $config->{$clientidname} = '';
            }
            if (!isset($config->{$clientsecretname})) {
                $config->{$clientsecretname} = '';
            }

            // client id

            echo '</h2>
               </td>
            </tr>
            <tr>
                <td align="right"><label for="'.$clientidname.'">';

            print_string('auth_'.$clientidname.'_key', 'auth_googleoauth2');

            echo '</label></td><td>';


            echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => $clientidname, 'name' => $clientidname,
                    'class' => $clientidname, 'value' => $config->{$clientidname}));

            if (isset($err[$clientidname])) {
                echo $OUTPUT->error_text($err[$clientidname]);
            }

            echo '</td><td>';
            $parse = parse_url($CFG->wwwroot);
            print_string('auth_'.$clientidname, 'auth_googleoauth2',
                array('jsorigins' => $parse['scheme'].'://'.$parse['host'], 'siteurl' => $CFG->httpswwwroot,
                    'domain' => $CFG->httpswwwroot,
                    'redirecturls' => $CFG->httpswwwroot . '/auth/googleoauth2/'.$providername.'_redirect.php',
                    'callbackurl' => $CFG->httpswwwroot . '/auth/googleoauth2/'.$providername.'_redirect.php',
                    'sitedomain' => $parse['host'])) ;

            echo '</td></tr>';

            // client secret

            echo '<tr>
                <td align="right"><label for="'.$clientsecretname.'">';

            print_string('auth_'.$clientsecretname.'_key', 'auth_googleoauth2');

            echo '</label></td><td>';


            echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => $clientsecretname, 'name' => $clientsecretname,
                    'class' => $clientsecretname, 'value' => $config->{$clientsecretname}));

            if (isset($err[$clientsecretname])) {
                echo $OUTPUT->error_text($err[$clientsecretname]);
            }

            echo '</td><td>';

            print_string('auth_'.$clientsecretname, 'auth_googleoauth2') ;

            echo '</td></tr>';

        }

        if (!isset($config->googleipinfodbkey)) {
            $config->googleipinfodbkey = '';
        }

        if (!isset($config->googleuserprefix)) {
            $config->googleuserprefix = 'social_user_';
        }

        if (!isset($config->oauth2displaybuttons)) {
            $config->oauth2displaybuttons = 1;
        }

        // IPinfoDB

        echo '<tr>
                <td align="right"><label for="googleipinfodbkey">';

        print_string('auth_googleipinfodbkey_key', 'auth_googleoauth2');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => 'googleipinfodbkey', 'name' => 'googleipinfodbkey',
                    'class' => 'googleipinfodbkey', 'value' => $config->googleipinfodbkey));

        if (isset($err["googleipinfodbkey"])) {
            echo $OUTPUT->error_text($err["googleipinfodbkey"]);
        }

        echo '</td><td>';

        print_string('auth_googleipinfodbkey', 'auth_googleoauth2', (object) array('website' => $CFG->wwwroot)) ;

        echo '</td></tr>';

        // User prefix

        echo '<tr>
                <td align="right"><label for="googleuserprefix">';

        print_string('auth_googleuserprefix_key', 'auth_googleoauth2');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input',
                array('type' => 'text', 'id' => 'googleuserprefix', 'name' => 'googleuserprefix',
                    'class' => 'googleuserprefix', 'value' => $config->googleuserprefix));

        if (isset($err["googleuserprefix"])) {
            echo $OUTPUT->error_text($err["googleuserprefix"]);
        }

        echo '</td><td>';

        print_string('auth_googleuserprefix', 'auth_googleoauth2') ;

        echo '</td></tr>';


        // Display buttons

        echo '<tr>
                <td align="right"><label for="oauth2displaybuttons">';

        print_string('oauth2displaybuttons', 'auth_googleoauth2');

        echo '</label></td><td>';

        $checked = empty($config->oauth2displaybuttons)?'':'checked';
        echo html_writer::checkbox('oauth2displaybuttons', 1, $checked, '',
            array('type' => 'checkbox', 'id' => 'oauth2displaybuttons', 'class' => 'oauth2displaybuttons'));

        if (isset($err["oauth2displaybuttons"])) {
            echo $OUTPUT->error_text($err["oauth2displaybuttons"]);
        }

        echo '</td><td>';

        $code = '<code>&lt;?php require_once($CFG-&gt;dirroot . \'/auth/googleoauth2/lib.php\'); auth_googleoauth2_display_buttons(); ?&gt;</code>';
        print_string('oauth2displaybuttonshelp', 'auth_googleoauth2', $code) ;

        echo '</td></tr>';


        /// Block field options
        // Hidden email options - email must be set to: locked
        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value' => 'locked',
                    'name' => 'lockconfig_field_lock_email'));

        //display other field options
        foreach ($user_fields as $key => $user_field) {
            if ($user_field == 'email') {
                unset($user_fields[$key]);
            }
        }
        print_auth_lock_options('googleoauth2', $user_fields, get_string('auth_fieldlocks_help', 'auth'), false, false);

        echo '</table>';
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        // set to defaults if undefined

        $providers = provider_list();

        foreach($providers as $providername) {
            $clientidname = $providername . 'clientid';
            $clientsecretname = $providername . 'clientsecret';

            // set to defaults if undefined
            if (!isset($config->{$clientidname})) {
                $config->{$clientidname} = '';
            }
            if (!isset($config->{$clientsecretname})) {
                $config->{$clientsecretname} = '';
            }

            // save settings
            set_config($clientidname, $config->{$clientidname}, 'auth/googleoauth2');
            set_config($clientsecretname, $config->{$clientsecretname}, 'auth/googleoauth2');

        }
	
        if (!isset ($config->googleuserprefix)) {
            $config->googleuserprefix = 'social_user_';
        }
        if (!isset ($config->oauth2displaybuttons)) {
            $config->oauth2displaybuttons = 0;
        }

        set_config('googleipinfodbkey', $config->googleipinfodbkey, 'auth/googleoauth2');
        set_config('googleuserprefix', core_text::strtolower($config->googleuserprefix), 'auth/googleoauth2');
        set_config('oauth2displaybuttons', $config->oauth2displaybuttons, 'auth/googleoauth2');

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
    function user_update($olduser, $newuser) {
        if ($olduser->email != $newuser->email) {
            return false;
        } else {
            return true;
        }
    }
}
