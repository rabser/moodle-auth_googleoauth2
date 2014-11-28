<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_google', language 'en'
 *
 * @package   auth_google
 * @author Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Oauth2';
$string['auth_facebookclientid'] = 'Your App ID/Secret can be generated in your <a href="https://developers.facebook.com/apps/">Facebook developer page</a>:
<br/>Add a new app > Website > Enter your site name as app name > Create new facebook app ID > Enter the Site URL - no need to enter Mobile URL > On the confirmation page, look for the "Skip to Developer Dashboard" link > on the app dashboard you should find the id/secret > Settings > Advanced > enter the Valid OAuth redirect URIs
<br/>Site URL: {$a->siteurl}
<br/>Site domain: {$a->sitedomain}
<br/>Valid OAuth redirect URIs: {$a->callbackurl}';
$string['auth_facebookclientid_key'] = 'Facebook App ID';
$string['auth_facebookclientsecret'] = 'See above.';
$string['auth_facebookclientsecret_key'] = 'Facebook App secret';
$string['auth_githubclientid'] = 'Your client ID/Secret can be generated in your <a href="https://github.com/settings/applications/new">Github register application page</a>:
<br/>Homepage URL: {$a->siteurl}
<br/>Authorization callback URL: {$a->callbackurl}';
$string['auth_githubclientid_key'] = 'Github client ID';
$string['auth_githubclientsecret'] = 'See above.';
$string['auth_githubclientsecret_key'] = 'Github client secret';
$string['auth_googleclientid'] = 'Your client ID/Secret can be generated in the <a href="https://code.google.com/apis/console">Google console API</a>:
<br/>
Project > APIS & AUTH > Credentials > Create new Client ID > Web application
<br/>
Authorized Javascript origins: {$a->jsorigins}
<br/>
Authorized Redirect URI: {$a->redirecturls}
<br/>
You also need to <strong>enable the "Google+ API"</strong> in Project > APIS & AUTH > APIs';
$string['auth_googleclientid_key'] = 'Google Client ID';
$string['auth_googleclientsecret'] = 'See above.';
$string['auth_googleclientsecret_key'] = 'Google Client secret';
$string['auth_googleipinfodbkey'] = 'IPinfoDB is a service that let you find out what is the country and city of the visitor. This setting is optional. You can subscribe to <a href="http://www.ipinfodb.com/register.php">IPinfoDB</a> to get a free key.<br/>
Website: {$a->website}';
$string['auth_googleipinfodbkey_key'] = 'IPinfoDB Key';
$string['auth_googleuserprefix'] = 'The created user\'s username will start with this prefix. On a basic Moodle site you don\'t need to change it.';
$string['auth_googleuserprefix_key'] = 'Username prefix';
$string['auth_googleoauth2description'] = 'Allow a user to connect to the site with an external authentication provider: Google/Facebook/Windows Live. The first time the user connect with an authentication provider, a new account is created. <a href="'.$CFG->wwwroot.'/admin/search.php?query=authpreventaccountcreation">Prevent account creation when authenticating</a> <b>must</b> be unset.';
$string['auth_linkedinclientid'] = 'Your API/Secret keys can be generated in your <a href="https://www.linkedin.com/secure/developer">Linkedin register application page</a>:
<br/>Website URL: {$a->siteurl}
<br/>OAuth 1.0 Accept Redirect URL: {$a->callbackurl}';
$string['auth_linkedinclientid_key'] = 'Linkedin API Key';
$string['auth_linkedinclientsecret'] = 'See above.';
$string['auth_linkedinclientsecret_key'] = 'Linkedin Secret key';
$string['auth_messengerclientid'] = 'Your Client ID/Secret can be generated in your <a href="https://account.live.com/developers/applications">Windows Live apps page</a>:
<br/>Redirect domain: {$a->domain}';
$string['auth_messengerclientid_key'] = 'Messenger Client ID';
$string['auth_messengerclientsecret'] = 'See above.';
$string['auth_messengerclientsecret_key'] = 'Messenger Client secret';

$string['auth_googlesettings'] = 'Settings';
$string['couldnotauthenticate'] = 'The authentication failed - Please try to sign-in again.';
$string['couldnotgetgoogleaccesstoken'] = 'The authentication provider sent us a communication error. Please try to sign-in again.';
$string['couldnotauthenticateuserlogin'] = 'Authentication method error.<br/>
Please try to login again with your username and password.<br/>
<br/>
<a href="{$a->loginpage}">Try again</a>.<br/>
<a href="{$a->forgotpass}">Forgot your password</a>?';
$string['oauth2displaybuttons'] = 'Display buttons on login page';
$string['oauth2displaybuttonshelp'] = 'Display the Google/Facebook/... logo buttons on the top of the login page. If you want to position the buttons yourself in your login page, you can keep this option disabled and add the following code:
{$a}';
$string['emailaddressmustbeverified'] = 'Your email address is not verified by the authentication method you selected. You likely have forgotten to click on a "verify email address" link that Google or Facebook should have sent you during your subscribtion to their service.';
$string['auth_sign-in_with'] = 'Sign-in with {$a->providername}';
$string['moreproviderlink'] = 'Sign-in with another service.';
$string['signinwithanaccount'] = 'Log in with:';
$string['noaccountyet'] = 'You do not have permission to use the site yet. Please contact your administrator and ask them to activate your account.';
$string['unknownfirstname'] = 'Unknown Firstname';
$string['unknownlastname'] = 'Unknown Lastname';