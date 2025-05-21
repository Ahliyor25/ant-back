<?php

return [
    "log" => [
        "dir" => env("BITRIX24_LOG_DIR", "storage/logs/bitrix24.log"),
        "level" => env("LOG_LEVEL", \Monolog\Logger::toMonologLevel(300)),
    ],

    "credentials" => [
        "type" => "webhook",
        "domain" => env("BITRIX24_DOMAIN", ''),
        "url" => env("BITRIX24_WEBHOOK", ''),
        "client_id" => env("BITRIX24_CLIENT_ID", ''),
        "client_secret" => env("BITRIX24_CLIENT_SECRET", ''),
    ],

    "webhook" => [
        "token" => env("BITRIX24_WEBHOOK_TOKEN"),
    ],

    "catalog" => [
        "iblockId_catalog" => env('BITRIX24_IBCLOCKID_CATALOG'),
        "iblockId_variations" => env('BITRIX24_IBCLOCKID_VARIATIONS'),
    ]
];
