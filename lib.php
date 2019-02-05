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
 * This file contains lib functions for the Oauth2 authentication plugin.
 *
 * @package    auth_googleoauth2
 * @copyright  2015 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');


/**
 * Return HTML code for login buttons
 *
 * @return string
 */
function googleoauth2_html_button($authurl, $providerdisplaystyle, $provider) {
        return '<a class="signinprovider" href="' . $authurl . '" style="' . $providerdisplaystyle .'">
                  <div class="social-button ' . $provider->sskstyle . '">' .
                    get_string('signinwithanaccount', 'auth_googleoauth2', $provider->readablename) .
                 '</div>
                </a>';
}

/**
 * Return list of supported provider.
 *
 * @return array
 */
function provider_list() {
    global $CFG;
    $providerInstances = array_diff(scandir($CFG->dirroot.'/auth/googleoauth2/classes/provider'), array('..', '.'));
    foreach ($providerInstances as $providerInstance) {
	$providers[]=substr($providerInstance,0,-4);
    }
    return $providers;
}

/**
 * Manage the redirect in oauth2
 *
 * @return none
 */
function googleoauth2_provider_redirect($providername) {
    global $CFG;

    $code = optional_param('code', '', PARAM_TEXT); // Google can return an error.

    if (empty($code)) {
        throw new moodle_exception($providername . '_failure', 'auth_googleoauth2');
    }

    $state = optional_param('state', null, PARAM_TEXT);
    // Clean the state from a weird #_=_ added to the end by facebook.
    $state = str_replace('#_=_' , '', $state);

    // Ensure that this is no request forgery going on.
    // And that the user sending us this connect request is the user that was supposed to.
    if (empty($state) || ($_SESSION['oauth2state_' . $providername] !== $state)) {
        throw new moodle_exception('invalidstateparam', 'auth_googleoauth2');
    }

    $loginurl = '/login/index.php';
    if (!empty($CFG->alternateloginurl)) {
        $loginurl = $CFG->alternateloginurl;
    }
    $url = new moodle_url($loginurl, array('code' => $code, 'authprovider' => $providername));

    redirect($url);
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
 * Support function for setting the state token in our session
 *
 */
function set_state_token($providername, $providerstate) {
    $_SESSION['oauth2state_' . $providername] = $providerstate;
}

/**
 * For backwards compatibility only: this echoes the html created in auth_googleoauth2_render_buttons
 */
function auth_googleoauth2_display_buttons($echo = true) {
    $html = auth_googleoauth2_render_buttons();
    if ($echo) {
        echo $html;
    }
    return $html;
}

/**
 * The code to render the html buttons.
 * @return string returns the html for buttons and some JavaScript
 */
function auth_googleoauth2_render_buttons() {
    global $CFG;
    $html = '';
    $providerscount = 0;

    if (!is_enabled_auth('googleoauth2')) {
        return $html;
    }

    // Get previous auth provider.
    $allauthproviders = optional_param('allauthproviders', false, PARAM_BOOL);
    $cookiename = 'MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie;
    $authprovider = '';
    if ( $allauthproviders ) {
	// unset cookie if allauthproviders is requested by the user
    	unset ($_COOKIE[$cookiename]);
	// UnSet the cookie used to remember what auth provider was selected.
        setcookie('MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie, $authprovider,
                  time() - 3600, $CFG->sessioncookiepath,
                  $CFG->sessioncookiedomain, $CFG->cookiesecure,
                  $CFG->cookiehttponly);
    }
    if (!empty($_COOKIE[$cookiename])) {
        $authprovider = $_COOKIE[$cookiename];
    }

    $providers = provider_list();
    if ( count ($providers) > 0 ) {
    	$html .= '<div class="providerlinks providerlinks-'.get_config('auth/googleoauth2','providerlinksstyle').'">';
    	$html .= '<p class="providerlinkstext">';
    	$html .= get_string("providerlinkstext","auth_googleoauth2");
    	$html .= '</p>';

        foreach ($providers as $providername) {

            require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/'.$providername.'.php');

            // Load the provider plugin.
            $providerclassname = 'provideroauth2' . $providername;
            $provider = new $providerclassname();
            $authurl = $provider->getAuthorizationUrl();
            set_state_token($providername, $provider->getState());
    
            // Check if we should display the button.
            $providerisenabled = $provider->isenabled();
            $providerscount = $providerisenabled ? $providerscount + 1 : $providerscount;
            $displayprovider = ((empty($authprovider) || $authprovider == $providername || $allauthproviders) && $providerisenabled);
            $providerdisplaystyle = $displayprovider ? 'display:inline-block;padding:10px;' : 'display:none;';

            // The button html code.
            $html .= $provider->html_button($authurl, $providerdisplaystyle);
        }

        if (!$allauthproviders && !empty($authprovider) && $providerscount > 1) {
            $html .= '<br /><br />
               <div class="moreproviderlink">
                    <a href="'. $CFG->httpswwwroot . (!empty($CFG->alternateloginurl) ? $CFG->alternateloginurl : '/login/index.php')
                         . '?allauthproviders=true' .'" onclick="changecss(\\\'signinprovider\\\',\\\'display\\\',\\\'inline-block\\\');">
                        '. get_string('moreproviderlink', 'auth_googleoauth2').'
                    </a>
                </div>';
        }

         $html .= "</div>";
    }

    return $html;
}
