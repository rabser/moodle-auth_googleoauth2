How to run these behat tests
----------------------------
a) your behat site should be accessible from the web. You can use localtunnel (http://progrium.com/localtunnel/),
   and see how to a setup the behat domain (http://docs.moodle.org/dev/Acceptance_testing#Advanced_usage)
b) register the behat site in the Google console API
c) install this theme extending bootstrap: https://github.com/mouneyrac/moodle-theme_easy/archive/googleoauth2.zip
d) access your site, setup the Oauth2 plugin, then sign-in with Google
   (the behat test doesn't test the Google acceptance screen so you need to valid your site once)
e) edit login_google.feature
f) change the googleclientid and googleclientsecret values by your site values.
g) change the xpath_element for the theme xpath (in my case the theme as a tr[16])
h) change the Email and Passwd values by your gmail credentials.

This is just to get you started with a behat test, and obviously don't use your personal Google account.
For more serious testing, you may want to use a system like travis-ci that seems to allow to encrypt your keys
and a let you run the test with phantomjs. It's just an advice from what I read, I haven't used travis yet.
If you come up with a nice settings, please share it in the tracker issue (https://github.com/mouneyrac/moodle-auth_googleoauth2/issues/20).
