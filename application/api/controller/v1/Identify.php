<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/31
 * Time: 15:58
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Alidayu;

class Identify extends Common
{
    /**
     * post
     */
    public function save()
    {
//        设置短信验证码
        if(!request()->isPost()) {
            return api_return([], config('code.error'), '您提交的数据不合法', 403);
        }

        $validate = validate('Identify');

        if(!$validate->check(input('post.'))) {
            return api_return([], config('code.error'), $validate->getError(), 403);
        }

        $alidayu = Alidayu::getInstance();
        $id = input('param.id');
        if($alidayu->setSmsIdentify($id)) {
            return api_return([], config('code.success'));
        } else {
            return api_return([], config('code.error'), 'error', 403);
        }
    }
}