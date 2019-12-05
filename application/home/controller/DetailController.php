<?php

namespace app\home\controller;

use think\DB;
use think\Controller;
use think\Request;
use think\facade\Session;
use app\home\common\model\User;
use app\home\common\model\Link;

class DetailController extends Controller
{
    
    public function initialize()
    {
        //判断有无uname这个session，如果没有，跳转到登陆界面
        if(!Session('uname')){
            return $this->error('您没有登陆',url('/home/login/index'));
        }
    }
 
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */

    public function time(){


        return view('/detail/time');


    }

    
    public function index(Request $request)
    {   

        $date = $request->post('time');
        $times = date('Y-m',time());
        if($date=="" || $date == $times){
            $date = $times;           
            $datem = date('m',time());
            $dated = date('d',time());
        }else{
            $datem = substr($date,5);
            $dated = 31;
            //print($datem);die;
        }
       
        

        $uname = Session::get('uname');
        
        $case = new Connect();
        $con = $case ->case($times);

        
        // echo $dated;die;
        
        // $link = Link::where('stime','like',$date.'%')->group("left(stime,10)")->count();
        // $link = Link::query("SELECT count(stime),stime FROM `link_table` where  stime like '$time' group by left(stime,10)");
      
        //  try{
        //     $links = DB::connect($con)->table('link')->where('uname',$uname)->count();
        //  }catch(\Exception $e){
        //     return $this->error('本月没有数据');
        //     // $links = DB::table('link-2019-11')->where('uname',$uname)->count();
        //  }
        
        $i = 1;
        while($i<= $dated){
            if($i < 10){
                $s = $date.'-0'.$i;
            }else{
                $s = $date.'-'.$i;
            }
            $link = DB::connect($con)->table('link')->where('uname',$uname)->where('stime','like',$s.'%')->count();
            $batch = DB::connect($con)->table('batch-excel')->where('uname',$uname)->where('Stime','like',$s.'%')->sum('S_num');
            // dump($batch);die;
            $shu[]=$link+$batch;$i++;
            
        }
        $links = array_sum($shu);
        $str = implode(",", $shu);
       
        

        //print_r($links);die;
        return view('/detail/index',
        [
        'links'=>$links,
        'date'=>$date,
        'dated'=>$dated, 
        'datem'=>$datem,
        'str'=>$str
        ]); 
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
