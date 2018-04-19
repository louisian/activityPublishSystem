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
}