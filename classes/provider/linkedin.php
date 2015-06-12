<?php

require_once $CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php';

class provideroauth2linkedin extends League\OAuth2\Client\Provider\LinkedIn {

    // THE VALUES YOU WANT TO CHANGE WHEN CREATING A NEW PROVIDER.
    public $sskstyle = 'linkedin';
    public $name = 'linkedin'; // it must be the same as the XXXXX in the class name provideroauth2XXXXX.
    public $readablename = 'Linkedin';
    public $scopes = array('r_basicprofile', 'r_emailaddress');

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
            'redirectUri'   => $CFG->wwwroot .'/auth/googleoauth2/' . $this->name . '_redirect.php',
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