<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 19:52
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{
//    初始化前的方法
    public function _initialize()
    {
//        判断用户是否登录
        if(!$this->is_login()) {
            $this->redirect('app/admin/login/index');
        }
    }

    public function is_login()
    {
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));

        if($user && $user->id) {
            return true;
        }

        return false;
    }
}