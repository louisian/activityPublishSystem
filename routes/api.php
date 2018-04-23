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
        Route::post('/activity/info/basic','ActivityController@postAddActivityBasic');
        Route::post('/activity/info/detail','ActivityController@postAddActivityDetail');
        Route::get('/activity/apply/info','ActivityController@getApplyInfo');
        Route::post('/activity/enter/add','ActivityEnterController@postActivityEnter');
        Route::get('/activity/info/applied','ActivityController@getAppliedActivity');
        Route::get('/activity/info/create','ActivityController@getCreateActivity');

    });
    Route::post('/activity/publish/uploader/poster',"UploadController@posterUploader");
    Route::post('/activity/publish/uploader/md-pic',"UploadController@mdPicUploader");
    Route::get('/user/login/status','UserController@getLoginStatus');
    Route::post('/user/login','UserController@postLogin');
    Route::post('/user/logout','UserController@postLogout');
    Route::post('/user/register','UserController@postRegister');
    Route::get('/tag/search','TagController@searchTag');
    Route::get('/tag/get/list','TagController@getTagsByTidList');
    Route::get('/activity/info/all','ActivityController@getAllActivity');
    Route::get('/activity/info/detail','ActivityController@getActivityDetail');

//    Route::get('/user/te','UserController@postTagEnter');//todo temp must be delete

});

