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
        $data   = ['number'=>'', 'count'=>0, 'msg'=>'参数错误'];
        $count  = 0;

        $validate = Validator::make($request->all(), [
          'num' =>  'required|integer|between:1,15',
          'min' =>  'required|integer',
          'max' =>  'required|integer'
        ]);
        if($validate->fails())
        {
            $data['req'=>$request->all()];
            return $data;
        }


        $num  = $request->input("num");
        $min  = $request->input("min");
        $max  = $request->input("max");
        $number = getRandNumber($num, $min, $max);
        $count  = Redis::get('safe:count');

        $data   = ['number'=>$number, 'count'=>$count];
        return $data;
    }






#设置缓存 ， 放到redis
function saveNumber($arr){
    $old_arr = getNumber();
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
    $arr_exist = getNumber();
    foreach($arr_exist as $key => $val){
        if($key >= $min && $key < $max){
            $arr[$key] = 1;
        }
    }



    while(1) {
        $k = rand($min, $max);
        if (empty($arr[$k])){
            $arr[$k] = 1;
        } else {
            $arr[$k]++;
        }

        if( count($arr) > ($count-$num)){
            break;
        }
    }
    ksort($arr);

    $ret = [];


    for($j=$min;$j<$max;$j++){

        if(empty($arr[$j])){
            $ret[] = $j;
        }
    }
    

    saveNumber($ret);

    return $ret;

}
}
