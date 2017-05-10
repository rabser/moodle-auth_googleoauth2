**No new versions are planned starting from Moodle 3.3:** starting from Moodle 3.3, Oauth2 should be implemented in Moodle core. It will require you to upgrade to Moodle 3.3 and switch to the Oauth2 core authentication (and probably do some migration), but it is a great news as authentication is a really sensible matter and having Oauth2 plugins in core will guarantee their maintenance. So starting from Moodle 3.3 I recommend to use the new Oauth2 core plugins ((https://docs.moodle.org/dev/Better_Office_Integrations_3.3#Core_API_for_managing_Authorized_OAuth_Applications) ). Missing core providers will likely be implemented by someone and publish in Moodle.org plugin repository. There will probably be a new Oauth2 categories in the Moodle plugin repository. wait and see...

-

This plugin adds the "Sign-in with Google / Github / Linkedin / Windows Live" buttons on the login page. The first time the user login with a social account, a new Moodle account is created.

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

### Continuous integration
[![Build Status](https://travis-ci.org/rabser/moodle-auth_googleoauth2.svg?branch=master)](https://travis-ci.org/rabser/moodle-auth_googleoauth2)
[![Codacy Badge](https://www.codacy.com/project/badge/84928dc4c553414786735ba745e57c93)](https://www.codacy.com/app/jerome/moodle-auth_googleoauth2)

### Credits
* [Contributors](https://github.com/mouneyrac/auth_googleoauth2/graphs/contributors)
* [The PHP League oauth2 client](https://github.com/thephpleague/oauth2-client)
* [Pixelfear dropbox support](https://github.com/pixelfear/oauth2-dropbox)
* [Depotwarehouse battle.net support](https://github.com/tpavlek/oauth2-bnet)
* [Guzzle](http://docs.guzzlephp.org/en/latest/)
* [illuminate contracts](https://github.com/illuminate/contracts)
* [Symfony EventDispatcher](http://symfony.com/)


