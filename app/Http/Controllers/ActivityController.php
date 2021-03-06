<?php

namespace App\Http\Controllers;

use App\Model\ActivityEnterModel;
use App\Model\ActivityModel;
use App\Model\TagModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ActivityController extends Controller
{
    public function postAddActivityBasic(Request $request){
        $bActivityInfo=$request->all();
        $edit=false;
        if($request->input('aid')){
            $edit=true;
        }
        $uid=$request->session()->get('logged_uid');
        $bActivityInfo['tag']=$this->convStringListToIntList(explode(',',$bActivityInfo['tag']));
        $aid=ActivityModel::addBasicActivityInfo($bActivityInfo,$uid,$edit);
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
    function page_array($count,$page,$array,$order=0){
        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
        $start=($page-1)*$count; #计算每次分页的开始位置
        if($order==1){
            $array=array_reverse($array);
        }
        $totals=count($array);
        $countpage=ceil($totals/$count); #计算总页面数
//        $pagedata=array();
        $pagedata=array_slice($array,$start,$count);
        return ['list'=>$pagedata,'page'=>$page,'total'=>$totals,'pageSize'=>$count];  #返回查询数据
    }
    private function calcTimeScore($startTime,$stopTime,$freeTime){
        $startTimeCarbon=new Carbon($startTime, 'Asia/Shanghai');
        $stopTimeCarbon=new Carbon($stopTime,'Asia/Shanghai');
        $diffTime=$stopTimeCarbon->diffInHours($startTimeCarbon);
        $total=0;
        $startWeekDay=$startTimeCarbon->dayOfWeek;
        $startHour=$startTimeCarbon->hour;
//        $stopWeekDay=$stopTimeCarbon->dayOfWeek;
        $tempWeek=$startWeekDay;
        $tempHour=$startHour;

        for($i=0;$i<$diffTime-1;$i++){
            ++$tempHour;
            if($tempHour==24){
                ++$tempWeek;
                $tempHour=0;
                if($tempWeek==7){
                    $tempWeek=0;
                }
            }
//            var_dump($tempWeek*24+$tempHour,$freeTime[$tempWeek*24+$tempHour]);
            $total+=$freeTime[$tempWeek*24+$tempHour];
        }
//        var_dump('week'.$startWeekDay);
//        var_dump('total'.$total);
//        var_dump('difftime'.$diffTime);
        return $total/$diffTime;

    }
    public function getActivityEdit(Request $request){
        $aid=$request->input('aid');
        $uid=$request->session()->get('logged_uid');
        $cUid=ActivityModel::getCreatorUidByAid($aid);
        $am=ActivityModel::getActivityDetail($aid);
        if($cUid!=$uid){
            return $this->apiResponse(401,'您没有足够权限');
        }
        return $this->apiResponse(200,'数据获取成功',$am);

    }
    public function getAllActivity(Request $request){
        $pageSize=$request->input('pageSize');
        $page=$request->input('page');
        if(empty($pageSize)){
            $pageSize=12;
        }
        if(empty($page)){
            $page=1;
        }
        $uid=-1;
        $tsl=array();
        if($request->session()->has('logged_uid')){
            $uid=$request->session()->get('logged_uid');
            $tsl=UserModel::getTagScoreByUid($uid);
            $tidList=array_keys(UserModel::getTagEnterByUid($uid));
//            var_dump(array_keys(UserModel::getTagEnterByUid($uid)));
            $aml=ActivityModel::getHasTagActivityList($tidList,$uid);
            $naidList=array();
            $freeTime=UserModel::getFreeTimeByUid($uid);
//            var_dump($freeTime);
            foreach ($aml as $key=>$value){
                $aml[$key]['rScore']=0;
                array_push($naidList,$value['aid']);
                foreach (json_decode($value['tag'],true) as $tTag){
                    if(array_key_exists($tTag,$tsl)){
                        $aml[$key]['rScore']+=$tsl[$tTag];
                    }
                }
            }
            $amln=ActivityModel::getActivityListNotAid($naidList,$uid);
            array_merge($aml,$amln);
            foreach ($aml as $key=>$value){
                if(!array_key_exists('rScore',$aml[$key])){
                    $aml[$key]['rScore']=0;
                }
                $tScore=$this->calcTimeScore($value['activityStartTime'],$value['activityStopTime'],$freeTime)*50;
//                var_dump($tScore);
                $aml[$key]['rScore']+=$tScore;
            }
        }else{
            $aml=ActivityModel::getActivityList();
        }
        $aidl=ActivityEnterModel::getAidByUid($uid);
        foreach ($aml as $key=>$value){
                $aml[$key]['applied']=in_array($value['aid'],$aidl);
                if(!array_key_exists('rScore',$aml[$key])){
                    $aml[$key]['rScore']=0;
                }
        }
        $aml=$this->arraySort($aml,'rScore');

        return $this->apiResponse(200,'数据获取成功',$this->page_array($pageSize,$page,$aml));
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
            if($value=='commit'){
                $am_ret['applyInfo'][$value]=$am['commitTitle'];
                continue;
            }
            $am_ret['applyInfo'][$value]=$um[$value];
        }
        $am_ret['commitTitle']=$am['commitTitle'];
        return $this->apiResponse(200,'数据获取成功',$am_ret);
    }

}
