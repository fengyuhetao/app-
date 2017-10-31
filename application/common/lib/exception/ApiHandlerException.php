<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/24
 * Time: 16:42
 */

namespace app\common\lib\exception;

use think\exception\Handle;

class ApiHandlerException extends Handle
{
    public $httpCode = 500;

    public function render(\Exception $e) {
        /*
        if(config('app_debug') == true) {
            return parent::render($e);
        }*/
        if($e instanceof ApiException) {
            $this->httpCode = $e->getHttpCode();
        }
        return api_return([], 0, $e->getMessage(), $this->httpCode);
    }
}