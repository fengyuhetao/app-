<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/24
 * Time: 14:58
 */
namespace app\api\controller;

use app\common\lib\exception\ApiException;

use app\common\lib\SendSms;
use think\Cache;
use think\Exception;
use think\Log;


class Test
{
    const LOG_TPL = "alidayu";

    public function index()
    {
        return array(
            'code' => "你好呀"
        );
    }

    public function update($id)
    {
        return array(
            'code' => $id
        );
    }

    /**
     * post方法
     * @return array
     * @throws ApiException
     */
    public function save()
    {
        $data = input('post.');
        if($data['mt'] != 1) {
            throw new ApiException('不合法的数据', 502);
        }
        try{
            model('13');
        } catch (\ Exception $e) {
            return api_return([],0, $e->getMessage(), 400);
        }
//        获取到提交数据 插入库
//        给客户端app => 接口数据
        return api_return(input('post.'), 1, 'OK', 201);
    }

    /**
     * 测试使用阿里大于发送短信验证码
     */
    public function sendSms()
    {
        // 调用示例：
        set_time_limit(0);
        header('Content-Type: text/plain; charset=utf-8');
        $code = random_int(1000, 9999);

        $phone = '13263138306';
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
        } catch (Exception $e) {
            Log::write(self::LOG_TPL ." sendSms-----失败， info = ". $e->getMessage());
            return api_return("fail", 500);
        }

//        设置验证码失效时间
        Cache::set($phone, $code, config('alidayu.identify_time'));
        return api_return($response, 200);
    }

    public function checkSmsIdentify($phone = 0) {
        if(!$phone) {
            return false;
        }

        return Cache::get($phone);

    }
}