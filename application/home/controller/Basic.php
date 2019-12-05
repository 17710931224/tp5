<?php

namespace app\home\controller;

use think\DB;
use think\Controller;
use think\Request;
use think\facade\Session;
use think\facade\Debug;
use app\home\common\model\User;
use app\home\common\model\Tmp;

class Basic extends Controller
{
    
     
     
    public function initialize()
    {
        //判断有无uname这个session，如果没有，跳转到登陆界面
        if(!Session('uname')){
            return $this->error('您没有登陆',url('/home/login/index'));
        }
    }


    //单条请求判断余额
    public function balance()
    {   
        $user = $ylink['Uname'] = Session::get('uname');

        $balance = User::where('uname',$user)->value('balance');
        
        if($balance <= 0){
            $this->error('余额不足','/');
        }

        return $user;
    }


    //批量请求判断余额
    public function batchbal($row_num=null)
    {   
        $user = $ylink['Uname'] = Session::get('uname');

        $batchbal = User::where('uname',$user)->value('balance');
        
        if($batchbal < $row_num){
            $this->error('余额不足','/');
        }

        return $user;
    }
    

        //百度短网址 Api接口
    function linkapi($value=null,$key=null)
    {   

        

        $host = 'https://dwz.cn';
        $path = '/admin/v2/create';
        $url = $host . $path;
        $method = 'POST';
        $content_type = 'application/json';
        
        // TODO: 设置Token
        $token = '5dd608b8a78fa529e2ade68d8161bbdc';
        
        // TODO：设置待注册长网址
        $bodys = array('Url'=>$value, 'TermOfValidity'=>'1-year');
        
        // 配置headers 
        $headers = array('Content-Type:'.$content_type, 'Token:'.$token);
        
        // 创建连接
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($bodys));
        
        // 发送请求
        $response = curl_exec($curl);
        curl_close($curl);

        $resArr = json_decode($response, true);
        // dump($resArr);die;
        // if($resArr['Code'] == 0){
        //     Tmp::where('id',$key)->update([
        //         'Status' => 1,
        //         'Ulink'  => $resArr['ShortUrl']
        //     ]);
        // }else{
        //     Tmp::where('id',$key)->update([
        //         'Status' => 2,
        //         'Err'  => $resArr['ErrMsg']
        //     ]);
        // }
        

        return $resArr;
        
    }




    //并发请求接口
    public function batchapi($uname=null,$row_num=null)
    {   

        
        $token = '5dd608b8a78fa529e2ade68d8161bbdc';
        $host = 'https://dwz.cn';
        $path = '/admin/v2/create';
        $timeout = 50;
        $url = $host . $path;
        $method = 'POST';
        $content_type = 'application/json';
        $headers = array('Content-Type:'.$content_type, 'Token:'.$token);

        $redis = new \Redis();

        $redis->connect('127.0.0.1',6379);

    //并发请求
        $curl_Arr=[];
       
        for($i=0;$i<$row_num;$i++){
        //开启curl连接
        $curl_Arr[$i]=curl_init($url);
        //CURLOPT_RETURNTRANSFER 设置为1表示稍后执行的curl_exec函数的返回是URL的返回字符串，而不是把返回字符串定向到标准输出并返回TRUE；
        
            // curl_setopt($curl_Arr[$i], CURLOPT_CONNECTTIMEOUT,3);
            curl_setopt($curl_Arr[$i], CURLOPT_TIMEOUT, $timeout);  
            curl_setopt($curl_Arr[$i], CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl_Arr[$i], CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_Arr[$i], CURLOPT_FAILONERROR, false);
            curl_setopt($curl_Arr[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_Arr[$i], CURLOPT_HEADER, false);
            curl_setopt($curl_Arr[$i], CURLOPT_POST, true);
            $value = $redis->lpop($uname.'list');
            $bodys = array('Url'=>$value, 'TermOfValidity'=>'1-year');
            curl_setopt($curl_Arr[$i], CURLOPT_POSTFIELDS, json_encode($bodys));

        }
        
        //创建批处理cURL句柄
        $mh = curl_multi_init();
        
        

        foreach($curl_Arr as $k => $ch){
        //curl句柄入栈增加
        curl_multi_add_handle($mh,$ch);
        }
        $active = null;
        while(count($curl_Arr)>0){
        //发起curl_multi请求
            
            @curl_multi_exec($mh,$active);
     
            $nums  = 0;
            foreach($curl_Arr as $k => $ch){
            //获取句柄的返回值
                
                if($result[$k]= curl_multi_getcontent($ch)){
                //输出结果
                $resArr = json_decode($result[$k],true);
                $resArr['Uname'] = $uname;
                $resArr['Stime'] = date('Y-m-d H:i:s');
                    // if($resArr['Code'] == 0){
                    //     $resArr['Status'] = 1;
                    //     // DB::name('batch-link')->insert($resArr);
               
                        
                    // }else{
                    //     $resArr['Status'] = 2;
                    //     // DB::name('batch-link')->insert($resArr);
                    // }
                

                $redis->rpush($uname."lists",$result[$k]);
                
                ob_flush();
                //把被释放的数据发送到浏览器
                flush();
                //关闭该句柄
                curl_multi_remove_handle($mh,$ch);
                unset($curl_Arr[$k]);
                }
            }
        }
        //关闭ouput_buffering机制
        ob_end_flush();
        //关闭"curl_mulit"句柄
        curl_multi_close($mh);
        
        
        
        
        
        
    }

   




    
    
    
}
