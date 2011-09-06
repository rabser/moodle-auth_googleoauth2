<?php

/**
 * @author Jerome Mouneyrac
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle multiauth
 *
 * Authentication Plugin: Google Authentication
 * If the gmail email doesn't exist, then the auth plugin creates the user.
 * If the gmail email exist, authenticate as the user related to this email.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');

/**
 * Google Oauth2 authentication plugin.
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
     * Authenticates user against Google
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        return true;
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
     * Authentication choice (CAS or other)
     * Redirection to the CAS form or to login/index.php
     * for other authentication
     */
    function loginpage_hook() {
        global $USER, $SESSION, $CFG, $DB;
        
        $access_token = optional_param('access_token', '', PARAM_TEXT);
        $expires_in = optional_param('expires_in', '', PARAM_INT);
        $token_type = optional_param('token_type', '', PARAM_TEXT);
        $refresh_token = optional_param('refresh_token', '', PARAM_TEXT);
        
        //get the authorization code
        $authorizationcode = optional_param('code', '', PARAM_TEXT);
        if (!empty($authorizationcode)) {

            $clientid = get_config('auth/googleoauth2', 'googleclientid');
            $clientsecret = get_config('auth/googleoauth2', 'googleclientsecret');

            //request by curl an access token and refresh token
            require_once($CFG->libdir . '/filelib.php');
            $curl = new curl();
            $params = array();
            $params['client_id'] = $clientid;
            $params['client_secret'] = $clientsecret;
            $params['code'] = $authorizationcode;
            $params['redirect_uri'] = $CFG->wwwroot . '/login/index.php';
            $params['grant_type'] = 'authorization_code';
            $postreturnvalues = $curl->post('https://accounts.google.com/o/oauth2/token', $params);
            $postreturnvalues = json_decode($postreturnvalues);
            
            $accesstoken = $postreturnvalues->access_token;
            $refreshtoken = $postreturnvalues->refresh_token;
            $expiresin = $postreturnvalues->expires_in;
            $tokentype = $postreturnvalues->token_type;

            //with access token request by curl the email address
            if (!empty($accesstoken)) {

                //get the username matching the email                  
                $params = array();
                $params['access_token'] = $accesstoken;
                $params['alt'] = 'json';
                $postreturnvalues = $curl->get('https://www.googleapis.com/userinfo/email', $params);
                $useremail = json_decode($postreturnvalues)->data->email;

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
                    
                    $isnewuser = true;
                    
                    //get following incremented username
                    $lastusernumber = get_config('auth/googleoauth2', 'lastusernumber');
                    $lastusernumber = empty($lastusernumber)?1:$lastusernumber++;
                    //check the user doesn't exist
                    $nextuser = $DB->get_record('user', 
                            array('username' => get_config('auth/googleoauth2', 'googleuserprefix').$lastusernumber));
                    while (!empty($nextuser)) {
                        $lastusernumber = $lastusernumber +1;
                        $nextuser = $DB->get_record('user', 
                            array('username' => get_config('auth/googleoauth2', 'googleuserprefix').$lastusernumber));
                    }
                    $username = get_config('auth/googleoauth2', 'googleuserprefix') . $lastusernumber;
                
                } else {
                    $username = $user->username;
                }

                //authenticate the user
                $user = authenticate_user_login($username, null);
                if ($user) {
                                                      
                    //try to complete the user information has much as we can in case of new one
                    if (!empty($isnewuser)) {
                        //retrieve more information
                        $userinfo = $curl->get('https://www.googleapis.com/oauth2/v1/userinfo', $params);
                        $userinfo = json_decode($userinfo); //email, id, name, verified_email, given_name, family_name, link, gender, locale

                        $googleipinfodbkey = get_config('auth/googleoauth2', 'googleipinfodbkey');
                        if (!empty($googleipinfodbkey)) {
                            $locationdata = $curl->get('http://api.ipinfodb.com/v3/ip-city/?key=' . 
                                $googleipinfodbkey . '&ip='. getremoteaddr() . '&format=json' );
                            $locationdata = json_decode($locationdata);
                        }

                        //create the user
                        $newuser = array(
                                'id' => $user->id,
                                'email' => $useremail,
                                'auth' => 'googleoauth2');
                        if (!empty($userinfo->given_name)) {
                            $newuser['firstname'] = $userinfo->given_name;
                        }
                        if (!empty($userinfo->family_name)) {
                            $newuser['lastname'] = $userinfo->family_name;
                        }
                        if (!empty($userinfo->locale)) {
                            //$newuser['lang'] = $userinfo->locale;
                            //TODO: convert the locale into correct Moodle language code
                        }
                        if (!empty($locationdata)) {
                            //TODO: check that countryCode does match the Moodle country code
                            $newuser['country'] = $locationdata->countryCode;
                            $newuser['city'] = $locationdata->cityName;
                        }
                        $DB->update_record('user', $newuser);
                    }
                    
                    complete_user_login($user);
                    
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
                }
            } else {
                throw new moodle_exception('couldnotgetgoogleaccesstoken');
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
        global $OUTPUT;    

        // set to defaults if undefined
        if (!isset($config->googleclientid)) {
            $config->googleclientid = '';
        }
        if (!isset($config->googleclientsecret)) {
            $config->googleclientsecret = '';
        }
        if (!isset($config->googleipinfodbkey)) {
            $config->googleipinfodbkey = '';
        }
        if (!isset($config->googleuserprefix)) {
            $config->googleuserprefix = 'google_user_';
        }

        echo '<table cellspacing="0" cellpadding="5" border="0">
            <tr>
               <td colspan="3">
                    <h2 class="main">';

        print_string('auth_googlesettings', 'auth_googleoauth2'); 
        
        // Google client id

        echo '</h2>
               </td>
            </tr>
            <tr>
                <td align="right"><label for="googleclientid">';

        print_string('auth_googleclientid_key', 'auth_googleoauth2');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input', 
                array('type' => 'text', 'id' => 'googleclientid', 'name' => 'googleclientid', 
                    'class' => 'googleclientid', 'value' => $config->googleclientid));

        if (isset($err["googleclientid"])) {
            echo $OUTPUT->error_text($err["googleclientid"]);
        }

        echo '</td><td>';

        print_string('auth_googleclientid', 'auth_googleoauth2') ;

        echo '</td></tr>';
        
        // Google client secret
        
        echo '<tr>
                <td align="right"><label for="googleclientsecret">';

        print_string('auth_googleclientsecret_key', 'auth_googleoauth2');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input', 
                array('type' => 'text', 'id' => 'googleclientsecret', 'name' => 'googleclientsecret', 
                    'class' => 'googleclientsecret', 'value' => $config->googleclientsecret));

        if (isset($err["googleclientsecret"])) {
            echo $OUTPUT->error_text($err["googleclientsecret"]);
        }

        echo '</td><td>';

        print_string('auth_googleclientsecret', 'auth_googleoauth2') ;

        echo '</td></tr>';
        
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

        print_string('auth_googleipinfodbkey', 'auth_googleoauth2') ;

        echo '</td></tr>';
        
        // IPinfoDB
        
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
        
        
        // Block field options

        print_auth_lock_options('googleoauth2', $user_fields, get_string('auth_fieldlocks_help', 'auth'), false, false);

        echo '</table>';
    }
    
    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        // set to defaults if undefined
        if (!isset ($config->googleclientid)) {
            $config->googleclientid = '';
        }
        if (!isset ($config->googleclientsecret)) {
            $config->googleclientsecret = '';
        }
        if (!isset ($config->googleipinfodbkey)) {
            $config->googleipinfodbkey = '';
        }
        if (!isset ($config->googleuserprefix)) {
            $config->googleuserprefix = 'google_user_';
        }

        // save settings
        set_config('googleclientid', $config->googleclientid, 'auth/googleoauth2');
        set_config('googleclientsecret', $config->googleclientsecret, 'auth/googleoauth2');
        set_config('googleipinfodbkey', $config->googleipinfodbkey, 'auth/googleoauth2');
        set_config('googleuserprefix', $config->googleuserprefix, 'auth/googleoauth2');

        return true;
    }

}
