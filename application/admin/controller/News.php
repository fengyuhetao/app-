<?php
namespace app\admin\controller;


class News extends Base
{
    public function index()
    {
        $data = input('param.');
//        应该去除$data['page'] 不去掉也无所谓，url中第二个page的值会覆盖第一个page
        $query = http_build_query($data);
//        echo ($query);
        $whereData = [];

//        转换查询条件
        if(!empty($data['start_time']) && !empty($data['end_time'])
            && $data['end_time'] > $data['start_time']) {
            $whereData['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])]
            ];
        }

        if(!empty($data['catid'])) {
            $whereData['catid'] = intval($data['catid']);
        }

        if(!empty($data['title'])) {
            $whereData['title'] = ['like', '%'.$data['title'].'%'];
        }

//        模式一
//        获取数据
//        $news = model('News')->getNews();

//        模式二
        $this->getPageAndSize($data);

        $news = model('News')->getNewsByCondition($whereData, $this->from, $this->size);

//        获取满足条件的数据总数
       $total = model('News')->getNewsCountByCondition($whereData);

       $pageTotal = ceil($total/$this->size);

       $cats = config('cat.lists');
        return $this->fetch('', [
            'news' => $news,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,
            'cats' => $cats,
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'catid' => empty($data['catid']) ? 0 : $data['catid'],
            'title' => empty($data['title']) ? '' : $data['title'],
            'query' => $query
        ]);
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input('post.');

//            入库
            $id = null;

            try{
                $id = model('News')->add($data);
            } catch (\Exception $e) {
                $this->result('', 0, $e->getMessage());
            }

            if($id) {
                $this->result(['jump_url' => url('news/index')], 1, 'ok');
            } else {
                $this->result('', 0, '新增失败');
            }
        }
        
        return $this->fetch();
    }
}
