<?php
namespace app\admin\controller;
use think\Request;
use app\common\lib\Upload;
/**
 * 后台图片上传
 * Class News
 * @package app\admin\controller
 */
class Image extends Base
{
    public function uploadLocal()
    {
        $file = Request::instance()->file("file");
        //        上传到指定文件夹
        $info = $file->move('public/upload');

        if($info && $info->getPathname()) {
            $data = array(
                'status' => 1,
                'message' => 'OK',
                'data' => url($info->getPathname(), '', '')
            );
            return json_encode($data);
        }

        return ['status' => 0, 'message' => '上传失败'];
    }

    public function uploadQiniu()
    {
        try{
            $image = Upload::image();
        } catch (\Exception $e) {
            return ['status' => 0, 'message' => $e->getMessage()];
        }
        if($image) {
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => config('qiniu.image_url').'/'. $image
            ];

            return json_encode($data);
        }

        return json_encode(['status' => 0, 'message' => '上传失败']);
    }
}
