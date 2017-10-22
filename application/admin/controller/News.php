<?php
namespace app\admin\controller;


class News extends Base
{
    public function index()
    {
//        获取数据
        $news = model('News')->getNews();
        return $this->fetch('', [
            'news' => $news
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
