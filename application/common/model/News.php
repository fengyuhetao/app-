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

    /**
     * 获取列表数据
     * @param array $param
     */
    public function getNewsByCondition($condition = [], $from = 0, $size = 5)
    {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $order = ['id' => 'desc'];

//        limit a,b
        $result = $this->where($condition)
            ->limit($from, $size)
            ->order($order)
            ->select();

//        halt($this->getLastSql());
        return $result;
    }

    /**
     * 根据条件获取新闻条数
     * @param array $param
     */
    public function getNewsCountByCondition($condition = [])
    {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $count = $this->where($condition)
            ->count();

        return $count;
    }

    /**
     * 获取头图
     */
    public function getIndexHeadNormalNews($num = 4)
    {
        $condition = [
            'status' => 1,
            'is_head_figure' => 1
        ];

        $order = [
            'id' => 'desc'
        ];

        $result = $this->where($condition)
            ->field($this->getListField())
            ->limit($num)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 获取首页推荐位新闻
     *
     * @param int $num
     * @return object
     * @internal param int $limit
     */
    public function getPositionNormalNews($num = 20) {
        $condition = [
            'status' => 1,
            'is_position' => 1,
        ];

        $order = [
            'id' => 'desc'
        ];

        $result = $this->where($condition)
            ->field($this->getListField())
            ->limit($num)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * @param int $num
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getRankNormalNews($num = 5) {
        $condition = [
            'status' => 1,
        ];

        $order = [
            'read_count' => 'desc'
        ];

        $result = $this->where($condition)
            ->field($this->getListField())
            ->limit($num)
            ->order($order)
            ->select();

        return $result;
    }

    /**
     * 通用化获取参数的数据字段
     */
    private function getListField() {
        return [
            'id',
            'catid',
            'image',
            'title',
            'read_count',
            'status',
            'is_position',
            'update_time',
            'create_time'
        ];
     }
}