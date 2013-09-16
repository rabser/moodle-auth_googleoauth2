<?php

/*
 * Get linkedin code and call the normal login page
 * Needed to add the parameter authprovider in order to identify the authentication provider
 */
require('../../config.php');
$code = optional_param('code', '', PARAM_TEXT); //Google can return an error

if (empty($code)) {
    throw new moodle_exception('linkedin_failure');
}

$loginurl = '/login/index.php';
if (!empty($CFG->alternateloginurl)) {
    $loginurl = $CFG->alternateloginurl;
}
$url = new moodle_url($loginurl, array('code' => $code, 'authprovider' => 'linkedin'));
redirect($url);
?>
