<?php

namespace Depotwarehouse\OAuth2\Client\Provider;

use Depotwarehouse\OAuth2\Client\Entity\BattleNetUser;
use Guzzle\Http\Exception\BadResponseException;
use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class BattleNet extends AbstractProvider
{

    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    public $uidKey = 'accountId';

    public $scopes = [
        'sc2.profile'
    ];

    public $scopeSeparator = " ";

    public function urlAuthorize()
    {
        return "https://eu.battle.net/oauth/authorize";
    }

    public function urlAccessToken()
    {
        return "https://eu.battle.net/oauth/token";
    }

    public function urlUserDetails(AccessToken $token)
    {
        return "https://eu.api.battle.net/sc2/profile/user?access_token=" . $token;
    }

    /**
     * @param $response
     * @param AccessToken $token
     * @return BattleNetUser
     */
    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        $response = (array)($response->characters[0]);
        $user = new BattleNetUser($response);

        return $user;
    }
}
