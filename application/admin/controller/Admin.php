<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 11:29
 */

namespace app\admin\controller;

use app\common\lib\IAuth;

class Admin extends Base
{
    public function add()
    {
//        判断是否是post
        if(request()->isPost()) {
//            dump(input('post.'));  halt()
            $data = input('post.');
            $validate = validate('AdminUser');
            if(!$validate->check($data)) {
                $this->error($validate->getError());
            }

            $data['password'] = IAuth::setPassword($data['password']);
            $data['status'] = 1;

//            exception
//            add id
            $id = null;
            try{
                $id = model('AdminUser')->add($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if($id) {
                $this->success('id='. $id .'的用户新增成功');
            } else {
                $this->error('error');
            }
        }
        return $this->fetch();
    }
}