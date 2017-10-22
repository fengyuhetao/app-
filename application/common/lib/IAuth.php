<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 14:43
 */
namespace app\common\lib;

class IAuth
{
    /**
     * 设置密码
     * @param $data
     * @return string
     */
    public static function setPassword($password)
    {
        return md5($password . config('app.password_pre_halt'));
    }
}