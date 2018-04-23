<?php

namespace App\Http\Controllers;

use App\Model\ActivityEnterModel;
use App\Model\ActivityModel;
use App\Model\UserModel;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function postAddActivityBasic(Request $request){
        $bActivityInfo=$request->all();
        $uid=$request->session()->get('logged_uid');
        $aid=ActivityModel::addBasicActivityInfo($bActivityInfo,$uid);
        if($aid){
            return $this->apiResponse(200,'活动数据添加成功',['aid'=>$aid]);
        }
        return $this->apiResponse(400,'系统错误');
    }
    public function postAddActivityDetail(Request $request){
        $dActivityInfo=$request->all();
        if(ActivityModel::updateDetailActivityInfoByAid($dActivityInfo,$dActivityInfo['aid'])){
            return $this->apiResponse(200,'数据更新成功');
        }
        return $this->apiResponse(400,'系统错误');
    }
    public function getActivityDetail(Request $request){
        $aid=$request->input('aid');
        if(!$aid){
            return $this->apiResponse(400,'参数错误，无法获取详细信息');
        }
        $am=ActivityModel::getActivityDetail($aid);
        $am['applied']=false;
        $admin=0;
        if($request->session()->has('logged_uid')){
            $uid=$request->session()->get('logged_uid');
            if($uid==$am['creatorUid']){
                $admin=1;
            }
            $am['applied']=ActivityEnterModel::existEnterByAidUid($aid,$uid);
        }
        return $this->apiResponse(200,'数据获取成功',['admin'=>$admin,'activityInfo'=>$am]);

    }
    public function getAllActivity(Request $request){
        $aml=ActivityModel::getActivityList();
        $aml_ret=array();
        $uid=$request->session()->get('logged_uid');
        $aidl=ActivityEnterModel::getAidByUid($uid);
//        var_dump($aidl);
        foreach ($aml as $key=>$value){
            if($value['creatorUid']!=$uid){
                $aml_ret[$key]=$value;
                $aml_ret[$key]['applied']=in_array($value['aid'],$aidl);
            }

        }
        return $this->apiResponse(200,'数据获取成功',$aml_ret);
    }
    public function getPosterActivity(Request $request){

    }
    public function getCreateActivity(Request $request){
        $uid=$request->session()->get('logged_uid');
        $aml=ActivityModel::getCreateActivityListByUid($uid);
        return $this->apiResponse(200,'数据获取成功',$aml);
    }
    public function getAppliedActivity(Request $request){
        $uid=$request->session()->get('logged_uid');
        $aidList=ActivityEnterModel::getAidByUid($uid);
        $aml=ActivityModel::getAppliedActivityListByAidList($aidList);
        return $this->apiResponse(200,'数据获取成功',$aml);
    }

    public function getApplyInfo(Request $request){
        $aid=$request->input('aid');
        $username=$request->session()->get('logged');

        if(!$aid){
            return $this->apiResponse(400,'参数不正确');
        }
        $am=ActivityModel::getApplyInfoByAid($aid);
        $um=UserModel::getUserByUsername($username);
        $am_ret=array();
//        var_dump($am);
        foreach ($am['applyInfo'] as $value){
            $am_ret['applyInfo'][$value]=$um[$value];
        }
        $am_ret['commitTitle']=$am['commitTitle'];
        return $this->apiResponse(200,'数据获取成功',$am_ret);
    }

}
