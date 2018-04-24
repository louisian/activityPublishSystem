<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function apiResponse($code,$msg,$data=array()){
        $content = json_encode(array("code"=>$code,"message"=>$msg,"data"=>$data));
//        var_dump($content);
        return response($content,$code)->header("Access-Control-Allow-Credentials", "true");;
    }
    public function convStringListToIntList($stringList){
        $intList=array();
        foreach ($stringList as $key=>$value){
            $intList[$key]=intval($value);
        }
        return $intList;
    }
    function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}
