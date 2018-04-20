<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/14
 * Time: 22:43
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class TagModel extends Model{
    protected $primaryKey='tid';
    protected $table='tag';
    public static function addTag($tagObj,$uid){
        $tm=new TagModel();
        $tm->name=$tagObj['name'];
        $tm->description=$tagObj['description'];
        $tm->creatorUid=$uid;
        $tm->save();
        return true;
    }
    public static function existTag($tagName){
        if(TagModel::where('name',$tagName)->count()>0){
            return true;
        }
        return false;
    }
    public static function getTagByQuery($query){
//        var_dump($query);
        $tml=TagModel::where('name','like',"%{$query}%")->select('name','tid')->get();    //->select('tid','name','description');
//        var_dump($tml);
        if($tml==null){
            return [];
        }
        return $tml->toArray();
    }
    public static function getTagByTid($tid){
        $tm=TagModel::where('tid',$tid)->first();
        if($tm==null){
            return null;
        }
        return $tm->toArray();

    }
}