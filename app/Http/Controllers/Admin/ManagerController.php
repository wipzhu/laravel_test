<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{

    public function __construct()
    {
        // 
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            // 显示视图
            return view('Admin.login');
        } elseif ($request->isMethod('post') /*&& $request->ajax()*/) {
            $username = $request->input('username');
            $password = $request->input('password');
            $res = Auth::attempt(['username' => $username, 'password' => $password]);
            pr($res);
            dd($res);
            // 数据处理
//            dd($request->input());
//            $this->validate($request, [
//                'username' => 'required',
//                'password' => 'required',
//                'captcha' => 'required|captcha',
//            ],[
//                'captcha.required' => trans('validation.required'),
//                'captcha.captcha' => trans('validation.captcha'),
//            ]);
//            --------------------------------------------------------------------------
            // 1.数据验证:用户名长度,是否为空
            // 2.用户认证(用户名与密码是否对应)

        }
    }

}
