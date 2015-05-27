<?php

require('../../config.php');
require_once $CFG->dirroot . '/auth/googleoauth2/lib.php';
googleoauth2_provider_redirect('google');

