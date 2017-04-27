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

require_once($CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php');

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
    return array('google', 'facebook', 'battlenet', 'github', 'linkedin', 'messenger', 'microsoft', 'vk', 'dropbox');
}

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
 * The very ugly code to render the html buttons.
 * TODO remove ugly html like center-tag and inline styles, implement a moodle renderer
 * @return string returns the html for buttons and some JavaScript
 */
function auth_googleoauth2_render_buttons() {
    global $CFG;
    $html = '';

    if (!is_enabled_auth('googleoauth2')) {
        return $html;
    }

    // Get previous auth provider.
    $allauthproviders = optional_param('allauthproviders', false, PARAM_BOOL);
    $cookiename = 'MOODLEGOOGLEOAUTH2_'.$CFG->sessioncookie;
    $authprovider = '';
    if (!empty($_COOKIE[$cookiename])) {
        $authprovider = $_COOKIE[$cookiename];
    }

    $html .= "<div>";
    $providerscount = 0;

    // TODO get the list from the provider folder instead to hard code it here.
    $providers = provider_list();

    foreach ($providers as $providername) {

        require_once($CFG->dirroot . '/auth/googleoauth2/classes/provider/'.$providername.'.php');

        // Load the provider plugin.
        $providerclassname = 'provideroauth2' . $providername;
        $provider = new $providerclassname();
        $authurl = $provider->getAuthorizationUrl();
        set_state_token($providername, $provider->state);

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
                <a href="'. $CFG->wwwroot . (!empty($CFG->alternateloginurl) ? $CFG->alternateloginurl : '/login/index.php')
                     . '?allauthproviders=true' .'" onclick="changecss(\\\'signinprovider\\\',\\\'display\\\',\\\'inline-block\\\');">
                    '. get_string('moreproviderlink', 'auth_googleoauth2').'
                </a>
            </div>';
    }

     $html .= "</div>";

    return $html;
}
