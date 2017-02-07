<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Manage\User\Manager;


class Login extends Controller
{

    private $page_date;

    public function __construct()
    {
        $this->page_date['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/')
        );
    }

    public function index()
    {
        return view('judicial.manage.login',$this->page_date);
    }

    public function doLogin(Request $request)
    {
        //处理请求参数并验证用户是否存在
        $inputs = $request->input();
        $user = Manager::where('login_name',$inputs['loginName'])->select('login_name','password','disabled')->first();
        $userInfo = $user['attributes'];
        if(is_null($user)){
            json_response(['status'=>'faild', 'msg'=>'用户名或密码错误！']);
        }
        elseif($userInfo['disabled'] == 'yes'){
            json_response(['status'=>'faild', 'msg'=>'账号被禁用，请联系管理员']);
        }

        //验证密码
        if(!password_verify($inputs['passWord'],$userInfo['password'])){
            json_response(['status'=>'faild', 'msg'=>'用户名或密码错误！']);
        }
        else{
            json_response(['status'=>'succ', 'msg'=>'登陆成功！']);
        }

    }

    /**
     * ajax请求统一入口
     * @param null $action
     */
    public function ajaxRequest($action = null){
        if(!method_exists($this,'_ajax_'.$action)){
            json_response(['status'=>'faild', 'msg'=>'不存在的方法']);
        }
        $_action = '_ajax_'.$action;
        $this->$_action($_REQUEST['loginName']);
        exit;
    }

    private function _ajax_checkUser($loginName){
        $user = Manager::where('login_name',$loginName)->select('login_name','disabled')->first();
        if(is_null($user)){
            json_response(['status'=>'faild', 'msg'=>'用户名不存在！']);

        }
        elseif($user['attributes']['disabled'] == 'yes'){
            json_response(['status'=>'faild', 'msg'=>'账号被禁用，请联系管理员']);
        }
        else{
            json_response(['status'=>'succ']);
        }
    }

}