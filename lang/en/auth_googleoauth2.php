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
 * Strings for component 'auth_google', language 'en'
 *
 * @package   auth_google
 * @author Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Oauth2';
$string['auth_battlenetclientid'] = 'Your Client ID/Secret can be generated in the <a href="https://dev.battle.net/apps/mykeys" target="_blank">Battle.net API site</a>.
Enter the following settings when creating an application (note that Battle.net only supports https url, so your Moodle site must support https):
<br/>Web site: {$a->siteurl}
<br/>Register callback url: {$a->callbackurl} [it MUST BE a HTTPS url otherwise Battle.net will refuse  to log you in]
<br/>Franchises: Starcraft II';
$string['auth_battlenetclientid_key'] = 'Battle.net key';
$string['auth_battlenetclientsecret'] = '';
$string['auth_battlenetclientsecret_key'] = 'Battle.net secret';
$string['auth_dropboxclientid'] = 'Your app Key/Secret are generated in the <a href="https://www.dropbox.com/developers/apps" target="_blank">Dropbox app console</a>.
Enter the following settings when creating an application (note that Dropbox only supports https url, so your Moodle site must support https):
<br/>App website: {$a->siteurl}
<br/>Redirect URIs: {$a->callbackurl}';
$string['auth_dropboxclientid_key'] = 'Dropbox App key';
$string['auth_dropboxclientsecret'] = '';
$string['auth_dropboxclientsecret_key'] = 'Dropbox App secret';
$string['auth_facebookclientid'] = 'Your App ID/Secret can be generated in your <a href="https://developers.facebook.com/apps/" target="_blank">Facebook developer page</a>:
<br/>Add a new app > Website > Enter your site name as app name > Create new facebook app ID > Enter the Site URL - no need to enter Mobile URL >
On the confirmation page, look for the "Skip to Developer Dashboard" link > on the app dashboard you should find the id/secret > Settings > Advanced > enter the Valid OAuth redirect URIs
<br/>Site URL: {$a->siteurl}
<br/>App domains: {$a->sitedomain}
<br/>Valid OAuth redirect URIs: {$a->callbackurl}
<br/><strong>WARNING: Facebook recently changed the API. It is not working for newly created API key. For example we know it is broken from Facebook API 2.8 
and it is working up to Facebook API 2.2. We didn\'t test Facebook API 2.3, 2.4, 2.5, 2.6 and 2.7. To summarize if you don\'t have already an old Facebook API key,
then it is guarantee that Facebook won\'t work in this login. Please look at plugin alternatives or wait for the next plugin big update (not planned yet).</strong>';
$string['auth_facebookclientid_key'] = 'Facebook App ID';
$string['auth_facebookclientsecret'] = '';
$string['auth_facebookclientsecret_key'] = 'Facebook App secret';
$string['auth_githubclientid'] = 'Your client ID/Secret can be generated in your <a href="https://github.com/settings/applications/new" target="_blank">Github register application page</a>:
<br/>Homepage URL: {$a->siteurl}
<br/>Authorization callback URL: {$a->callbackurl}';
$string['auth_githubclientid_key'] = 'Github client ID';
$string['auth_githubclientsecret'] = '';
$string['auth_githubclientsecret_key'] = 'Github client secret';
$string['auth_googleclientid'] = 'Your client ID/Secret can be generated in the <a href="https://code.google.com/apis/console" target="_blank">Google console API</a>:
<br/>
Project > APIS & AUTH > Credentials > Create new Client ID > Web application
<br/>
Authorized Javascript origins: {$a->jsorigins}
<br/>
Authorized Redirect URI: {$a->redirecturls}
<br/>
You also need to <strong>enable the "Google+ API"</strong> in Project > APIS & AUTH > APIs';
$string['auth_googleclientid_key'] = 'Google Client ID';
$string['auth_googleclientsecret'] = '';
$string['auth_googleclientsecret_key'] = 'Google Client secret';
$string['auth_googleipinfodbkey'] = 'IPinfoDB is a service that let you find out what is the country and city of the visitor.
This setting is optional. You can subscribe to <a href="http://www.ipinfodb.com/register.php">IPinfoDB</a> to get a free key.<br/>
Website: {$a->website}';
$string['auth_googleipinfodbkey_key'] = 'IPinfoDB Key';
$string['auth_googleuserprefix'] = 'The created user\'s username will start with this prefix. On a basic Moodle site you don\'t need to change it.';
$string['auth_googleuserprefix_key'] = 'Username prefix';
$string['auth_googleoauth2description'] = 'Allow a user to connect to the site with an external authentication provider: Google/Facebook/Windows Live.
The first time the user connect with an authentication provider, a new account is created.
Prevent account creation (authpreventaccountcreation admin setting) when authenticating <b>must</b> be unset.';
$string['auth_linkedinclientid'] = 'Your API/Secret keys can be generated in your <a href="https://www.linkedin.com/secure/developer" target="_blank">Linkedin register application page</a>:
<br/>Website URL: {$a->siteurl}
<br/>OAuth 2.0 Accept Redirect URL: {$a->callbackurl}';
$string['auth_linkedinclientid_key'] = 'Linkedin API Key';
$string['auth_linkedinclientsecret'] = '';
$string['auth_linkedinclientsecret_key'] = 'Linkedin Secret key';
$string['auth_vkclientid_key'] = 'VK app id';
$string['auth_vkclientsecret_key'] = 'VK app secret';
$string['auth_vkclientid'] = 'Your app id and secret keys can be generated in <a href="https://vk.com/editapp?act=create" target="_blank">VK developer page</a>.<br/>
Base domain: {$a->siteurl} (without http://)<br/>
Site address: {$a->callbackurl}';
$string['auth_vkclientsecret'] = '';
$string['auth_messengerclientid'] = 'Your Client ID/Secret can be generated in your <a href="https://account.live.com/developers/applications" target="_blank">Windows Live apps page</a>:
<br/>Redirect domain: {$a->domain}';
$string['auth_messengerclientid_key'] = 'Messenger Client ID';
$string['auth_messengerclientsecret'] = '';
$string['auth_messengerclientsecret_key'] = 'Messenger Client secret';
$string['auth_microsoftclientid'] = 'Your Client ID/Secret can be generated at <a href="https://apps.dev.microsoft.com/" target="_blank">Microsoft Application Registration Portal</a>:
<br />Redirect URI: {$a->callbackurl}';
$string['auth_microsoftclientid_key'] = 'Microsoft v2 Application ID';
$string['auth_microsoftclientsecret'] = '';
$string['auth_microsoftclientsecret_key'] = 'Microsoft v2 Application secret';
$string['microsoft_failure'] = 'Did not receive an authorization code from the Microsoft servers.';
$string['auth_googlesettings'] = 'Settings';
$string['couldnotauthenticate'] = 'The authentication failed - Please try to sign-in again.';
$string['couldnotgetgoogleaccesstoken'] = 'The authentication provider sent us a communication error. Please try to sign-in again.';
$string['couldnotauthenticateuserlogin'] = 'Authentication method error.<br/>
Please try to login again with your username and password.<br/>
<br/>
<a href="{$a->loginpage}">Try again</a>.<br/>
<a href="{$a->forgotpass}">Forgot your password</a>?';
$string['oauth2displaybuttons'] = 'Display buttons on login page';
$string['oauth2displaybuttonshelp'] = 'Display the Google/Facebook/... logo buttons on the top of the login page.
If you want to position the buttons yourself in your login page, you can keep this option disabled and add the following code: {$a}';
$string['emailaddressmustbeverified'] = 'Your email address is not verified by the authentication method you selected. You likely have forgotten to click on a "verify email address"
link that Google or Facebook should have sent you during your subscribtion to their service.';
$string['auth_sign-in_with'] = 'Sign-in with {$a->providername}';
$string['faileduserdetails'] = 'The site succeed to connect to the selected provider but failed to retrieve your user details. If you are using Google, check that your site administrator has enabled the Google+ API in the Google developer console. It is the most common reason for this error.';
$string['moreproviderlink'] = 'Sign-in with another service.';
$string['signinwithanaccount'] = 'Log in with {$a}';
$string['noaccountyet'] = 'You do not have permission to use the site yet. Please contact your administrator and ask them to activate your account.';
$string['othermoodle'] = 'Other Moodle auth';
$string['stattitle'] = 'Login statistics for the last {$a->periodindays} days (starting from the plugin installation/upgrade time)';
$string['supportmaintenance'] = 'To support the maintenance of this plugin, login to the <a target="_blank" href="https://moodle.org/plugins/view/auth_googleoauth2">Moodle.org plugin page</a> and click \'Add to my favourites\'. Thanks!';
$string['unknownfirstname'] = 'Unknown Firstname';
$string['unknownlastname'] = 'Unknown Lastname';

