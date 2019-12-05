<?php

namespace app\home\controller;

use think\DB;
use think\Controller;
use think\Request;
use think\facade\Session;
use app\home\common\model\User;
class RegisterController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return view('/register/register');
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

        $time = date('Ym');
        
        ceshic($time);die;


        $data = $request->post();
        
        if(strlen($data['tel']) != 11){
           return $this->error('电话错误');        
        };
        
        $uid = $data['uname'];

        $sql = User::where('uname',$uid)->find();
       
     

        if($sql != "" ){
            return $this->error('账号已存在');
        };

        
        if(empty($data['upwd']) || empty($data['reupwd'])){
            return $this->error('密码不能为空');
        } 

        if($data['upwd'] !== $data['reupwd']){
            return $this->error('两次密码输入不一致');
        }

        if(empty($data['tel'])){
            return $this->error('电话不能为空');
        }

        $data['regtime'] = date('Y-m-d H:i:s');

       // var_dump($data['regtime']);

    //    $log = DB::table('users_table'.$time)->insert($data);
    //     dump($log);die;

    

        try{
            User::create($data , true);
        }catch(\Exception $e){
            return $this->error('提交失败');
        }return $this->success('提交成功','/home/login/index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function user()
    {   
        $uname = Session::get('uname');
        if($uname == ""){
            return view('login/login');
        }else{
            $sql = User::where('uname',$uname)->find();
            return view('user/index',['sql'=>$sql]);
        }
      
        
    }


    public function time(){


        return view('/user/time');


    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function history(Request $request)
    {
        $uname = Session::get('uname');
        $time  = $request->post('time');
        
        if($time == ""){
            $time = date('Y-m');
        }
        
        $case = new Connect();
        $res = $case ->case($time);

        try{
            $data = DB::connect($res)->name('batch-excel')->where('uname',$uname)->field('Stime,Convertfile,S_num')->order('id desc')->paginate(15);
        }catch(\Exception $e){
            return $this->error('本月没有数据');
                // $links = DB::table('link-2019-11')->where('uname',$uname)->count();
        }
        
        // $data = DB::connect($res)->name('batch-excel')->where('uname',$uname)->field('Stime,Convertfile,S_num')->order('id desc')->paginate(15);
        // dump($data);die;
        // $data = array_reverse($date['items']);
        // dump($date);die;
        return view('user/history',['data'=>$data]);
      
        
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    
    
     public function update()
    {
        return view('user/update');
    }



    public function passw(Request $request)
    {
        $post = $request->post();
        $uname = Session::get('uname');
        $sql = User::where('uname',$uname)->value('upwd');
        
        if(($post['opwd']) != $sql ){
            return $this->error('原密码不正确');
        }

        if( empty($post['pwd']) || empty($post['upwd'])){
            return $this->error('密码不能为空');
        } 

        if($post['pwd'] !== $post['upwd']){
            return $this->error('两次密码输入不一致');
        }
        
        $pwd = $post['pwd'];
        try{
            User::where('uname',$uname)->update(['upwd'=>$pwd]);
        }catch(\Exception $e){
            return $this->error('提交失败');
        }return $this->success('提交成功','/home/login/index');
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
