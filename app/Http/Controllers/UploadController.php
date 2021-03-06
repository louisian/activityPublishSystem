<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/14
 * Time: 12:59
 */

namespace App\Http\Controllers;
use App\Model\ActivityModel;
use Storage;
use Illuminate\Http\Request;

class UploadController extends Controller{
    private function uploadPic(Request $request){
        if($request->isMethod('post')){
            $file=$request->file('file');
            if($file->isValid()){
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();
                // 上传文件
                $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
//                $im = imagecreatefromjpeg($realPath);
//                imageinterlace($im, 1);
//                imagejpeg($im, $realPath, 100);
//                imagedestroy($im);
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
//                var_dump($bool);
                if($bool){
                    return $filename;
                }
            }
        }
        return false;
    }
    public function posterUploader(Request $request){
        $aid=$request->input('aid');
        if(!$aid){
            return $this->apiResponse(400,'没有活动ID,上传失败');
        }
       if($filename=$this->uploadPic($request)) {
           // write to database
           if (ActivityModel::updatePosterPathByAid($filename,$aid)){
               return $this->apiResponse(200, '');
           }
       }
           return $this->apiResponse(400,'系统错误');


    }
    public function mdPicUploader(Request $request){
        $filename=$this->uploadPic($request);
        if($filename){
            return $this->apiResponse(200,'',[
                'url'=>'/uploads/'.$filename,
            ]);
        }else{
            return $this->apiResponse(500,'系统错误');
        }

    }
}