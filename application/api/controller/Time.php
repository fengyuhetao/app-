<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/26
 * Time: 16:05
 */

namespace app\api\controller;


use think\Controller;

class Time extends Controller
{
    public function index()
    {
        return api_return(time());
    }
}