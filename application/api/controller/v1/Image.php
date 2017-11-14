<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/11/06
 * Time: 20:30
 */

namespace app\api\controller\v1;

use app\common\lib\Upload;

class Image
{
    public function save()
    {
        print_r($_FILES);
        $image = Upload::image();
        if($image) {
            return api_return(config('qiniu.image_url').$image, config('code.success'));
        }
    }
}