<?php

namespace App\Controllers;

class BaseController
{
    /**
     * @param $params
     * @return array|string
     */
    public function getRequiredParams($params)
    {
        $data = [];

        foreach ($params as $param) {
            if (! $value = input($param, '')) {
                return "The Request Parameters {$param} is Required .";
            }

            $data[$param] = $value;
        }

        return $data;
    }
}