<?php

namespace App\Services;

use GuzzleHttp\Client;

class BaseService extends AbstractService
{
    protected $httpClient;

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        if ($this->httpClient) {
            return $this->httpClient;
        }
        $this->httpClient = new Client([
            // You can set any number of default request options.
            'timeout'  => 10.0,
            'http_errors' => false,
        ]);

        return $this->httpClient;
    }
}