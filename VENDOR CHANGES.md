List here the hacks that need to be done in the 'vendor' folder.

* class Depotwarehouse\OAuth2\Client\ProviderBattleNet
=> change battle.net urls: us to eu (if you want to use the european server of course)

* pixelfear/oauth2-dropox
=> nothing to do, just saying that I actually use a personal fork of this rep where I changed the require version of League oauth2.

* class League\OAuth2\Client\Provider\LinkedIn
=> test if $response->publicProfileUrl is not empty.
   The problem here is that the provider class expect the r_contactinfo scope which need to be manually requested to LinkedIn.
   'urls' => isset($response->publicProfileUrl)?$response->publicProfileUrl:'',
