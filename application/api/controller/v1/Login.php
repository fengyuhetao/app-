<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/11/06
 * Time: 20:30
 */

namespace app\api\controller\v1;


use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;

class Login extends Common
{
    public function save()
    {
        if(!request()->isPost()) {
            return api_return([], config('code.error'), '你没有权限', 403);
        }

        $param = input('param.');
        if(empty($param['phone'])) {
            return api_return([], config('code.error'), '手机不合法', 404);
        }

        if(empty($param['code'])) {
            return api_return([], config('code.error'), '手机短信不合法', 404);
        }

//        以验证码的形式登录
        if($param['code']) {
            //        validate严格校验
            $code = Alidayu::getInstance()->checkSmsIdentify($param['phone']);
            if($code != $param['code']) {
                return api_return([], config('code.error'), '手机短信不正确', 404);
            }
        }

        $token = IAuth::setAppToken($param['phone']);
//        查询手机号是否存在
        $user = User::get(['phone' => $param['phone']]);
        if($user && $user->status == 1) {
            if(!empty($param['password'])) {
                //        以密码的形式登录,判断用户名和密码
                if(IAuth::setPassword($param['password']) != $user->password) {
                    return api_return([], config('code.error'), '密码不正确', 403);
                }
            }
//            $id = model('User')->save($data, ['phone' => $param['phone']]);
        } else {
            if(!empty($param['code'])) {
                //        第一次登录
                $data = [
                    'token' => $token,
                    'time_out' => strtotime("+" . config('app.login_time_out_day') . " days"),
                    'username' => 'suijiUsername',
                    'status' => 1,
                    'phone' => $param['phone']
                ];

                $id = model('User')->add($data);
            } else {
                return api_return([], config('code.error'), '验证码不正确', 403);

            }
        }

        $aes = new Aes();

        if($id) {
//            登录成功
            $result = [
                'token' => $aes->encrypt($token."||".$id),
            ];
            return api_return($result, config('code.success'));
        }
    }
}