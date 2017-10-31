<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/10/21
 * Time: 19:52
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{
    public $page = '';

    public $size = '';

    public $model = '';

    public $from = 0;

//    初始化前的方法
    public function _initialize()
    {
//        判断用户是否登录
        if(!$this->is_login()) {
            $this->redirect('app/admin/login/index');
        }
    }

    public function is_login()
    {
        $user = session(config('admin.session_user'), '', config('admin.session_user_scope'));

        if($user && $user->id) {
            return true;
        }

        return false;
    }

    /**
     * 获得分页
     */
    public function getPageAndSize($data)
    {
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }

    public function delete($id)
    {
        if(!intval($id)) {
            return $this->result('', 0, 'ID不合法');
        }

//        如果表和控制器文件名一样 news news
//        如果控制器和表明不一样
        $model = $this->model ? $this->model : request()->controller();
        try {
            $res = model($model)->save(['status' => -1], ['id' => $id]);
        } catch (\Exception $e) {
            return $this->result('', 0, $e->getMessage());
        }

        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'OK');
        }

        return $this->result('', 0, '删除失败');
    }

    public function status($id, $status)
    {
//        校验id和status 通过validate校验
//        通过id判断记录是否存在
//        model('News')->get($id);
        if(!intval($id)) {
            return $this->result('', 0, 'ID不合法');
        }

//        如果表和控制器文件名一样 news news
//        如果控制器和表明不一样
        $model = $this->model ? $this->model : request()->controller();
        try {
            $res = model($model)->save(['status' => $status], ['id' => $id]);
        } catch (\Exception $e) {
            return $this->result('', 0, $e->getMessage());
        }

        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'OK');
        }

        return $this->result('', 0, '删除失败');
    }
}