<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class TestApiController extends Controller
{
    public function index(){
        return Response::json(['key' => 'Api请求测试成功']);
    }
}
