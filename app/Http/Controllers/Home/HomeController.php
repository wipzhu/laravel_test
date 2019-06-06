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

    public function wkWebsocket(){;
        return view('home.wkWebsocket');
    }

    public function wkHttp(){;
        return view('home.wkHttp');
    }
}
