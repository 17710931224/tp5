<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\DB;
use think\facade\Session;
use app\home\common\model\User;
use app\home\common\model\Link;


class IndexController extends Controller
{   


    // public function initialize()
    // {
    //     //判断有无uname这个session，如果没有，跳转到登陆界面
    //     if(!Session('uname')){
    //         return $this->error('您没有登陆',url('/home/login/index'));
    //     }
    // }

    

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   

        // $ss = 404; 
        // $sc = new GoodController();
        // $sc->app($ss);

        return view('/index/index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
      
       
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save()
    {   
        
        $ylink  =  $_POST;

        $date = date("Y-m");
        
        $case = new Connect();
        $res = $case ->case($date);
        
        //验证登录和余额
        $sc = new Basic();

        $user = $sc->balance();

        
        
        $ylink['Stime'] = date('Y-m-d H:i:s');
           
        $ylink['Uname'] = $user;

        

        
        // $key = DB::table('link-tmp')->insertGetId($ylink);
        // dump($id);die;
        //连接Api

        

        $resArr = $sc->linkapi($ylink['Ylink']);
        //dump($resArr);die;
        
        
        $ylink['User_id'] = User::where('uname',$user)->value('uid');
        
        $resA = json_encode($resArr,true);
        //日志
        link_log($user,$ylink['Ylink'],$resA);
        
        if($resArr['Code'] == 0){
           
            $ylink['Dlink'] = $resArr['ShortUrl']; 
            User::where('uname',"$user")->setDec('balance',1);
            
            
           
            ceshic($date);
        
            $id = DB::connect($res)->table('link')->insertGetId($ylink);
            //   dump($id);die;
            return $this->redirect('/home/index/link/'.$id);
        }else{
            return $this->error($resArr['ErrMsg']);
        }

        
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {   
        
        $date = date("Y-m");

        $case = new Connect();
        $res = $case ->case($date);
        
        

        $user = DB::connect($res)->table('link')->where('id',$id)->find();
        // dump($user);die;
        return view('index/link',['user'=>$user]);
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
