<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/24
 * Time: 14:58
 */
namespace app\api\controller\v1;

use app\api\controller\Common;

/**
 * Cat
 * Class Cat
 * @package app\api\controller
 */
class Cat extends Common
{
   public function read()
   {
       $cats = config('cat.lists');

       $result = [];

       foreach ($cats as $catid => $catname) {
            $result[] = [
                'catid' => $catid,
                'catname' => $catname
            ];
       }

       return api_return($result);
   }

   public function testJson()
   {
       $data = file_get_contents("php://input");;
//       $data = array_keys($data);
       halt($data);
//       $data = json_decode($data['']);
       return api_return($data);
   }
}