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




class BatchController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
       
        return view('batch\index');
    }
    
   
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function upload(Request $request)
    {
        
        
        $time = date("Y-m");     
   
        $sc = new Basic();


        $uname = Session::get('uname');

        //上传excel文件
        $file = request()->file('excel');
        //将文件保存到public/uploads目录下面

        $info = $file->validate(['size'=>1048576,'ext'=>'xlsx'])->move( './uploads');


        require '../extend/PHPExcel/PHPExcel.php';
        if($info){
            //获取上传到后台的文件名
            $fileName = $info->getSaveName();
            //获取文件路径
            $filePath = Env::get('root_path').'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$fileName;
            //获取文件后缀
            $suffix = $info->getExtension();
            //判断哪种类型
            if($suffix=="xlsx"){
                $reader = \PHPExcel_IOFactory::createReader('Excel2007');
            }else{
                $reader = \PHPExcel_IOFactory::createReader('Excel5');
            }
        }else{
            $this->error('文件过大或格式不正确导致上传失败-_-!');
        }





        //载入excel文件
        $excel = $reader->load("$filePath",$encode = 'utf-8');
        //读取第一张表
        $sheet = $excel->getSheet(0);
        //获取总行数
        $row_num = $sheet->getHighestRow();
        //获取总列数
        $col_num = $sheet->getHighestColumn();


        if($row_num >= 300){
            $this->error('转换数量过多,可能出现卡顿');
        }


        //判断余额
        $sc->batchbal($row_num);
        //dump($row_num);die;

        

        $data = []; //数组形式获取表格数据
        // $link['Stime'] = date('Y-m-d H:i:s');
        for ($i = 1; $i <= $row_num; $i ++) {
            $data[$i]  = $sheet->getCell("A".$i)->getValue();
            
            $link[$i]['Ylink'] = $data[$i];
            $link[$i]['Uname'] = $uname;
            $utime = $link[$i]['Stime'] = date('Y-m-d H:i:s');
            
            // DB::name('link-tmp')->insert([
            //     'Ylink'=>$data[$i],
            //     'Status'=>0,
            //     'Stime' => date('Y-m-d H:i:s'),
            // ]);
        }
        
        $case = new Connect();
        $con = $case ->case($time);

        $test = DB::connect($con)->name('link-tmp')->insertAll($link);
        $testres = Db::connect($con)->name('link-tmp')->getLastInsID();
        $del = array();
        for ($i=0; $i<$test; $i++) { 
            $del[] = (int)$testres++;

        }
       
        
      
        //存入Redis
            $redis = new \Redis();

            $redis->connect('127.0.0.1',6379);
            $redis->del($uname."list");
            $redis->del($uname."path");
            foreach($data as $k=>$v){
                $redis->rpush($uname."list",$v);
            
            // echo $k.'成功'.date('Y-m-d H:i:s');
             }
            $redis->rpush($uname."path",$filePath);
        
          
          
            // $resArr = $sc->batchapi($uname,$row_num);
            
            // dump($resArr);die;
            


    //      return view('batch/down',['fname'=>$fname,'time'=>$time]);
        
        return view('batch/down',['row'=>$row_num]);
        
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function down($row)
    {
       
        $sc = new Basic();
        $uname = Session::get('uname');
        // dump($row);die;
        
        //请求接口转换
        $resArr = $sc->batchapi($uname,$row);
        // $redis->del($uname.'lists');

        return view('batch\file',['row'=>$row]);
    }


    public function file($row)
    {   

        require '../extend/PHPExcel/PHPExcel.php';

        $uname = Session::get('uname');

        $date = date('Y-m');
        $case = new Connect();
        $con = $case ->case($date);

        $rand = md5(time());
        $fname = $rand.$uname.'.'.'xlsx';

        $time = date("Y-m-d");
        $path = "excel/$time";

        

        $redis = new \Redis();

        $redis->connect('127.0.0.1',6379);

        $filePath = $redis->lpop($uname.'path');

        //写入excel
        $objPHPExcel = \PHPExcel_IOFactory::load($filePath);                     
        $objSheet = $objPHPExcel->getActiveSheet();        //选取当前的sheet对象

        if(!is_dir($path)){
        $file = mkdir($path,true);
        }

        $arr = [];
        $num = 0;
        for($i=1;$i<=$row;$i++){
        $res = $redis->lpop($uname.'lists');
        $resArr[$i] = json_decode($res,true);  
       // dump($resArr);die;
        $objSheet->setCellValue("A$i",$resArr[$i]["LongUrl"]);
        
        if($resArr[$i]["Code"] == 0){     
            $objSheet->setCellValue("B$i",$resArr[$i]["ShortUrl"]);
            // $arr[$i] = $resArr;
            // $arr[$i]['Status'] = 1;
            // $arr[$i]['Stime'] = $utime;
            // $arr[$i]['Uname'] = $uname;
            $num++;
        }else{
            $objSheet->setCellValue("B$i","转换失败");
            // $ss = DB::name('link-tmp')->field('id',true)->where('Ylink',$resArr["LongUrl"])->find();
            // $ss['Err']=$resArr['ErrMsg'];
            // //dump($ss);die;
            // DB::name('link-error')->insert($ss);
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');   //设定写入excel的类型
        $obj = $objWriter->save(App::getRootPath()."/public"."/".$path."/".$fname);

        }
        

        
        
        // DB::name('link-tmp')->delete($del);
        //dump($arr);die;
        // DB::name('batch-link')->insertAll($arr);
       
        //扣款
        User::where('uname',$uname)->setDec('balance',$num);

        
        //dump($del);die;
        $id = DB::connect($con)->table('batch-excel')->insert([
            'Stime'=> date('Y-m-d H:i:s'),
            'Uname' => $uname,
            'User_id'=> User::where('Uname',"$uname")->value('uid'),
            'Uploadfile' => $fname,
            'Convertfile' => $path.'/'.$fname,
            'U_num' => $row,
            'S_num' => $num,
        ]);

        $this->redirect("/".$path."/".$fname);   

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    
}
