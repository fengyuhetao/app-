<?php

namespace app\common\model;

use think\Model;

class BaseModel extends Model {
    public function add($data)
    {
        if(!is_array($data)) {
            exception("传递数据不合法");
        }
        $this->allowField(true)->save($data);

        return $this->id;
    }
}