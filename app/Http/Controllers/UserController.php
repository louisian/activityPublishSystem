<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/17
 * Time: 15:12
 */
namespace App\Http\Controllers;

use App\Model\UserModel;
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
        $um=UserModel::getUserByUsername($username);
//        var_dump($um);
        if($um){
            if(md5($password)==$um['password']){
                $request->session()->put('logged',$username);
                $request->session()->put('logged_uid',$um['uid']);
                return $this->apiResponse(200,'登录成功');
            }
        }
        return $this->apiResponse(401,'用户名或密码错误');
    }
    public function getLoggedUserInfo(Request $request){
        $username=$request->session()->get('logged');
        $um=UserModel::getUserByUsername($username);

        if($um){
            $umj=[];
            foreach ($um as $key=>$value){
                switch ($key){
                    case 'free_day':
                        $umj['freeDay']=explode(',',$value);
                        break;
                    case 'free_time':
                        $umj['freeTime']=explode(',',$value);
                        break;
                    case 'city_name':
                        $umj['cityName']=$value;
                        break;
                    case'password':
                        $umj['password']='';
                        break;
                    case'tag':
                        $tagList=explode(',',$value);
                        $umj['tag']=$value==null?[]:$tagList;
                        break;
                    default:
                        if($value==null){
                            $value='';
                        }
                        $umj[$key]=$value;
                }
            }
            return $this->apiResponse(200,'数据获取成功',$umj);
        }
        return $this->apiResponse(500,'系统错误');
    }
    public function postInfoEdit(Request $request){
        $userObj=$request->all();
        if(UserModel::updateUser($userObj)){
            return $this->apiResponse(200,'数据更新成功');
        }
        return $this->apiResponse(500,'系统错误');
    }
    public function postLogout(Request $request){
        $username=$request->session()->pull('logged');
//        var_dump($username);
        if($username){
            return $this->apiResponse(200,'登出成功');
        }
        return $this->apiResponse(400,'系统错误');
    }
    public function postRegister(Request $request){
        $userObj=$request->all();
        if(UserModel::existUser($userObj['username'])){
            return $this->apiResponse(401,'用户已存在');
        }
        if(UserModel::addUser($userObj)){
            return $this->apiResponse(200,'注册成功');
        }
        return $this->apiResponse(500,'系统错误');


    }
}