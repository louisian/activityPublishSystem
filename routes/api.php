<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware'=>'api'],function () {
    Route::group(['middleware'=>'checkLogin'],function (){
        Route::get('/user/login/info','UserController@getLoggedUserInfo');
        Route::post('/user/login/info/edit','UserController@postInfoEdit');
        Route::post('/tag/add','TagController@postAddTag');

    });
    Route::post('/activity/publish/uploader/poster',"UploadController@posterUploader");
    Route::post('/activity/publish/uploader/md-pic',"UploadController@mdPicUploader");
    Route::get('/user/login/status','UserController@getLoginStatus');
    Route::post('/user/login','UserController@postLogin');
    Route::post('/user/logout','UserController@postLogout');
    Route::post('/user/register','UserController@postRegister');
    Route::get('/tag/search','TagController@searchTag');
    Route::get('/tag/get/list','TagController@getTagsByTidList');


});

