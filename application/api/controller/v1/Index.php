<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/26
 * Time: 16:53
 */

namespace app\api\controller\v1;


use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use think\Log;

class Index extends Common
{

    /**
     * 获取首页接口
     * 1. 头图
     * 2. 推荐位列表 默认40条
     */
    public function index()
    {
        $news = model('news');
        $heads = $news->getIndexHeadNormalNews();
        $heads = $this->getDealNews($heads);
        $normals = $news->getPositionNormalNews();
        $normals = $this->getDealNews($normals);

        $result = [
            'heads' => $heads,
            'positions' => $normals
        ];
        return api_return($result);
    }

    /**
     * 客户端初始化接口
     * 检测app是否需要升级
     */
    public function init()
    {
        // app_type 查询ent_version 最后一条数据
        $version = model('version')->getLastVersion($this->headers['app_type']);

        if(empty($version)) {
            return new ApiException('error', 404);
        }

        if($version->version > $this->headers['version']) {
            $version->is_update = $version->is_force == 1 ? 2 : 1 ;
        } else {
            $version->is_update = 0; //0 不需要更新 1 需要更新 2 强制更新
        }

//        记录用户的基本信息
        $actives = [
            'version' => $this->headers['version'],
            'app_type' => $this->headers['app_type'],
            'did' => $this->headers['did'],
        ];
        try {
            model('AppActive')->add($actives);
        } catch (\Exception $e) {
            // todo
//            Log::write("")
        }
        return api_return($version, 200);
    }
}