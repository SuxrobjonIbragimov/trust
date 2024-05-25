<?php

//  ██████╗██╗     ██╗ ██████╗██╗   ██╗    ██╗    ██╗██████████╗
// ██╔════╝██║     ██║██╔════╝██║ ██╔═╝    ██║    ██║      ██╔═╝
// ██║     ██║     ██║██║     ████╔═╝      ██║    ██║    ██╔═╝
// ██║     ██║     ██║██║     ██║ ██╗      ██║    ██║  ██══╝
// ╚██████╗███████╗██║╚██████╗██║   ██╗ ██╗█████████║██████████╗
//  ╚═════╝╚══════╝╚═╝ ╚═════╝╚═╝   ╚═╝ ╚═╝╚════════╝╚═════════╝

return [
    'provider' => [
        'endpoint' => 'https://my.click.uz/services/pay',
        'endpointMerchant' => 'https://api.click.uz/v2/merchant',
        'click' => [
            'merchant_id' => '8999',
            'service_id' => '21229',
            'user_id' => '24395',
            'secret_key' => 'zGKbym182apdE'
        ],
        'click-osgo' => [
            'merchant_id' => '8999',
            'service_id' => '21229',
            'user_id' => '24395',
            'secret_key' => 'zGKbym182apdE'
        ]
    ],
];