<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;


class CateController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $cates = Cate::orderRaw("concat(path,pid,',')")->paginate(10)->appends($_GET);
        return view('cate/index',['cates'=>$cates]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($id=0)
    {
        $cates = Cate::orderRaw("concat(path,cid,',')")->select();
           
         
        return view('cate/create', ['cates'=>$cates,'id'=>$id]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request -> post();

        // var_dump($data);die;
        $pid = $data['pid'];
        if($pid == 0){
            $data['path'] = "0,";
        }else{
            $data['path'] = Cate::get($pid)->path."$pid,";
        }
        try{
            Cate::create($data, true);
            }catch(\Exception $e){
                return $this->error('添加失败');
            }return $this->success('添加成功','/admin/cate/index');
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
        $cate = Cate::get($id);

        return view('cate/edit',['cate'=>$cate]);
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
        $data = $request -> post();
        try{
            Cate::update($data,['cid'=>$id],true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }return $this->success('修改成功','/admin/cate/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $cate = Cate::where('pid','=',$id)->find();

        if($cate){
            return $this->error('有子类不能删除');
        }


        $row = Cate::destroy($id);

        if($row){
            return $this->success('删除成功','admin/cate/index');
        }return $this->error('删除失败');
    }
}
