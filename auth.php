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
 * Google authentication plugin.
 */
class auth_plugin_google extends auth_plugin_base {

    /**
     * Constructor.
     */
    function auth_plugin_google() {
        $this->authtype = 'google';
        $this->roleauth = 'auth_google';
        $this->errorlogtag = '[AUTH GOOGLE] ';
        $this->config = get_config('auth/google');
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
        global $USER, $SESSION, $CFG;
        
        $access_token = optional_param('access_token', '', PARAM_TEXT);
        varlog($access_token);
        $expires_in = optional_param('expires_in', '', PARAM_INT);
        varlog($expires_in);
        $token_type = optional_param('token_type', '', PARAM_TEXT);
        varlog($token_type);
        $refresh_token = optional_param('refresh_token', '', PARAM_TEXT);
        varlog($refresh_token);
        
        //get the authorization code
        $authorizationcode = optional_param('code', '', PARAM_TEXT);
        if (!empty($authorizationcode)) {
        
            //if good => 4
            //then request by curl an access token and refresh token
            if (substr($authorizationcode, 0, 2) == '4/') {
                $authorizationtoken = substr($authorizationcode, 2);
                
                
                $clientid = get_config('auth/google', 'googleclientid');
                $clientsecret = get_config('auth/google', 'googleclientsecret');
                
                //with access token request by curl the email address
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

                if (!empty($accesstoken)) {

                    //get the username matching the email                  
                    //We will call https://www.googleapis.com/userinfo/email?alt=json
                    $params = array();
                    $params['access_token'] = $accesstoken;
                    $params['alt'] = 'json';
                    $postreturnvalues = $curl->get('https://www.googleapis.com/userinfo/email', $params);
                    $useremail = json_decode($postreturnvalues)->data->email;

                    //if email not existing in user database then create a new username (userX).


                    //authenticate the user
                  //  $user = authenticate_user_login($username, null);
                    if ($user) {
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
                       // redirect($urltogo);
                    }
                } else {
                    throw new moodle_exception('googleauthenticationerror2');
                }
            } else {
                throw new moodle_exception('googleauthenticationerror1');
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

        echo '<table cellspacing="0" cellpadding="5" border="0">
            <tr>
               <td colspan="3">
                    <h2 class="main">';

        print_string('auth_googlesettings', 'auth_google'); 

        echo '</h2>
               </td>
            </tr>
            <tr>
                <td align="right"><label for="googleclientid">';

        print_string('auth_googleclientid_key', 'auth_google');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input', 
                array('type' => 'text', 'id' => 'googleclientid', 'name' => 'googleclientid', 
                    'class' => 'googleclientid', 'value' => $config->googleclientid));

        if (isset($err["googleclientid"])) {
            echo $OUTPUT->error_text($err["googleclientid"]);
        }

        echo '</td><td>';

        print_string('auth_googleclientid', 'auth_google') ;

        echo '</td></tr>';
        
        echo '<tr>
                <td align="right"><label for="googleclientsecret">';

        print_string('auth_googleclientsecret_key', 'auth_google');

        echo '</label></td><td>';


        echo html_writer::empty_tag('input', 
                array('type' => 'text', 'id' => 'googleclientsecret', 'name' => 'googleclientsecret', 
                    'class' => 'googleclientsecret', 'value' => $config->googleclientsecret));

        if (isset($err["googleclientsecret"])) {
            echo $OUTPUT->error_text($err["googleclientsecret"]);
        }

        echo '</td><td>';

        print_string('auth_googleclientsecret', 'auth_google') ;

        echo '</td></tr>';

        print_auth_lock_options('google', $user_fields, get_string('auth_fieldlocks_help', 'auth'), false, false);

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

        // save settings
        set_config('googleclientid', $config->googleclientid, 'auth/google');
        set_config('googleclientsecret', $config->googleclientsecret, 'auth/google');

        return true;
    }

}
