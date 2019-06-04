<?php
/**
 * Created by PhpStorm.
 * User: wipzhu
 * Date: 2019/6/4
 * Time: 16:09
 */

use Illuminate\Support\Facades\Route;

// 登录模块           name()      别名login
Route::match(['get', 'post'], '/admin/login', 'Admin\ManagerController@login')->name('login');
Route::match(['get', 'post'], '/admin/register', 'Admin\ManagerController@register')->name('register');

// 后台模块：在该组中的路由使用中间件auth中admin guard进行验证
Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin/logout', 'Admin\ManagerController@logout');
    Route::get('/admin/index', 'Admin\IndexController@index');
    Route::get('/admin/welcome', 'Admin\IndexController@welcome');

    Route::get('/admin/manager/lst', 'Admin\ManagerController@lst');
    Route::match(['get', 'post'], '/admin/manager/add', 'Admin\ManagerController@add');
    Route::post('/admin/manager/del', 'Admin\ManagerController@del');
});
