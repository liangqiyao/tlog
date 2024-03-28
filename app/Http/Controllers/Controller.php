<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Validator;
 

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function safeTickect(Request $request){
        \Log::info("post safeTickect:".$request->getClientIp(), $request->all());
        $data   = ['status'=>1, 'number'=>'', 'count'=>0, 'msg'=>''];
        $count  = 0;

        $validate = Validator::make($request->all(), [
          'num' =>  'required|integer|between:1,15',
          'min' =>  'required|integer|max:5000',
          'max' =>  'required|integer|max:5000'
        ]);
        if($validate->fails())
        {
            $data['status'] = 0;
            $data['msg']    = "参数异常:".$validate->messages()->first();
            return $data;
        }

        $lucknumber  = $request->input("lucknumber");    
        $num  = $request->input("num");
        $min  = $request->input("min");
        $max  = $request->input("max");

        if(!empty($lucknumber)){
            $oldNumberArr = explode(',', $lucknumber);
            //数组校验
            $oldNumberArr = $this->checkNumberArr($oldNumberArr);


            $this->delNumber($oldNumberArr);
        }

        $number = $this->getRandNumber($num, $min, $max);
        $data['number'] = implode(',', $number);
        $count  = Redis::get('safe:count');
        $data['count']  = $count;

        if(empty($number)){
            $data['status'] = 0;
            $data['msg']    = '所选范围号码不够分配，请重选';
        } 

        if(count($number) < $num){
            $data['msg']    = '所选范围号码只剩下'.count($number).'个了';
        }
        
        
        \Log::info('number'.$data['number']);
        return $data;
    }

//数组内数字类型转换
function checkNumberArr($arr){
    $new_arr = [];
    foreach($arr as $val){
        $new_arr[] = intval($val);
    }
    return $new_arr;
}

function delNumber($oldNumberArr){
    $data = $this->getNumber();

    $diff = array_diff($data, $oldNumberArr);
    sort($diff);

    Redis::set('safe:numbers', json_encode($diff));
    Redis::set('safe:count', count($diff));    
}

#设置缓存 ， 放到redis
function saveNumber($arr){
    $old_arr = $this->getNumber();
    $new_arr = array_merge($old_arr, $arr);
    $user = Redis::set('safe:numbers', json_encode($new_arr));

    Redis::set('safe:count', count($new_arr));
}


#读取缓存[1,2,3,4]
function getNumber(){
    $json =   Redis::get('safe:numbers');
    return $json ? json_decode($json, true) : [];
}

# 获取指定数量随机数
function getRandNumber($num = 10, $min = 200, $max=2000){

    $count = $max - $min;
    $arr = [];
    $arr_exist = $this->getNumber();
    foreach($arr_exist as  $val){
        if($val >= $min && $val < $max){
            $arr[$val] = 1;
        }
    }

    $c = ($count+1-$num);

    while(1) {
        if(!$c){
            break;
        }
        $k = rand($min, $max);
        if (empty($arr[$k])){
            $arr[$k] = 1;
        }
        if( count($arr) >= $c){
            break;
        }
    }
    ksort($arr);

    $ret = [];

    for($j=$min ; $j <= $max; $j++){

        if(empty($arr[$j])){
           
            $ret[] = $j;
        }
    }
    
    $this->saveNumber($ret);

    return $ret;

}



}
