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
    public function getAllActivityEnterUser(Request $request){
        $aid=$request->input('aid');
        $am=ActivityModel::getActivityDetail($aid);
        $cUid=$am['creatorUid'];
        $applyHeader=array();
        $applyHeaderProp=explode(',',$am['applyInfo']);
        $applyHeaderLabel=[
          'realname'=>'真实姓名',
          'phone'=>'手机号',
          'commit'=>'备注'
        ];
        foreach($applyHeaderProp as $key=> $value){
            $applyHeader[$key]['label']=$applyHeaderLabel[$value];
            $applyHeader[$key]['prop']=$value;
        }
        $key=count($applyHeader)+1;
        $applyHeader[$key]['label']='报名时间';
        $applyHeader[$key]['prop']='applyTime';
        $uid=$request->session()->get('logged_uid');
        if($cUid!=$uid){
            return $this->apiResponse(400,'您没有足够权限');
        }
        $aeml=ActivityEnterModel::getAllEnterUidByAid($aid);

        $applyInfoArray=array();
        foreach ($aeml as $key=> $aem){
//            $um=UserModel::getUserByUid($aem['uid']);
            $applyInfoArray[$key]=json_decode($aem['applyInfo'],true);
            $applyInfoArray[$key]['applyTime']=$aem['created_at'];
        }
        return $this->apiResponse(200,'数据获取成功',
            ['total'=>count($applyInfoArray),'data'=>$applyInfoArray,'header'=>$applyHeader]);
    }

}
