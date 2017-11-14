<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::resource('api/test', 'api/test');

Route::resource('api/:ver/index', 'api/:ver.index');

Route::get('api/:ver/cat', 'api/:ver.cat/read');

Route::post('api/:ver/cat', 'api/:ver.cat/testJson');

Route::get('api/:ver/rank', 'api/:ver.rank/index');

Route::get('api/:ver/news/:id', 'api/:ver.news/read');

Route::get('api/:ver/news', 'api/:ver.news/index');

Route::get('api/:ver/init', 'api/:ver.index/init');

Route::get('api/sendSms', 'api/test/sendSms');

Route::post('api/:ver/login', 'api/:ver.login/save');

Route::resource('api/:ver/user', 'api/:ver.user');

Route::post('api/:ver/image', 'api/:ver.image/save');

Route::post('api/:ver/upvote', 'api/:ver.upvote/save');

Route::delete('api/:ver/upvote', 'api/:ver.upvote/delete');
//Route::get('test', 'api/test/index');
//
//Route::put('test/:id', 'api/test/update');
 //return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//];