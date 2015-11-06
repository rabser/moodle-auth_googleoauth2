<?php

return [
    'clientId' => getenv('BNET_CLIENT_ID'),
    'clientSecret' => getenv('BNET_CLIENT_SECRET'),
    'redirectUri' => getenv("BNET_REDIRECT_URI")
];
