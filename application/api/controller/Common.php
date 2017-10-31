<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/24
 * Time: 14:58
 */
namespace app\api\controller;

use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;
use think\Controller;

/**
 * api模块公共控制器
 * Class Common
 * @package app\api\controller
 */
class Common extends Controller
{
    public $page = 1;
    public $size = 10;
    public $from = 0;

    public $headers = array();
    /*
     * 初始化方法
     */
    public function _initialize()
    {
//        $this->checkRequestAuth();
//        $this->testAes();

    }

    /**
     * 校验api请求是否合法
     */
    public function checkRequestAuth()
    {
//        1. 需要获取headers里边的数据
        $headers = request()->header();
//        todo

//        sign 加密需要客户端程序

//        基础参数校验
        if(empty($headers['sign'])) {
            throw new ApiException('sign不存在', 401);
        }

        if(!in_array($headers['app_type'], config('app.apptypes'))) {
            throw new ApiException('app_type不存在', 401);
        }

//        需要校验sign
        if(IAuth::checkSignPass($headers)) {
            throw new ApiException('授权码验证失败', 401);
        }

        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));
//        1. 文件 2. mysql 3. redis
        $this->headers = $headers;
    }

    public function testAes()
    {
        $data = [
            'did' => 1234,
            'version' => 1,
            'time' => Time::get13TimeStamp()
        ];
        halt(IAuth::setSign($data));
    }

    /**
     * @param array $news获取处理的新闻内容数
     * @return array
     */
    protected function getDealNews($news = []) {
        if(empty($news)) {
            return [];
        }

        $cats = config('cat.lists');

        foreach ($news as $key => $new) {
            $news[$key]['catname'] = $cats[$new['catid']] ? $cats[$new['catid']] : '-';
        }

        return $news;
    }

    /**
    * 获得分页
    */
    public function getPageAndSize($data)
    {
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }
}