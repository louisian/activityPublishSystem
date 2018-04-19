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
        $tml=[];
        foreach ($tidList as $key=>$value){
            $tm=TagModel::getTagByTid($value);
            $tml[$key]['name']=$tm['name'];
            $tml[$key]['tid']=$tm['tid'];
        }
        return $this->apiResponse(200,'数据获取成功',$tml);


    }
}