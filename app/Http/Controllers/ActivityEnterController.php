<?php

namespace App\Http\Controllers;

use App\Model\ActivityEnterModel;
use App\Model\ActivityModel;
use App\Model\UserModel;
use Illuminate\Http\Request;

class ActivityEnterController extends Controller
{
    //
    public function postActivityEnter(Request $request){
        $aid=$request->input('aid');
        $applyInfo=$request->input('applyInfo');
        $uid=$request->session()->get('logged_uid');
        if($aid&&$applyInfo&&$uid){
            if(ActivityEnterModel::existEnterByAidUid($aid,$uid)){
                return $this->apiResponse(400,'您已经报名这个活动，请勿重复报名');
            }
            $tidList=ActivityModel::getTidStringByAid($aid);
            if(UserModel::updateTagEnterTagScoreByTidListUid($tidList,$uid)){
                ActivityEnterModel::addActivityEnter($aid,$uid,$applyInfo);
                return $this->apiResponse(200,"报名成功");
            }
        }
        return $this->apiResponse(400,'系统错误');
    }

}
