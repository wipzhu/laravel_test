<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Workerman\Worker;

class HomeController extends Controller
{
    public function articles(){
        return view('home.articles');
    }

    public function test_workerman(){;
        return view('home.test_workerman');
    }
}
