
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

### Composer (for devs)
I deliver the entire vendor content in the repository (so don't run composer). It makes it for me easy to download the zip file from Github and then to upload it straight away in Moodle.org. 
Moodle.org is not able to create a package from Github (with vendor libs) yet.

### Continueous integration, tracker...
[![Build Status](https://api.shippable.com/projects/546da22ad46935d5fbbe1761/badge?branchName=master)](https://app.shippable.com/projects/546da22ad46935d5fbbe1761/builds/latest)
[![Stories in Ready](https://badge.waffle.io/mouneyrac/moodle-auth_googleoauth2.png?label=ready&title=Ready)](https://waffle.io/mouneyrac/moodle-auth_googleoauth2)
[![Codacy Badge](https://www.codacy.com/project/badge/84928dc4c553414786735ba745e57c93)](https://www.codacy.com/app/jerome/moodle-auth_googleoauth2)
[![bountysource](https://api.bountysource.com/badge/team?team_id=49212&style=raised)](https://www.bountysource.com/teams/oauth2-authentication-plugin-for-moodle/backers)

### Credits
* [Contributors](https://github.com/mouneyrac/auth_googleoauth2/graphs/contributors)
* [Social Share Kit](http://socialsharekit.com/)
* [The PHP League oauth2 client](https://github.com/thephpleague/oauth2-client)
* [Pixelfear dropbox support](https://github.com/pixelfear/oauth2-dropbox)
* [Depotwarehouse battle.net support](https://github.com/tpavlek/oauth2-bnet)
* [Guzzle](http://docs.guzzlephp.org/en/latest/)
* [illuminate contracts](https://github.com/illuminate/contracts)
* [Symfony EventDispatcher](http://symfony.com/)

### +1 the plugin
To like the plugin, go to the [Moodle.org repositoroty plugin page](https://moodle.org/plugins/view/auth_googleoauth2), login and click on 'Add to my Favorites'. Find other ways to contribute on the [github plugin page](http://mouneyrac.github.io/moodle-auth_googleoauth2/).

