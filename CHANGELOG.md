Changelog
==========

Release 2.1 (wip)
-----------------
* add licence file
* Remove SocialShareKit [LICENCE ISSUE]
* Add help text about dropbox and battle.net only working on https site
* Update admin detail to setup github, windows, linkedin
* #135 - Translation of the exceptions
* #131 - Links to providers are unneccessarily output into HTML
* #129 - Use $PAGE->requires->css to load the css

Release 2.0
-----------
* lots of code changes
* new button css
* buttons now displayed under the login form inputs for all themes (Thanks to the Oauth2 Elcentra plugin for Moodle)

Release 1.5.1 (Build: 2015051502)
---------------------------------
* quite some code rewrite to use the popular PHPLeague Oauth2 client. It is now easy to add a new provider if they have been created for this client.
* add support for Dropbox.com

Release 1.5 (Build: 2015051500)
------------------------------
* add support for vk.com
* add support for Battle.net

Release 1.5 (Build: 2014120000)
-------------------------------
* compatible behat test with 2.7+
* Do not display 'See all providers' link if there is only one provider to select.
* add yml shippable file (compatible with travis). You need to change the secure variable with your own GITHUBTOKEN variable. See the docs of shippable or travis for secure variable.

v1.5 (18/7/14)
--------------
* code updated to support the new Google Oauth2 API as the one used by the plugin is been deprecated in two months.  
See: https://developers.google.com/+/api/auth-migration#timetable

*ACTION REQUIRED* you need to enable the Google+ API in the Google developer console:  
Project > API & AUTH > APIs

v1.5
----
* add user_loggedin event - it uses Events 2 introduced in Moodle 2.6
* For Moodle 2.5 and below use the STABLE_25 branch.

v1.4
----
* Add Linkedin authentication.
* Change the way Github email is retrieved.
* The code generating the buttons is now responsive.

v1.3
----
* Add Github
