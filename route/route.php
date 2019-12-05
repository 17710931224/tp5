<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


Route::rule('/admin/user/create','admin/UsersController/create');
Route::rule('/admin/user/save','admin/UsersController/save');
Route::rule('/admin/user/index','admin/UsersController/index');
Route::rule('/admin/user/:id/delete','admin/UsersController/delete');
Route::rule('/admin/user/:id/edit','admin/UsersController/edit');
Route::rule('/admin/user/update/:id','admin/UsersController/update');


 Route::group(['name'=>'/admin/cate', 'prefix'=>'admin/CateController/'],function(){
               Route::rule('create/[:id]', 'create', 'get');
               Route::rule('index', 'index', 'get');
               Route::rule('save','save','post');
               Route::rule('[:id]/edit','edit','get');
               Route::rule('update/[:id]','update','post');
               Route::rule(':id/delete','delete','get');
       });


Route::group(['name'=>'/admin/login', 'prefix'=>'admin/LoginController/'],function(){
        Route::rule('create', 'create', 'post');
        Route::rule('index', 'index', 'get');
       
});

Route::group(['name'=>'/admin/register', 'prefix'=>'admin/RegisterController/'],function(){
        Route::rule('create', 'create', 'get');
        Route::rule('index', 'index', 'get');
       
});

Route::view('/admin','admin@default');



//前台
Route::view('/','home@default');
//登录
Route::rule('/home/login/index','home/LoginController/index');
Route::rule('/home/login/create','home/LoginController/create');
Route::rule('/home/login/logout','home/LoginController/logout');

//个人中心
Route::rule('/home/register/user','home/RegisterController/user');
Route::rule('/home/register/passw','home/RegisterController/passw');
Route::rule('/home/register/update','home/RegisterController/update');
Route::rule('/home/register/history','home/RegisterController/history');
Route::rule('/home/register/time','home/RegisterController/time');


//注册
Route::rule('/home/register/index','home/RegisterController/index');
Route::rule('/home/register/save','home/RegisterController/save');
//单短
Route::rule('/home/index/index','home/IndexController/index');
Route::rule('/home/index/save','home/IndexController/save');
Route::rule('/home/index/create','home/IndexController/create');
Route::rule('/home/index/link/[:id]','home/IndexController/read');

//批量
Route::rule('/home/batch/index','home/BatchController/index');
Route::rule('/home/batch/upload','home/BatchController/upload');
Route::rule('/home/batch/file/[:row]','home/BatchController/file');
Route::rule('/home/batch/down/[:row]','home/BatchController/down');

//统计
Route::rule('/home/detail/index','home/detailController/index');
Route::rule('/home/detail/time','home/detailController/time');



