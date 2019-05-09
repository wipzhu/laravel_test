<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagerController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            // 显示视图
            return view('Admin.login');
        } elseif ($request->isMethod('post')) {
            // 数据处理
            // 1.数据验证:用户名长度,是否为空
            // 2.用户认证(用户名与密码是否对应)

        }
    }

}
