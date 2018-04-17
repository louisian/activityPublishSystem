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
        return response($content,$code)->header("Access-Control-Allow-Credentials", "true");;
    }
}
