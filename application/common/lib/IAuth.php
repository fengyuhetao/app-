<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 14:43
 */
namespace app\common\lib;

use think\Cache;

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

    /**
     * 生成每次请求的sign
     * @param array $data
     */
    public static function setSign($data = [])
    {
//        该加密算法需要和客户端协商
//        按字段排序
        ksort($data);
        $string = http_build_query($data);
//        通过aes来加密
        $aes = new Aes();
        $encrypt_data = $aes->encrypt($string);
//        所有字符转为大写
        return $encrypt_data;
    }

    /**
     * 检测sign是否正常
     * @param array data
     * @return bool
     */
    public static function checkSignPass($data)
    {
        $str = (new Aes())->decrypt($data['sign']);

        if(empty($str)) {
            return false;
        }

        parse_str($str, $arr);

        if(time() - ceil($arr['time'] / 1000) > config('app.app_sign_expire_time')) {
            return false;
        }

//        唯一性判断
        if(!Cache::get($data['sign'])) {
            return false;
        }

        if(!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did']) {
            return false;
        }

        return true;

    }

    /**
     * TODO 不完善，应该在token中加入时间戳，设置过期时间，当token失效时，要求重新登录，类似于sign一样      access_token = token+13位时间戳
     * 设置远程登录token
     */
    public static function setAppToken($phone = '')
    {
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str.$phone);
        return $str;
    }
}