<?php

namespace app\home\common\model;

use think\Model;

class User extends Model
{
    protected $table = 'user_table';
    protected $pk = 'uid';

    public function userid()
    {
        return $this->hasOne('APP\model\home\link','user_id','uid');
    }

}
