<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/27
 * Time: 16:04
 */

namespace app\api\controller\v1;


use app\api\controller\Common;
use app\common\lib\exception\ApiException;

class News extends Common
{
    public function index()
    {
        $data = input('get.');
        $whereData['status'] = config('coce.status_normal');
        if(!empty($data['catid'])) {
            $whereData['catid'] = $data['catid'];
        }

        if(!empty($data['title'])) {
            $whereData['title'] = ['like', '%'. $data['title'] . '%'];
        };

        $this->getPageAndSize($data);
        $total = model('news')->getNewsCountByCondition($whereData);
        $news = model('news')->getNewsByCondition($whereData, $this->from, $this->size);

        $result = [
            'total' => $total,
            'page_num' => ceil($total / $this->size),
            'list' => $this->getDealNews($news)
        ];

        return api_return($result);
    }

    public function read()
    {
        $id = input('param.id', 0, 'intval');
        if(empty($id)) {
            throw new ApiException('id is notfound', 404);
        }

        $new = model('news')->get($id);
        if(empty($new) || $new->status != config('code.status_normal')) {
            throw new ApiException('不存在该新闻', 404);
        }

        try {
            model('news')->where(['id' => $id])->setInc('read_count');
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), 500);
        }

        $cats = config('cat.lists');
        $new->catname = $cats[$new->catid];
        return api_return($new);
    }
}