<?php

namespace app\common\lib;

use Qiniu\Auth;

use Qiniu\Storage\UploadManager;

/**
 * 七牛图片上传基础类库
 * Class Upload
 * @package app\common\lib
 */
class Upload {
    /**
     * 图片上传
     */
    public static function image()
    {
        if(empty($_FILES['file']['tmp_name'])) {
            exception('您提交的图片数据不合法', 404);
        }

//        要上传的文件的临时路径
        $file = $_FILES['file']['tmp_name'];
        $ext = explode('.', $_FILES['file']['name']);
        $ext = $ext[1];
//        $pathinfo = pathinfo($_FILES['file']['name']);
//        $ext = $pathinfo['extension'];

//        构件一个鉴权对象
        $config = config('qiniu');
        $auth = new Auth($config['ak'], $config['sk']);
//        生成上传token
        $token = $auth->uploadToken($config['bucket']);
//        上传到七牛后保存的文件名
        $key = date('Y') ."/". date('m') ."/". substr(md5($file), 0, 5)
            .date('YmdHis').rand(0,9999).'.'.$ext;
//        初始化UploadManager类
        $uploadMgr = new UploadManager();
        list($res, $err) = $uploadMgr->putFile($token, $key, $file);

        if($err != null) {
            return null;
        } else {
            return $key;
        }
    }
}