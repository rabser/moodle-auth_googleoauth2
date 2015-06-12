<?php

require_once $CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php';

class provideroauth2battlenet extends \Depotwarehouse\OAuth2\Client\Provider\BattleNet {

    // THE VALUES YOU WANT TO CHANGE WHEN CREATING A NEW PROVIDER.
    public $sskstyle = 'battlenet';
    public $name = 'battlenet'; // it must be the same as the XXXXX in the class name provideroauth2XXXXX.
    public $readablename = 'Battle.net';
    public $scopes = array('sc2.profile');

    /**
     * Constructor.
     *
     * @throws Exception
     * @throws dml_exception
     */
    public function __construct() {
        global $CFG;

        parent::__construct([
            'clientId'      => get_config('auth/googleoauth2', $this->name . 'clientid'),
            'clientSecret'  => get_config('auth/googleoauth2', $this->name . 'clientsecret'),
            'redirectUri'   => preg_replace('/http:/', 'https:', $CFG->httpswwwroot .'/auth/googleoauth2/' . $this->name . '_redirect.php', 1),
            'scopes'        => $this->scopes
        ]);
    }

    /**
     * Is the provider enabled.
     *
     * @return bool
     * @throws Exception
     * @throws dml_exception
     */
    public function isenabled() {
        return (get_config('auth/googleoauth2', $this->name . 'clientid')
            && get_config('auth/googleoauth2', $this->name . 'clientsecret'));
    }

    /**
     * The html button.
     *
     * @param $authUrl
     * @param $providerdisplaystyle
     * @return string
     * @throws coding_exception
     */
    public function html_button($authUrl, $providerdisplaystyle) {
        return googleoauth2_html_button($authUrl, $providerdisplaystyle, $this);
    }
}