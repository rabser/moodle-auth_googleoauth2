Changelog
==========

Release 2.2
-----------
* #177 Create new button similar to socialsharekit but open source
* #176 Missing closing div in auth_googleoauth2_render_buttons()
* #173 The label in the log in buttons should be "Log in" instead of "LOGIN"
* #172 I could not connect to facebook
* #171 Got problem after installing plug-in in moodle2.9.1
* #169 Create some statistics
* #168 test upgrade from the version before phpleague to the last phpleague version with Google+ (can student still connect)
* #167 User not logged in after authenticating...
* #164 Buttons are not showing (except for midori browser) ¿?
* #162 create link to contributors, stargazers... on the jekyll site
* #161 fix codacy issues to get A rating
* #160 update Moodle.org plugin screenshots
* #159 mention how to contribute to the plugin in the plugin settings page
* #154 switch from shippable to travis
* #152 Manual display of button showing link but not image
* #151 Merge behat fix pull request
* #149 Correction de test behat.
* #147 Failed Login 'auth_googleoauth2\event\user_loggedin'

Release 2.1
-----------
* #143 add licence file
* #144 Remove SocialShareKit [LICENCE ISSUE]
* #124 Better looking buttons
* #125 Add help text about dropbox and battle.net only working on https site
* #135 Translation of the exceptions
* #129 Use $PAGE->requires->css to load the css
* #146 better error message when Google+ API is disabled

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
