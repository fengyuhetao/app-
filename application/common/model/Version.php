<?php

/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 12:14
 */
namespace app\common\model;


class Version extends BaseModel
{
    protected $autoWriteTimestamp = true;

    /**
     * 通过apptype获取最后一条版本内容
     * @param string $appType
     * @return Version|false|null|\PDOStatement|string|\think\Collection
     */
    public function getLastVersion($appType = '') {
        $data = [
            'status' => 1,
            'app_type' => $appType
        ];

        $order = [
            'id' => 'desc'
        ];

        return $this->where($data)
            ->limit(1)
            ->order($order)
            ->find();
    }
}