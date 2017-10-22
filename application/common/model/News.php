<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 12:14
 */
namespace app\common\model;


class News extends BaseModel
{
    protected $autoWriteTimestamp = true;

    /**
     * 后台自动化分类
     * @param array $data
     */
    public function getNews($data = [])
    {
        $data['status'] = [
            'neq', config('code.status_delete')
        ];
        $order = ['id' => 'desc'];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
}