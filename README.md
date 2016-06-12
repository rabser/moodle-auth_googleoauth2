
This plugin adds the "Sign-in with Google / Facebook / Github / Linkedin / Windows Live" buttons on the login page. The first time the user login with a social account, a new Moodle account is created.

### Requirements
PHP 5.5

### Installation:
1. add the plugin into /auth/googleoauth2/
2. Install composer.phar: curl -sS https://getcomposer.org/installer | php
3. Install the 'vendor' folder: php composer.phar install
4. apply the changes listed in VENDOR CHANGES.md
5. run the Moodle upgrade
6. in the Moodle administration, enable the plugin (Admin block > Plugins > Authentication)
7. in the plugin settings, follow the displayed instructions.
or just install the plugin from [Moodle.org repository plugin page](https://moodle.org/plugins/view/auth_googleoauth2)

If you have any issues you can follow the Git chapter of my free mini-course [how to install a Moodle plugin](http://bepaw-open-source-school.teachable.com/courses/how-to-install-a-plugin). It is using the Oauth2 plugin as example.

### Implement your own provider (for devs)
1. add your third party provider for Oauth2 client as explain in https://github.com/thephpleague/oauth2-client
2. create /classes/provider/newprovidername.php and newprovidername_redirect.php. Then add the lang strings in /lang/en/auth_googleoauth2.php
and add the provider name to lib.php:provider_list (if you have time you can change the function logic to automatically load the classes from the provider folder
and then send me a pull request, thanks :))

### Use the table access token (for devs)
In order to store the user access tokens, you must set the config with:
set_config('saveaccesstoken', 1, 'auth/googleoauth2');

Then you can use them in your own plugin. The Oauth2 plugin also trigger an event on login.
You can retrieve the access token from it too.

### Composer (for devs)
The plugin does not include the 'vendor' folder as explained by [composer best practice](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).
If you need to know more about composer, you can read [Composer Namespaces in 5 Minutes](https://jtreminio.com/2012/10/composer-namespaces-in-5-minutes/)

### Continueous integration, tracker...
[![Build Status](https://travis-ci.org/mouneyrac/moodle-auth_googleoauth2.svg?branch=master)](https://travis-ci.org/mouneyrac/moodle-auth_googleoauth2)
[![Stories in Ready](https://badge.waffle.io/mouneyrac/moodle-auth_googleoauth2.png?label=ready&title=Ready)](https://waffle.io/mouneyrac/moodle-auth_googleoauth2)
[![Codacy Badge](https://www.codacy.com/project/badge/84928dc4c553414786735ba745e57c93)](https://www.codacy.com/app/jerome/moodle-auth_googleoauth2)
[![bountysource](https://api.bountysource.com/badge/team?team_id=49212&style=raised)](https://www.bountysource.com/teams/oauth2-authentication-plugin-for-moodle/backers)

[![Throughput Graph](https://graphs.waffle.io/mouneyrac/moodle-auth_googleoauth2/throughput.svg)](https://waffle.io/mouneyrac/moodle-auth_googleoauth2/metrics)

### Credits
* [Contributors](https://github.com/mouneyrac/auth_googleoauth2/graphs/contributors)
* [The PHP League oauth2 client](https://github.com/thephpleague/oauth2-client)
* [Pixelfear dropbox support](https://github.com/pixelfear/oauth2-dropbox)
* [Depotwarehouse battle.net support](https://github.com/tpavlek/oauth2-bnet)
* [Guzzle](http://docs.guzzlephp.org/en/latest/)
* [illuminate contracts](https://github.com/illuminate/contracts)
* [Symfony EventDispatcher](http://symfony.com/)

### +1 the plugin
To like the plugin, go to the [Moodle.org repository plugin page](https://moodle.org/plugins/view/auth_googleoauth2), login and click on 'Add to my Favorites'. Find other ways to contribute on the [github plugin page](http://mouneyrac.github.io/moodle-auth_googleoauth2/).

