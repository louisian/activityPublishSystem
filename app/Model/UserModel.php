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
        if(userModel::where('username',$username)->count()>0){
            return true;
        }
        return false;
    }
    public function addUser($username,$password,$phone,$realname,$tag){
        if(userModel::existUser($username)){
            return false;
        }
        $password_md5=md5($password);
        $um=new userModel();
        $um->username=$username;
        $um->password=$password_md5;
        $um->phone=$phone;
        $um->realname=$realname;
        $um->tag=$tag;
        $um->save();
        return true;
    }
    public function getUserByUsername($username){
        $user=userModel::where('username',$username)->first();
        if($user==null){
            return null;
        }
        return $user->toArray();
    }

}