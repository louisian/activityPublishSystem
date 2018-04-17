<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/17
 * Time: 15:12
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
class UserController extends Controller{
    public function getLoginStatus(Request $request){
        if($request->session()->has('logged')){
            return $this->apiResponse(200,'获取成功',[]);//todo fetch user data
        }
        return $this->apiResponse(400,'没有登录');
    }
    public function postLogin(Request $request){
        $username=$request->input('username');
        $password=$request->input('password');
        if($username=='lyq'&&$password=='1234567'){
            $request->session()->put('logged','lyq');
            return $this->apiResponse(200,'登录成功');
        }
        return $this->apiResponse(401,'用户名或密码错误');
    }
    public function postLogout(Request $request){
        $username=$request->session()->pull('logged');
//        var_dump($username);
        if($username){
            return $this->apiResponse(200,'登出成功');
        }
        return $this->apiResponse(400,'系统错误');
    }
}