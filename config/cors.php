<?php

return [
    'paths' => ['api/*', 'docs', 'api/documentation'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // atau ['http://localhost:8000']
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
