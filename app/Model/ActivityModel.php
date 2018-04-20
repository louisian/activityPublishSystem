<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/14
 * Time: 22:43
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class ActivityModel extends Model{
    protected $primaryKey='aid';
    protected $table='activity';
    public static function updatePosterPathByAid($path,$aid){
        ActivityModel::where('aid',$aid)->update(['poster'=>$path]);
        return true;
    }
    public static function addBasicActivityInfo($bActivityInfo,$uid){
        $am=new ActivityModel();
        $am->name=$bActivityInfo['name'];
        $am->tag=json_encode(explode(',',$bActivityInfo['tag']));
        $am->cityCode=$bActivityInfo['city'];
        $am->cityName=$bActivityInfo['cityName'];
        $am->address=$bActivityInfo['address'];
        $am->detailAddress=$bActivityInfo['detailAddress'];
        $am->applyStartTime=date('Y-m-d 0:0:0',$bActivityInfo['applyStartTime']);
        $am->applyStopTime=date('Y-m-d 23:59:59',$bActivityInfo['applyStopTime']);
        $am->activityStartTime=date('Y-m-d H:i:s',$bActivityInfo['activityStartTime']);
        $am->activityStopTime=date('Y-m-d H:i:s',$bActivityInfo['activityStopTime']);
        $am->creatorUid=$uid;
        $am->save();
        return $am->aid;

    }
    public static function updateDetailActivityInfoByAid($dActivityInfo,$aid){
        $am=ActivityModel::where('aid',$aid)->first();
        $am->description=$dActivityInfo['description'];
        $am->descriptionSource=$dActivityInfo['descriptionSource'];
        $am->applyInfo=$dActivityInfo['applyInfo'];
        $am->commitTitle=$dActivityInfo['commitTitle'];
        $am->save();
        return true;
    }
    public static function getActivityDetail($aid){
        $am=ActivityModel::where('aid',$aid)->first();
        if($am==null){
            return null;
        }
        $am->poster='/uploads/'.$am->poster;
        return $am->toArray();

    }
    public static function getActivityList($selectListArray=['aid','name','cityName','activityStartTime','poster']){
        $am=ActivityModel::all();
        if($am==null){
            return null;
        }

        $aml=$am->toArray();
        $amlr=array();
        foreach ($aml as $key=>$value){
            foreach ($selectListArray as $sl){
                switch ($sl){
                    case'poster':
                        $amlr[$key][$sl]='/uploads/'.$value[$sl];
                        break;
                    default:
                        $amlr[$key][$sl]=$value[$sl];

                }

            }
        }
        return $amlr;
    }
}