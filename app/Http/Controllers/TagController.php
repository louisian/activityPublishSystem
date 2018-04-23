<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/19
 * Time: 14:05
 */
namespace App\Http\Controllers;

use App\Model\TagModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
class TagController extends Controller{
    public function postAddTag(Request $request){
        $tagObj=$request->all();
        if(TagModel::existTag($tagObj['name'])){
            return $this->apiResponse(400,'标签已存在');
        }
        $uid=$request->session()->get('logged_uid');
        if(TagModel::addTag($tagObj,$uid)){
            return $this->apiResponse(200,'标签添加成功');
        }
        return $this->apiResponse(400,'系统错误');
    }
    public function searchTag(Request $request){
        $query=$request->input('query');
//        var_dump($query);
        if(!$query){
            return $this->apiResponse(400,'没有关键词');
        }
        $tml=TagModel::getTagByQuery($query);
//        var_dump($tml);
        return $this->apiResponse(200,'获取成功',$tml);
    }
    public function getTagsByTidList(Request $request){
        $tidList=explode(',',$request->input('tidList'));
//        var_dump($tidList);
        $tidList=$this->convStringListToIntList($tidList);
        $tml=TagModel::getTagByTidList($tidList);
//        var_dump($tml);
        $tml_ret=array();
        foreach ($tml as $key=>$value){
            $tml_ret[$key]['name']=$value['name'];
            $tml_ret[$key]['tid']=$value['tid'];
        }
        return $this->apiResponse(200,'数据获取成功',$tml_ret);


    }
}