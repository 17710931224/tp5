<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
use think\facade\Cache;

class UsersController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        $condition=[];
        if(!empty($_GET['sex'])){
            $condition[] = ['sex','=',$_GET['sex']];
        }
        if(!empty($_GET['uname'])){
            $condition[] = ['uname','like',"%{$_GET['uname']}%"];
        }

    //var_dump($condition);

      $users=  User::where($condition)->paginate(5)->appends($_GET);
        return view('user/index',['users'=>$users]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        echo 1231321321;
        //return view('user/create');
        
        // Cache::store('file')->set('name','12313',3600);

        // dump(Cache::store('file')->get('name'));
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
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
        

        $data['upwd'] = md5($data['upwd']);
        $data['regtime'] = date('Y-m-d H:i:s');

       // var_dump($data['regtime']);

        try{
        User::create($data , true);
        }catch(\Exception $e){
            return $this->error('提交失败');
        }return $this->success('提交成功','/admin/user/index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {   

        
    }
    
    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
       $user = User::get($id);    
       
       return view('/user/edit',['user'=>$user]); 

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
          $data = $request->post();
          try{
          User::update($data,['uid'=>$id],true);
          }catch(\Exception $e){
            return $this->error('修改失败',"/admin/user/{$id}/edit"); 
          }return $this->success('修改成功','/admin/user/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $row = User::destroy($id);
        if($row){
            return $this->success('删除成功','/admin/user/index');
        }else {return $this->error('删除失败');}
    }
}
