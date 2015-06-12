# Dropbox Provider for OAuth 2.0 Client

[![Build Status](https://travis-ci.org/pixelfear/oauth2-dropbox.svg?branch=master)](https://travis-ci.org/pixelfear/oauth2-dropbox)
[![Latest Stable Version](https://poser.pugx.org/pixelfear/oauth2-dropbox/v/stable.svg)](https://packagist.org/packages/pixelfear/oauth2-dropbox)

This package provides Dropbox OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation 

To install, use composer:

```
composer require pixelfear/oauth2-dropbox
```

## Usage

Usage is the same as The League's OAuth client, using `\Pixelfear\OAuth2\Client\Provider\Dropbox` as the provider.

### Authorization Code Flow

```php
$provider = new Pixelfear\OAuth2\Client\Provider\Dropbox([
    'clientId'          => '{dropbox-client-id}',
    'clientSecret'      => '{dropbox-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);

        // Use these details to create a new profile
        printf('Hello %s!', $userDetails->firstName);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->accessToken;
}
```

## Refreshing a Token
Dropbox's OAuth implementation does not use refresh tokens. Access tokens are valid until a user revokes access manually, or until an app deauthorizes itself.