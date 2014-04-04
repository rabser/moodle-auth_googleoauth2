This plugin adds the "Sign-in with Google / Facebook / Github / Linkedin / Windows Live" buttons on the login page. The first time the user login with a social account, a new Moodle account is created. 

### Installation:

1. add the plugin into /auth/googleoauth2/
2. in the Moodle administration, enable the plugin (Admin block > Plugins > Authentication)
3. in the plugin settings, follow the displayed instructions.
4. Add the following code to your login page :

``<?php 
require_once($CFG->dirroot . '/auth/googleoauth2/lib.php'); 
auth_googleoauth2_display_buttons(); 
?>``

Most of you will need to copy the code at the bottom of login/index_form.html. Some custom themes have their own login page. In this case you should find the login layout page indicated in the theme config.php file. Often the file location is something like /theme/YOURTHEME/layout/login.php.

### Support for business and organization
You can contact me at jerome@mouneyrac.com.

Credits
-------
* [Contributors](https://github.com/mouneyrac/auth_googleoauth2/graphs/contributors)
* [CSS social buttons](http://zocial.smcllns.com/)
