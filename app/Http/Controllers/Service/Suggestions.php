<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Session;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Suggestions extends Controller
{
    public $page_data = array();

    public function __construct()
    {
        $this->page_data['url'] = array(
            'loginUrl' => URL::route('loginUrl'),
            'userLoginUrl' => URL::route('userLoginUrl'),
            'webUrl' => URL::to('/'),
            'ajaxUrl' => URL::to('/'),
            'login' => URL::to('manage'),
            'loadContent' => URL::to('manage/loadContent'),
            'user'=>URL::to('user')
        );
        $loginStatus = $this->checkLoginStatus();
        if(!!$loginStatus)
            $this->page_data['is_signin'] = 'yes';
        //拿出政务公开
        $c_data = DB::table('cms_channel')->where('zwgk', 'yes')->orderBy('sort', 'desc')->get();
        $zwgk_list = 'none';
        if(count($c_data) > 0){
            $zwgk_list = array();
            foreach($c_data as $_c_date){
                $zwgk_list[] = array(
                    'key'=> $_c_date->channel_id,
                    'channel_title'=> $_c_date->channel_title,
                );
            }
        }
        $this->page_data['type_list'] = ['opinion'=>'意见','suggest'=>'建议','complaint'=>'投诉','other'=>'其他'];
        $this->page_data['zwgk_list'] = $zwgk_list;
        $this->page_data['channel_list'] = $this->get_left_list();
        $this->get_left_sub();
    }

    public function index($page = 1)
    {
        $record_list = array();
        //加载列表数据
        $pages = '';
        $count = DB::table('service_suggestions')->count();
        $count_page = ($count > 12)? ceil($count/12)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 12;
        $records = DB::table('service_suggestions')->orderBy('create_date', 'desc')->skip($offset)->take(12)->get();
        if(count($records) > 0){
            //格式化数据
            foreach($records as $record){
                $record_list[] = array(
                    'record_code'=> $record->record_code,
                    'type'=> $record->type,
                    'title'=> $record->title,
                    'create_date'=> date('Y-m-d H:i', strtotime($record->create_date)),
                    'answer_date'=> $record->status=='waiting' ? '待回复' : date('Y-m-d H:i', strtotime($record->answer_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'suggestions/list',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['record_list'] = $record_list;
        return view('judicial.web.service.suggestionList', $this->page_data);
    }

    public function search(Request $request){
        $keywords = $request->input('keywords');
        $sql = 'SELECT * FROM `service_suggestions` WHERE `record_code` LIKE "%'.$keywords.'%" OR `title` LIKE "%'.$keywords.'%"';
        $res = DB::select($sql);
        $record_list = array();
        if($res && count($res)>0){
            $count = count($res);
            $count_page = ($count > 16)? ceil($count/16)  : 1;
            //格式化数据
            foreach($res as $record){
                $record_list[] = array(
                    'record_code'=> $record->record_code,
                    'type'=> $record->type,
                    'title'=> $record->title,
                    'create_date'=> date('Y-m-d H:i', strtotime($record->create_date)),
                    'answer_date'=> $record->status=='waiting' ? '待回复' : date('Y-m-d H:i', strtotime($record->answer_date)),
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => 1,
                'type' => 'lawyer',
            );
        }
        else{
            $pages = array(
                'count' => 0,
                'count_page' => 1,
                'now_page' => 1,
                'type' => 'lawyer',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['record_list'] = $record_list;
        return view('judicial.web.service.suggestionList', $this->page_data);
    }

    public function add()
    {
        return view('judicial.web.service.addSuggestion', $this->page_data);
    }

    public function doAdd(Request $request)
    {

        $inputs = $request->input();
        $this->_checkInput($inputs);
        $save_data = array(
            'record_code'=> $this->get_record_code('S'),
            'name'=> $inputs['name'],
            'cell_phone'=> $inputs['cell_phone'],
            'email'=> $inputs['email'],
            'member_code'=> $this->checkLoginStatus() ? $this->checkLoginStatus() : '',
            'type'=> $inputs['type'],
            'title'=> $inputs['title'],
            'content'=> $inputs['content'],
            'create_date'=> date('Y-m-d H:i:s', time())
        );
        $id = DB::table('service_suggestions')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'提交失败！']);
        }
        else{
            json_response(['status'=>'succ','type'=>'notice', 'res'=>'提交成功！']);
        }
    }

    public function show($record_code){
        $record_detail = array();
        $record = DB::table('service_suggestions')->where('record_code', $record_code)->first();
        if(is_null($record) || count($record)<1){
            return view('errors.404');
        }
        else{
            $record_detail = array(
                'record_code'=> $record->record_code,
                'type'=> $record->type,
                'create_date'=> date('Y-m-d H:i', strtotime($record->create_date)),
                'title'=> $record->title,
                'content'=> trim($record->content),
                'answer_content'=> trim($record->answer_content),
                'answer_date'=> date('Y-m-d H:i', strtotime($record->answer_date)),
            );
        }
        $this->page_data['record_detail'] = $record_detail;
        return view('judicial.web.service.suggestionDetail', $this->page_data);
    }

    private function _checkInput($inputs){
        if(!isset($inputs['name']) || trim($inputs['name'])==='' || strlen($inputs['name']) > 20){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'姓名应为长度20以内的字符串']);
        }
        if(!isset($inputs['email']) || trim($inputs['email'])==='' || !preg_email($inputs['email'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写合法的邮箱！']);
        }
        if(!isset($inputs['cell_phone']) || trim($inputs['cell_phone'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'联系电话不能为空！']);
        }
        if(!preg_phone($inputs['cell_phone']) && !preg_phone2($inputs['cell_phone'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请填写正确的联系电话！（13800000000 或 0398-1234567 或 010-12345678）']);
        }
        if(!isset($inputs['type']) || trim($inputs['type'])===''){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'请选择留言分类！']);
        }
        if(!isset($inputs['title']) || trim($inputs['title'])==='' || strlen($inputs['title']) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'主题应为长度200以内的字符串']);
        }
        if(!isset($inputs['content']) || trim($inputs['content'])==='' || strlen($inputs['content']) > 200){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'内容应为长度200以内的字符串']);
        }
        return true;
    }
}