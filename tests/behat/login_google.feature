@auth @auth_googleoauth2
Feature: Sign in with a Google account
  In order to login to the site
  As a user
  I sign in with my Google account
 
  @javascript
  Scenario: Sign in with a Google account
    When I follow "Log in"
    When I set the field "username" to "admin"
    And I set the field "password" to "admin"
    And I press "loginbtn"
    When I expand "Site administration" node
    When I expand "Plugins" node
    When I expand "Authentication" node
    When I follow "Manage authentication"
    When I click on "enable" "link" in the "Oauth2" "table_row"
    When I click on "Oauth2" "link"
    And I set the field "googleclientid" to "1234567890"
    And I set the field "googleclientsecret" to "1234567890"
    And I press "Save changes"
    When I follow "Log out"
    Then I should see "Home"
    When I follow "Log in"
    Then I should see "Sign-in with Google"
    #Then I follow "Sign-in with Google"
    #When I fill in "Email" with "1234567890"
    #And I fill in "Passwd" with "1234567890"
    #And I press "Sign in"
    #Then I should see "Log out"

