<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/31
 * Time: 16:05
 */

namespace app\common\validate;


use think\Validate;

class Identify extends Validate
{
    protected $rule = [
        'id' => 'require|number|length:11'
    ];
}