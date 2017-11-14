<?php
/**
 * Created by PhpStorm.
 * User: HT
 * Date: 2017/11/06
 * Time: 20:30
 */

namespace app\api\controller\v1;


use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use think\Exception;

//class Upvote extends AuthBase
class Upvote
{
//    新闻点赞功能
    public function save()
    {
        $id = input('post.id', 0, 'intval');
        if(empty($id)) {
            return api_return([],config('code.error'), 'id不存在', [], 404);
        }
//        判断文章是否存在
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id,
        ];

//        查询库里是否存在该记录
        $userNews = model('UserNews')->get($data);
        if($userNews) {
            return api_return([], config('code.error'), '已经点赞，不能再次点赞', 401);
        }

        try {
            $userNewsId = model('UserNews')->add($data);
            if($userNewsId) {
                model('News')->where(['id' => $id])->setInc('upvote_count');
                return api_return([], config('code.success'), 'OK', 202);
            } else {
                return api_return([], config('code.error'), '点赞失败，请稍后再试', 500);
            }
        } catch (\Exception $e) {
            return api_return([], config('code.error'), '点赞失败，请稍后再试', 500);
        }
    }

    /**
     * 取消点赞
     */
    public function delete()
    {
        $id = input('delete.id', 0, 'intval');
        if(empty($id)) {
            return api_return([],config('code.error'), 'id不存在', [], 404);
        }
//        判断文章是否存在
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id,
        ];

//        查询库里是否存在该记录
        $userNews = model('UserNews')->get($data);
        if(!$userNews) {
            return api_return([], config('code.error'), '未点赞，无法取消', 401);
        }

        try {
            $userNewsId = model('UserNews')->where($data)->delete();
            if($userNewsId) {
                model('News')->where(['id' => $id])->setDec('upvote_count');
                return api_return([], config('code.success'), 'OK', 202);
            } else {
                return api_return([], config('code.error'), '取消失败，请稍后再试', 500);
            }
        } catch (\Exception $e) {
            return api_return([], config('code.error'), '取消失败，请稍后再试', 500);
        }
    }
}