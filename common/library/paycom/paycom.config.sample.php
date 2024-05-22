<?php
// sample configuration with fake values
return [
    // Get it in merchant's cabinet in cashbox settings
    'merchant_id' => '5f742c792a1efb16263be4a8',

    // Login is always "Paycom"
    'login'       => 'Paycom',

    // File with cashbox key (key can be found in cashbox settings)
    'key'     => 'NJ@OmP6WABBjg6BshRi1gNi2r#KBKeskki3@',

    // Your database settings
    'db'          => [
        'host'     => '<database host>',
        'database' => '<database name>',
        'username' => '<database username>',
        'password' => '<database password>',
    ],
];
