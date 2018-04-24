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
        $um->freeDay=$userObj['freeDay'];
        $um->freeTime=$userObj['freeTime'];
        $um->realname=$userObj['realname'];
        $um->city=$userObj['city'];
        $um->cityName=$userObj['cityName'];
        $um->tag=$userObj['tag'];
        $um->tagEnter=json_encode((object)array());
        $um->tagScore=json_encode((object)array());
        $um->save();
        self::updateTagEnterTagScoreByTidListUid(explode(',',$userObj['tag']),$um->uid,2);
        return true;
    }
    public static function updateUser($userObj){
        $um=UserModel::where('uid',$userObj['uid'])->first();
        self::updateTagEnterTagScoreByTidListUid(explode(',',$um->tag),$um->uid,-2);
        $um->username=$userObj['username'];
        $um->phone=$userObj['phone'];
        $um->freeDay=$userObj['freeDay'];
        $um->freeTime=$userObj['freeTime'];
        $um->realname=$userObj['realname'];
        $um->city=$userObj['city'];
        $um->cityName=$userObj['cityName'];
        $um->tag=$userObj['tag'];
        $um->save();
        self::updateTagEnterTagScoreByTidListUid(explode(',',$userObj['tag']),$um->uid,2);
        return true;

    }
    public static function getUserByUsername($username){
        $user=UserModel::where('username',$username)->first();
//        var_dump($user);
        if($user==null){
            return null;
        }
        return $user->toArray();
    }
    public static function getTagEnterByUid($uid){
        $te=UserModel::where('uid',$uid)->select('tagEnter')->first();
        if($te->tagEnter==null){
            return [];
        }
//        var_dump( json_decode($te,true));
        return json_decode($te->tagEnter,true);
    }
    public static function getTagScoreByUid($uid){
        $ts=UserModel::where('uid',$uid)->select('tagScore')->first();
        if($ts==null){
            return [];
        }
        return json_decode($ts->tagScore,true);;
    }
    public static function updateTagEnterTagScoreByTidListUid($tidList,$uid,$coefficient=1){//因为是写入，for持续写入性能问题
//        $tidList=explode(',',$tidString);
//        var_dump($uid);
        $teObject=self::getTagEnterByUid($uid);
        $tsObject=array();
//        $teObjectNew=array();
//        var_dump($teObject);
        $totalCount=0;
        foreach ($tidList as $value){
            if(array_key_exists($value,$teObject)){
                $teObject[$value]=$teObject[$value]+$coefficient;
            }else{
                $teObject[$value]=$coefficient;
            }
        }

        foreach ($teObject as $key=>$value){
            $totalCount+=$value;
        }
        var_dump($totalCount);
        foreach ($teObject as $key=>$value){
            $tsObject[$key]=$value*100/$totalCount;//计算每个tag的分数，执行推荐算法
        }

//        var_dump((object)$teObject);
        UserModel::where('uid',$uid)->update(['tagEnter'=>json_encode((object)$teObject),'tagScore'=>json_encode((object)$tsObject)]);
        return true;
    }

}