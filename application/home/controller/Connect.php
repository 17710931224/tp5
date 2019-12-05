<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Connect extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function case($time=null)
    {
        
     
        $res =([
            // 数据库类型
            'type'        => 'mysql',
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => '127.0.0.1',
            // 数据库名
            'database'    => 'link'.$time,
            // 数据库用户名
            'username'    => 'root',
            // 数据库密码
            'password'    => '',
            // 数据库连接端口
            'hostport'    => '',
            // 数据库连接参数
            'params'      => [],
            // 数据库编码默认采用utf8
            'charset'     => 'utf8',
            // 数据库表前缀
            'prefix'      => '',
        
        ]);
            
        return $res;


    }

   
}
