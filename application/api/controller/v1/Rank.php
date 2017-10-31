<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/27
 * Time: 16:02
 */

namespace app\api\controller\v1;


use app\api\controller\Common;
use app\common\lib\exception\ApiException;

class Rank extends Common
{
    /**
     * 获取排行榜数据
     */
    public function index()
    {
        try{
            $news = model('news')->getRankNormalNews(5);
            $news = $this->getDealNews($news);
            return api_return($news);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), 500);
        }
    }
}