<?php

namespace App\Http\Controllers\Home;

use App\Events\SendPhoneCodeEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    //
    public function Index()
    {
//        echo '111-123';
//
//        die();
        event(new SendPhoneCodeEvent(1, 'SH600547', '20190828', 120));

        $ret = [
            'retCode' => 2000,
            'retMsg' => 'Success',
            'retData' => [
                'name' => 'Little Flower',
                'age' => 18,
            ]
        ];
//        echo $ret;
        return response($ret);
    }
}
