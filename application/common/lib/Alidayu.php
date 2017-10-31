<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/31
 * Time: 14:20
 */

namespace app\common\lib;


use think\Cache;
use think\Log;

class Alidayu
{
    const LOG_TPL = "alidayu:";

    private static $_instance = null;

    private function __construct()
    {
    }

    /**
     * 静态方法 单例模式
     * @return Alidayu|null
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setSmsIdentify($phone = 0) {
        Log::write(self::LOG_TPL ." sendSms----begin");
        // 调用示例：
        set_time_limit(0);
        header('Content-Type: text/plain; charset=utf-8');
        $code = random_int(1000, 9999);

        try {
            $response = SendSms::sendSms(
                config('alidayu.signName'), // 短信签名
                config('alidayu.templateCode'),
                $phone, // 短信接收者
                array(  // 短信模板中字段的值
                    "code"=> $code,
                ),
                "123"   // 流水号,选填
            );
        } catch (\Exception $e) {
            Log::write(self::LOG_TPL ." sendSms-----失败， info = ". $e->getMessage());
            return false;
        }

        if($response->Code == 'OK') {
            Log::write(self::LOG_TPL ." sendSms-----成功");
//        设置验证码失效时间
            Cache::set($phone, $code, config('alidayu.identify_time'));
            return true;
        } else {
            Log::write(self::LOG_TPL ." sendSms-----失败");
            return false;
        }
    }

    public function checkSmsIdentify($phone = 0) {
        if(!$phone) {
            return false;
        }

        return Cache::get($phone);
    }

    function object_to_array($obj){
        $_arr = is_object($obj) ? get_object_vars($obj) :$obj;
        $arr = [];
        foreach ($_arr as $key=>$val){
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val):$val;
            $arr[$key] = $val;
        }
        return $arr;
    }
}