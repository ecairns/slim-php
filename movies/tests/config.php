<?php

return [
    "settings" => [
        "displayErrorDetails"    => true,
        "addContentLengthHeader" => false,
        "db"                     =>
            [
                "driver"    => "mysql",
                "host"      => "mysql",
                "database"  => "sakila",
                "username"  => "sakila",
                "password"  => "sakila",
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],
        "search"                 => [
            "type" => "pdo"
        ]
    ]
];
