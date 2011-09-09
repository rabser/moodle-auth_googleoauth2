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

$string['pluginname'] = 'Google Oauth2';
$string['auth_facebookclientid'] = 'Your App ID - it can be found at https://developers.facebook.com/apps/.';
$string['auth_facebookclientid_key'] = 'App ID';
$string['auth_facebookclientsecret'] = 'Your App secret - it can be found at https://developers.facebook.com/apps/.';
$string['auth_facebookclientsecret_key'] = 'App secret';
$string['auth_googleclientid'] = 'Your client ID - it can be found in the Google console API.';
$string['auth_googleclientid_key'] = 'Client ID';
$string['auth_googleclientsecret'] = 'Your client secret - it can be found in the Google console API.';
$string['auth_googleclientsecret_key'] = 'Client secret';
$string['auth_googleipinfodbkey'] = 'IPinfoDB is a service that let you find out what is the country and city of the visitor. You must subscribe to the service to get a Key: http://www.ipinfodb.com/register.php';
$string['auth_googleipinfodbkey_key'] = 'IPinfoDB Key';
$string['auth_googleuserprefix'] = 'This is the username prefix that is used for every new user authenticated by Google Oauth2. Change it if it conflicts with anything else in your Moodle. On a basic Moodle site you don\'t need to change it.';
$string['auth_googleuserprefix_key'] = 'Username prefix';
$string['auth_googleoauth2description'] = 'Simple and straight forward Oauth2 Google authentication. 
    If the user email doesn\'t exists, then the authentiation plugin creates a new user with username "User_XXX", XXX being incremented every time.';
$string['auth_googlesettings'] = 'Settings';
$string['couldnotgetgoogleaccesstoken'] = 'Google sent us a communication error. Please try to sign-in again.';
$string['emailaddressmustbeverified'] = 'Your email address is not verified by the authentication method you selected. You likely have forgotten to click on a "verify email address" link that Google or Facebook should have sent you during your subscribtion to their service.';
$string['signinwithanaccount'] = 'Sign in with:';