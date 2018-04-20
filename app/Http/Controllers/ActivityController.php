<?php

namespace App\Http\Controllers;

use App\Model\ActivityModel;
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
        $admin=0;
        if($request->session()->has('logged_uid')){
            if($request->session()->get('logged_uid')==$am['creatorUid']){
                $admin=1;
            }
        }
        return $this->apiResponse(200,'数据获取成功',['admin'=>$admin,'activityInfo'=>$am]);

    }
    public function getAllActivity(Request $request){
        $aml=ActivityModel::getActivityList();
        return $this->apiResponse(200,'数据获取成功',$aml);
    }

}
