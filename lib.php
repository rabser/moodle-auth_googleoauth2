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
 * This file contains lib functions for the Oauth2 authentication plugin.
 *
 * @package   auth_googleoauth2
 * @copyright 2013 Jerome Mouneyrac {@link http://jerome.mouneyrac.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * oauth_add_to_log is a quick hack to avoid add_to_log debugging
 */
function oauth_add_to_log($courseid, $module, $action, $url='', $info='', $cm=0, $user=0) {
    if (function_exists('get_log_manager')) {
        $manager = get_log_manager();
        $manager->legacy_add_to_log($courseid, $module, $action, $url, $info, $cm, $user);
    } else if (function_exists('add_to_log')) {
        add_to_log($courseid, $module, $action, $url, $info, $cm, $user);
    }
}

/**
 * Get (generate) session state token.
 *
 * @return string the state token.
 */
function auth_googleoauth2_get_state_token() {
    // Create a state token to prevent request forgery.
    // Store it in the session for later validation.
    if (empty($_SESSION['STATETOKEN'])) {
        $state = md5(rand());
        $_SESSION['STATETOKEN'] = $state;
    }
    return $_SESSION['STATETOKEN'];
}

/**
 * For backwards compatibility only: this echoes the html created in auth_googleoauth2_render_buttons
 */
function auth_googleoauth2_display_buttons() {
    echo auth_googleoauth2_render_buttons();
}

/**
 * The very ugly code to render the html buttons.
 * TODO remove ugly html like center-tag and inline styles, implement a moodle renderer
 * @return string: returns the html for buttons and some JavaScript 
 */
function auth_googleoauth2_render_buttons() {
	global $CFG;
	$html ='';
	
    if (!is_enabled_auth('googleoauth2')) {
        return $html;
    }

	$html .= '
    <script language="javascript">
        linkElement = document.createElement("link");
        linkElement.rel = "stylesheet";
        linkElement.href = "' . $CFG->httpswwwroot . '/auth/googleoauth2/csssocialbuttons/css/zocial.css";
        document.head.appendChild(linkElement);
    </script>
    ';
	
	//get previous auth provider
	$allauthproviders = optional_param('allauthproviders', false, PARAM_BOOL);
	$cookiename = 'MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie;
	if (empty($_COOKIE[$cookiename])) {
		$authprovider = '';
	} else {
		$authprovider = $_COOKIE[$cookiename];
	}
	
	$html .= "<center>";
	$html .= "<div style=\"width:'1%'\">";
    $a = new stdClass();
    $a->providername = 'Google';
    $providerscount = 0;
    $providerisenabled = get_config('auth/googleoauth2', 'googleclientid') && get_config('auth/googleoauth2', 'googleclientsecret');
    $providerscount = $providerisenabled?$providerscount+1:$providerscount;
	$displayprovider = ((empty($authprovider) || $authprovider == 'google' || $allauthproviders) && $providerisenabled);
	$providerdisplaystyle = $displayprovider?'display:inline-block;padding:10px;':'display:none;';
	$html .= '<div class="singinprovider" style="' . $providerdisplaystyle .'">
            <a class="zocial googleplus" href="https://accounts.google.com/o/oauth2/auth?client_id='.
	            get_config('auth/googleoauth2', 'googleclientid') .'&redirect_uri='.$CFG->wwwroot .'/auth/googleoauth2/google_redirect.php&state='.auth_googleoauth2_get_state_token().'&scope=profile email&response_type=code">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>';

    $a->providername = 'Facebook';
    $providerisenabled = get_config('auth/googleoauth2', 'facebookclientid');
    $providerscount = $providerisenabled?$providerscount+1:$providerscount;
	$displayprovider = ((empty($authprovider) || $authprovider == 'facebook' || $allauthproviders) && $providerisenabled);
	$providerdisplaystyle = $displayprovider?'display:inline-block;padding:10px;':'display:none;';
	$html .= '<div class="singinprovider" style="'. $providerdisplaystyle .'">
            <a class="zocial facebook" href="https://www.facebook.com/dialog/oauth?client_id='. get_config('auth/googleoauth2', 'facebookclientid') .'&redirect_uri='. $CFG->wwwroot .'/auth/googleoauth2/facebook_redirect.php&state='.auth_googleoauth2_get_state_token().'&scope=email&response_type=code">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>';

    $a->providername = 'Github';
    $providerisenabled = get_config('auth/googleoauth2', 'githubclientid');
    $providerscount = $providerisenabled?$providerscount+1:$providerscount;
	$displayprovider = ((empty($authprovider) || $authprovider == 'github' || $allauthproviders) && $providerisenabled);
	$providerdisplaystyle = $displayprovider?'display:inline-block;padding:10px;':'display:none;';
	$html .= '<div class="singinprovider" style="'. $providerdisplaystyle .'">
            <a class="zocial github" href="https://github.com/login/oauth/authorize?client_id='. get_config('auth/googleoauth2', 'githubclientid') .'&redirect_uri='. $CFG->wwwroot .'/auth/googleoauth2/github_redirect.php&state='.auth_googleoauth2_get_state_token().'&scope=user:email&response_type=code">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>';

    $a->providername = 'Linkedin';
    $providerisenabled = get_config('auth/googleoauth2', 'linkedinclientid');
    $providerscount = $providerisenabled?$providerscount+1:$providerscount;
	$displayprovider = ((empty($authprovider) || $authprovider == 'linkedin' || $allauthproviders) && $providerisenabled);
	$providerdisplaystyle = $displayprovider?'display:inline-block;padding:10px;':'display:none;';
	$html .= '<div class="singinprovider" style="'. $providerdisplaystyle .'">
            <a class="zocial linkedin" href="https://www.linkedin.com/uas/oauth2/authorization?client_id='. get_config('auth/googleoauth2', 'linkedinclientid') .'&redirect_uri='. $CFG->wwwroot .'/auth/googleoauth2/linkedin_redirect.php&state='.auth_googleoauth2_get_state_token().'&scope=r_basicprofile%20r_emailaddress&response_type=code">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>';

    $a->providername = 'Windows Live';
    $providerisenabled = get_config('auth/googleoauth2', 'messengerclientid');
    $providerscount = $providerisenabled?$providerscount+1:$providerscount;
	$displayprovider = ((empty($authprovider) || $authprovider == 'messenger' || $allauthproviders) && $providerisenabled);
	$providerdisplaystyle = $displayprovider?'display:inline-block;padding:10px;':'display:none;';
	$html .= '<div class="singinprovider" style="'. $providerdisplaystyle .'">
            <a class="zocial windows" href="https://oauth.live.com/authorize?client_id='. get_config('auth/googleoauth2', 'messengerclientid') .'&redirect_uri='. $CFG->wwwroot .'/auth/googleoauth2/messenger_redirect.php&state='.auth_googleoauth2_get_state_token().'&scope=wl.basic wl.emails wl.signin&response_type=code">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>
    </div>';

	if (!$allauthproviders and !empty($authprovider) and $providerscount>1) {
		$html .= '<br /><br /> 
           <div class="moreproviderlink">
                <a href="'. $CFG->wwwroot . (!empty($CFG->alternateloginurl) ? $CFG->alternateloginurl : '/login/index.php') . '?allauthproviders=true' .'" onclick="changecss(\'singinprovider\',\'display\',\'inline-block\');">
                    '. get_string('moreproviderlink', 'auth_googleoauth2').'
                </a>
            </div>';
	}

	$html .= "</center>";	
	return $html;
}
