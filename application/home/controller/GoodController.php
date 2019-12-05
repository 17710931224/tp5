<?php

namespace app\home\controller;

use think\DB;
use think\Controller;
use think\Request;
use think\facade\Env; 
use think\facade\Cache;
use think\facade\App;
use think\facade\Session;
use app\home\common\model\User;
class GoodController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function case()
    {
        
        // $sql = 'CREATE DATABASE LINK404';
        // Db::execute($sql);

        $time = date('Y-m');    
    
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
        // dump($res);die;

    }
   


    public function index($row)
    {
        
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
