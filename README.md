This plugin adds the "Sign-in with Google / Facebook / Github / Linkedin / Windows Live" buttons on the login page. The first time the user login with a social account, a new Moodle account is created. 

### Installation:
1. add the plugin into /auth/googleoauth2/
2. in the Moodle administration, enable the plugin (Admin block > Plugins > Authentication)
3. in the plugin settings, follow the displayed instructions.

### Implement your own provider (for devs)
1. add your third party provider for Oauth2 client as explain in https://github.com/thephpleague/oauth2-client
2. create /classes/provider/newprovidername.php and newprovidername_redirect.php. Then add the lang strings in /lang/en/auth_googleoauth2.php 
and add the provider name to lib.php:provider_list (if you have time you can change the function logic to automatically load the classes from the provider folder 
and then send me a pull request, thanks :)) 

### Use the table access token (for devs)
In order to use to store the access token you must set the config with:
set_config('saveaccesstoken', 1, 'auth/googleoauth2');

### Continueous integration
[![Build Status](https://api.shippable.com/projects/546da22ad46935d5fbbe1761/badge?branchName=master)](https://app.shippable.com/projects/546da22ad46935d5fbbe1761/builds/latest)

### Credits
* [Contributors](https://github.com/mouneyrac/auth_googleoauth2/graphs/contributors)
* [CSS social buttons](http://zocial.smcllns.com/)
