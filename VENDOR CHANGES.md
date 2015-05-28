List here the hacks done in vendor.

* class Depotwarehouse\OAuth2\Client\ProviderBattleNet 
=> change urls: us to eu

* pixelfear/oauth2-dropox
=> I actually use a fork of this rep where I changed the require version of League oauth2.

* class League\OAuth2\Client\Provider\LinkedIn
=> test if $response->publicProfileUrl is not empty. 
   The problem here is that the provider class expect the r_contactinfo scope which need to be manually requested to LinkedIn.
   'urls' => isset($response->publicProfileUrl)?$response->publicProfileUrl:'',
