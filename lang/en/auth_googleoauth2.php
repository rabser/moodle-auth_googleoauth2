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
 * Strings for language 'en'
 *
 * @package    auth
 * @subpackage googleoauth2
 * @author    Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Oauth2';
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
<br/>Valid OAuth redirect URIs: {$a->callbackurl}.';
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
Dashboard > Google Identity and Access Management (IAM) API > Enable > Credentials > Create new Oauth Client ID > Configure Product name in Consent Screen > Save > Choose \'Web application\' Credentials Type
<br/>
Authorized Javascript origins: {$a->jsorigins}
<br/>
Authorized Redirect URI: {$a->redirecturls}
<br/>';
$string['auth_googleclientid_key'] = 'Google Client ID';
$string['auth_googleclientsecret'] = '';
$string['auth_googleclientsecret_key'] = 'Google Client secret';
$string['auth_googleipinfodbkey'] = 'IPinfoDB is a service that let you find out what is the country and city of the visitor.
This setting is optional. You can subscribe to <a href="http://www.ipinfodb.com/register.php">IPinfoDB</a> to get a free key.<br/>
Website: {$a->website}';
$string['auth_googleipinfodbkey_key'] = 'IPinfoDB Key';
$string['auth_userprefixdesc'] = 'The created user\'s username will start with this prefix. On a basic Moodle site you don\'t need to change it.';
$string['auth_userprefix'] = 'Username prefix';
$string['auth_googleoauth2description'] = 'Allow a user to connect to the site with an external authentication provider: Google/Facebook/Windows Live.
The first time the user connects with an authentication provider, a new account is created.
Prevent account creation (authpreventaccountcreation admin setting) when authenticating <b>must</b> be unset.';
$string['auth_linkedinclientid'] = 'Your API/Secret keys can be generated in your <a href="https://www.linkedin.com/secure/developer" target="_blank">Linkedin register application page</a>:
<br/>Website URL: {$a->siteurl}
<br/>OAuth 2.0 Accept Redirect URL: {$a->callbackurl}';
$string['auth_linkedinclientid_key'] = 'Linkedin API Key';
$string['auth_linkedinclientsecret'] = '';
$string['auth_linkedinclientsecret_key'] = 'Linkedin Secret key';
$string['auth_microsoftclientid'] = 'Your Client ID/Secret can be generated at <a href="https://apps.dev.microsoft.com/" target="_blank">Microsoft Application Registration Portal</a>:
<br />Redirect URI: {$a->callbackurl}';
$string['auth_microsoftclientid_key'] = 'Microsoft v2 Application ID';
$string['auth_microsoftclientsecret'] = '';
$string['auth_microsoftclientsecret_key'] = 'Microsoft v2 Application secret';
$string['microsoft_failure'] = 'Did not receive an authorization code from the Microsoft servers.';
$string['auth_googlesettings'] = 'Providers Settings';
$string['couldnotauthenticate'] = 'The authentication failed - Please try to sign-in again.';
$string['couldnotgetgoogleaccesstoken'] = 'The authentication provider sent us a communication error. Please try to sign-in again.';
$string['couldnotauthenticateuserlogin'] = 'Authentication method error: an account user with the same email address already exists !<br/>
Please choose another authentication method or contact the site administrator.<br/>
<br/>
<a href="{$a->loginpage}">Try again</a>.<br/>
<a href="{$a->forgotpass}">Forgot your password</a>?';
$string['displaybuttons'] = 'Display buttons on login page ?';
$string['displaybuttonshelp'] = 'Display the provider logo buttons on the top of the login page.
If you want to position the buttons yourself in your login page, you can keep this option disabled and add the following code: {$a}';
$string['emailaddressmustbeverified'] = 'Your email address is not verified by the authentication method you selected. You likely have forgotten to click on a "verify email address"
link that Google or Facebook should have sent you during your subscription to their service.';
$string['auth_sign-in_with'] = 'Sign-in with {$a->providername}';
$string['faileduserdetails'] = 'The site succeed to connect to the selected provider but failed to retrieve your user details.';
$string['moreproviderlink'] = 'Sign-in with another service.';
$string['signinwithanaccount'] = 'Log in with {$a}';
$string['noaccountyet'] = 'You do not have permission to use the site yet. Please contact your administrator and ask them to activate your account.';
$string['othermoodle'] = 'Other Moodle auth';
$string['stattitle'] = 'Login statistics for this plugin';
$string['stattitlecaption'] = 'Statistics for the last {$a->periodindays} days.';
$string['supportmaintenance'] = 'To support the maintenance of this plugin, login to the <a target="_blank" href="https://moodle.org/plugins/view/auth_googleoauth2">Moodle.org plugin page</a> and click \'Add to my favourites\'. Thanks!';
$string['unknownfirstname'] = 'Unknown Firstname';
$string['unknownlastname'] = 'Unknown Lastname';
$string['auth_vkontakteclientid'] = 'Your app ID/Secret are generated in the <a href="https://vk.com/apps?act=manage" target="_blank">VK My Applications console</a>.
Enter the following settings when creating an application:
<br/>Site address: {$a->siteurl}
<br/>Base Domain: {$a->sitedomain}
<br/>Authorized Redirect URI: {$a->callbackurl}';
$string['auth_vkontakteclientid_key'] = 'VK.com App ID';
$string['auth_vkontakteclientsecret'] = '';
$string['auth_vkontakteclientsecret_key'] = 'VK.com Secure Key';

$string['google'] = 'Google';
$string['facebook'] = 'Facebook';
$string['linkedin'] = 'LinkedIn';
$string['microsoft'] = 'Microsoft Live';
$string['dropbox'] = 'Dropbox';
$string['github'] = 'GitHub';
$string['vkontakte'] = 'VK.com';
$string['othersettings'] = 'Other Settings';
$string['providerlinkstext'] = 'Other Log in providers:';
$string['providerlinksstyle'] = 'Provider Links Box style:';
$string['providerlinksstylehelp'] = 'With this option you can choose between a vertical or horizontal visualization style for the provider list of login links. A specific theme can anyway limit this setting by embedding the login form into a strict width size.';
$string['horizontal'] = 'Horizontal disposition';
$string['vertical'] = 'Vertical disposition';

$string['couldnotgetuseremail'] = 'The Social Network do not provide us an <b>email</b>. Moodle requires a valid email to login correctly: please check you user preferences in the social network and enable your email visibility.';
$string['saveaccesstoken'] = 'Save the user access Token?';
$string['saveaccesstokenhelp'] = 'Set to save the user access token to the plugin internal table. Please check if the API policy for the enabled oauth2 providers consent saving the tokens locally (Most of them do not consent...).';

$string['cantcreatenewuser'] = 'A user does not exists and automatic user creation is forbidden.';
$string['emailnotallowed'] = 'Your email is not allowed to login into this site.';
$string['donotcreatenewuser'] = 'Forbid automatic user creation.';
$string['donotcreatenewuserhelp'] = 'With this option enabled, even if the "Prevent account creation" is enabled, this plugin do not create automatically a new user at logon (normally this option must left unchecked).';
$string['authdomains'] = 'Allowed email domains for oauth2 providers';
$string['authdomainshelp'] = 'With this option you can restrict the email domain allowed to enter this site with the oauth2 providers.<br /> This is a list with comma delimited elements (e.g. example.com, mysite.net). If you want to enable also sub-domains, specify them preceding the domain with a dot (e.g. .example.com matches my.example.com or new.example.com).<br />Left empty to admin every email domain.';
