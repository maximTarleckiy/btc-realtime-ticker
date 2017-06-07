<?php
/**
 * Created by PhpStorm.
 * User: bbs_m
 * Date: 6/6/2017
 * Time: 9:59 AM
 */

return [
    'db' => ['path' => dirname(__FILE__) . '/db/data.db'],
    'providers' => [
        'coindesk' => [
            'class' => 'app\Providers\CoindeskBitcoinExchangeProvider',
            'expiration_interval' => 10,
        ],
        'exception_provider' => [
            'class' => 'app\Providers\ExceptionProvider'
        ],
        'coindesk-again' => [
            'class' => 'app\Providers\CoindeskBitcoinExchangeProvider'
        ],
        'test1' => [
            'class' => 'app\Providers\TestEuroUsdProvider',
            'expiration_interval' => 100,
        ],
        'test2' => [
            'class' => 'app\Providers\TestEuroUsdProvider',
            'expiration_interval' => 20,
        ],
        'test3' => [
            'class' => 'app\Providers\TestBtcUsdProvider',
            'expiration_interval' => 30
        ]
    ]
];