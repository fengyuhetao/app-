<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 12:31
 */

namespace app\admin\controller;


use app\common\lib\IAuth;

class Login extends Base
{
    public function _initialize()
    {}

    public function index()
    {
//        如果已登录
        $isLogin = $this->is_login();
        if($isLogin) {
            return $this->redirect('app/admin/index/index');
        } else {
            return $this->fetch();
        }
    }

    public function check()
    {
        if(request()->isPost()) {
            $data = input('post.');
            if(!captcha_check($data['code'])) {
                $this->error('验证码不正确');
            }
            $user = null;
            try {
                $user = model('AdminUser')->get(['username' => $data['username']]);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if(!$user || $user->status == config(['code.status_delete'])) {
                $this->error(' 用户名或密码不正确');
            }

//            对密码进行校验
            if(IAuth::setPassword($data['password']) != $user['password']) {
                $this->error('用户名或密码不正确');
            }

//            登录时间，ip
            $udata = [
                'last_login_time' => time(),
                'last_login_ip'   => request()->ip(),
            ];

            try{
                model('AdminUser')->save($udata, ['id' => $user->id]);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

//            session
            session(config('admin.session_user'), $user, config('admin.session_user_scope'));

            $this->success('登录成功', 'admin/index/index');
        } else {
            $this->error('请求不合法');
        }
    }

    /**
     * 退出登录
     * 逻辑
     * 1. 清空session
     * 2. 跳转到登录页
     */
    public function logout()
    {
        session(null, config('admin.session_user_scope'));
        $this->redirect('login/index');
    }
}