<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/14
 * Time: 22:43
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
class ActivityEnterModel extends Model{
    protected $primaryKey='eid';
    protected $table='activity_enter';
    public static function addActivityEnter($aid,$uid,$applyInfo){
        $aem=new ActivityEnterModel();
        $aem->aid=$aid;
        $aem->uid=$uid;
        $aem->applyInfo=$applyInfo;
        $aem->save();
        return true;
    }
    public static function existEnterByAidUid($aid,$uid){
        if(ActivityEnterModel::where([
            ['aid','=',$aid],
            ['uid','=',$uid]
        ])->count()){
            return true;
        }else{
            return false;
        }


    }
    public static function getEidByUid($uid){
        $aem=ActivityEnterModel::where('uid',$uid)->select('eid')->get();
        if($aem==null){
            return [];
        }
        $aeml=$aem->toArray();
        $aeml_ret=array();
        foreach ($aeml as $key=>$value){
            $aeml_ret[$key]=$value['eid'];
        }
        return $aeml_ret;
    }
    public static function getAidByUid($uid){
        $aem=ActivityEnterModel::where('uid',$uid)->select('aid')->get();
        if($aem==null){
            return [];
        }
        $aeml=$aem->toArray();
        $aeml_ret=array();
        foreach ($aeml as $key=>$value){
            $aeml_ret[$key]=$value['aid'];
        }
        return $aeml_ret;
    }
    public static function getAllEnterUidByAid($aid){
        $aeml=ActivityEnterModel::where('aid',$aid)->get();

        return $aeml->toArray();
    }
}