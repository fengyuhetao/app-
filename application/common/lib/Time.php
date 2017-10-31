<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 14:43
 */
namespace app\common\lib;

class Time
{
    /**
     * 获取13位时间戳
     */
   public static function get13TimeStamp()
   {
       list($t1, $t2) = explode(' ', microtime());
       return $t2 . ceil($t1 * 1000);
   }
}