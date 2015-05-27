<?php

require_once $CFG->dirroot . '/auth/googleoauth2/vendor/autoload.php';

class provideroauth2google extends League\OAuth2\Client\Provider\Google {

    // THE VALUES YOU WANT TO CHANGE WHEN CREATING A NEW PROVIDER.
    public $zocialstyle = 'googleplus';
    public $name = 'google'; // it must be the same as the XXXXX in the class name provideroauth2XXXXX.
    public $readablename = 'Google';
    public $scopes = array('profile', 'email');

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
        $a = new stdClass();
        $a->providername = $this->readablename;
        return '<div class="singinprovider" style="' . $providerdisplaystyle .'">
            <a class="zocial ' . $this->zocialstyle .'" href="'.$authUrl.'">
                '.get_string('auth_sign-in_with','auth_googleoauth2', $a).'
            </a>
        </div>';
    }
}