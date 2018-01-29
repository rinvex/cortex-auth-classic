<?php

declare(strict_types=1);

return [

    // Fort media storage disk
    'media' => [
        'disk' => 'public',
    ],

    //Reauthentication
    'reauthentication' => [
        'prefix' => 'cortex.fort.reauthentication.',
        'timeout' => 3600
    ]

];
