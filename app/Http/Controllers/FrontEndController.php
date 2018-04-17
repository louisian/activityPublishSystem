<?php
/**
 * Created by PhpStorm.
 * User: louyu
 * Date: 2018/4/17
 * Time: 14:40
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
class FrontEndController extends Controller{
    public function showFrontPage(){
        return view('frontend.index');
    }
}