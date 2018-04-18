<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/14
 * Time: 22:43
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class UserModel extends Model{
    protected $primaryKey='uid';
    protected $table='user';
    public static function existUser($username){
        if(UserModel::where('username',$username)->count()>0){
            return true;
        }
        return false;
    }
    public static function addUser($userObj){
        if(UserModel::existUser($userObj['username'])){
            return false;
        }
//        $freeDay=json_encode(explode(',',$freeDay));
        $password_md5=md5($userObj['password']);
        $um=new userModel();
        $um->username=$userObj['username'];
        $um->password=$password_md5;
        $um->phone=$userObj['phone'];
        $um->free_day=$userObj['freeDay'];
        $um->free_time=$userObj['freeTime'];
        $um->realname=$userObj['realname'];
        $um->city=$userObj['city'];
        $um->city_name=$userObj['cityName'];
        $um->tag=$userObj['tag'];
        $um->save();
        return true;
    }
    public static function updateUser($userObj){
        $um=UserModel::where('uid',$userObj['uid'])->first();
        $um->username=$userObj['username'];
        $um->phone=$userObj['phone'];
        $um->free_day=$userObj['freeDay'];
        $um->free_time=$userObj['freeTime'];
        $um->realname=$userObj['realname'];
        $um->city=$userObj['city'];
        $um->city_name=$userObj['cityName'];
        $um->tag=$userObj['tag'];
        $um->save();
        return true;

    }
    public static function getUserByUsername($username){
        $user=UserModel::where('username',$username)->first();
        if($user==null){
            return null;
        }
        return $user->toArray();
    }

}