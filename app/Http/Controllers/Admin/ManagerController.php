<?php

namespace App\Http\Controllers\Admin;

use App\Model\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{

    public function __construct()
    {
        // 
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            // 显示视图
            return view('Admin.login');
        } elseif ($request->isMethod('post')) { // 判断是否是ajax请求 $request->ajax()
            // 1.数据验证:用户名长度,是否为空
            // ①使用validate方法验证
//            $this->validate($request, [
//                'username' => 'required|min:2|max:16',
//                'password' => 'required|between:6,20',
//                'captcha' => 'required|size:5|captcha',
//            ]);
//            -----------------------------------------------------------
            // ②validator门面验证
            $validator = Validator::make($request->all(), [
                'username' => 'required|min:2|max:16',
                'password' => 'required|between:6,20',
                'captcha' => 'required|size:5|captcha',
            ]);
            if ($validator->fails()) {
                return redirect('/admin/login')->withErrors($validator)->withInput();
//                return response()->json([
//                    'success' => false,
//                    'errors' => $validator->errors()->toArray()
//                ]);
            }

            // 2.用户认证(用户名与密码是否对应)
            $username = $request->input('username');
            $password = $request->input('password');
            if (Auth::guard('admin')->attempt(['username' => $username, 'password' => $password])) {
                return redirect('/admin/index');
            } else {
                return redirect('/admin/login')->withErrors(['loginError' => '用户名或密码错误'])->withInput();
            }

        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lst()
    {
        $data = Manager::paginate(15);
//        $data = Manager::all()->forPage(1, 8);
//        dd($data);
        return view('Admin.manager.list', ['data' => $data]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function add(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('Admin.manager.add');
        } elseif ($request->isMethod('post')) {
            $manager = Manager::create($request->all());
            $manager->password = bcrypt($request->input('password'));
            // 文件处理
            $file = $request->file('file');
            if ($file->isValid()) {
                $path = $file->store('public');
            }
            $manager->mg_pic = str_replace('public', '/storage', $path);

            if ($manager->save()) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function del(Request $request){
        $mg_id = $request->input('mg_id');
        $res = Manager::find($mg_id)->delete();
        if ($res === false) {
            return ['success' => false];
        } else {
            return ['success' => true];
        }
    }


}
