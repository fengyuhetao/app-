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

class User extends AuthBase
{
    public function test()
    {
        halt($this->user);
    }

    /**
     * 获取用户信息
     */
    public function read()
    {
        /**
         * 该信息非常隐私，需要加密
         */
        $obj = new Aes();
        return api_return($obj->encrypt($this->user), config('code.success'));
    }

    /**
     * 修改數據
     */
    public function update()
    {
        $postData = input('param.');
        $data = [];

//        validate校验
        if(!empty($postData['image'])) {
            $data['image'] = $postData['image'];
        }

        if(!empty($postData['usename'])) {
            $data['usename'] = $postData['usename'];
        }

        if(!empty($postData['password'])) {
//           TODO 传输过程中也应该加密
            $data['password'] = IAuth::setPassword($postData['password']);
        }

        if(!empty($postData['signature'])) {
            $data['signature'] = $postData['signature'];
        }

        if(!empty($postData['sex'])) {
            $data['sex'] = $postData['sex'];
        }

        if(!empty($postData['sex'])) {
            $data['sex'] = $postData['sex'];
        }

        if(empty($data)) {
            return api_return([], config('code.error'), '数据不合法');
        }

        try {
            $id = model('User')->save($data, ['id' => $this->user['id']]);
            if($id) {
                return api_return([], config('code.success'), 'OK', 202);
            } else {
                return api_return([], config('code.error'), '更新失败', 401);
            }
        } catch (\Exception $e) {
            return api_return([], config('code.error'), $e->getMessage(), 500);
        }
    }
}