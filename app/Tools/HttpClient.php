<?php

namespace App\Tools;

use GuzzleHttp\Client;

class HttpClient
{
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    private static $httpClient;

    /**
     * @return Client
     */
    public static function getHttpClient()
    {
        if (null === self::$httpClient) {
            self::$httpClient = new Client([
                // You can set any number of default request options.
                'timeout'  => 5.0,
                'http_errors' => false,
            ]);

        }
        return self::$httpClient;
    }
}