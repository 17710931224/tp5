<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\facade\Session;
use app\home\common\model\User;

class LoginController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        
        return view('login/login');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        $user = $request->post('uname');

        $pwd = $request->post('upwd');

        $id = User::where('uname',"$user")->value('uid');
        
        
        $upwd = User::where('uid',$id)->value('upwd');
    
        if($id == ""){
            return $this->error('用户不存在','/home/login/index');
        }
        
        $users = User::get($id);
        if($pwd == $upwd ){
            Session::set('uname',$user);
            
            return view('/default',['users'=>$users]);
        }else{
            return $this->error('密码错误');
        }

    }

    public function logout()
    {
        session(null);//退出清空session
        return $this->success('退出成功',url('/home/login/index'));//跳转到登录页面
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
