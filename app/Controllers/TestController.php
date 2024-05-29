<?php

namespace App\Controllers;

use App\Tools\ParentID;
use Illuminate\Database\Capsule\Manager as DB;


class TestController extends BaseController
{

    public function testResponse($request)
    {
        // dd($request);

        // text/html
        // return view('test/index.html', ['name' => 'test']);

        // return response('ok');

        // return success();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test($request)
    {
        // dd($request);
        return success();
    }
}