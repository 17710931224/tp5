<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


function link_log($user=null,$link=null,$msg=null)
    {
        //$msg = [2018-04-11 09:22:56]文件名：wxpay，第29行，[info]：日志信息
        $msg = '['.date("Y-m-d H:i:s").']'.'账号：'.$user.'==========原地址'.$link.'============'.'[res]：'.$msg;

        // 日志文件名：日期.txt
        $path = 'log'."/".'link-logs' .date("Ymd").'.log';
        
        file_put_contents($path, $msg.PHP_EOL,FILE_APPEND);
        
        
    }



    
    function ceshic($time=null){
        
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
        

    //   $sql = 'CREATE DATABASE LINK404';
        $sql = 
        
            "CREATE TABLE IF NOT EXISTS `link`(
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `User_id` int(11) NOT NULL,
            `Uname` char(50) NOT NULL, 
            `Ylink` varchar(255) NOT NULL,
            `Dlink` varchar(255) DEFAULT NULL,
            `Stime` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `linkid` (`Uname`) USING BTREE
            )
            ENGINE=MyISAM
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            CHECKSUM=0
            ROW_FORMAT=DYNAMIC
            DELAY_KEY_WRITE=0"
            ;
    
            Db::connect($res)->execute($sql);

        
            
    }

    function connect(){

        $time = date('Y-m');

        $con =([
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
        

    }