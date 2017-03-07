<?php

namespace App\Http\Controllers\Manage\Service;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Config;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Certificate extends Controller
{
    public function __construct()
    {
        $this->page_data['thisPageName'] = '证书持有人管理';
    }

    public function index($page = 1)
    {
        //加载列表数据
        $certificate_list = array();
        $pages = '';
        $count = DB::table('service_certificate')->count();
        $count_page = ($count > 30)? ceil($count/30)  : 1;
        $offset = $page > $count_page ? 0 : ($page - 1) * 30;
        $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
        if(count($certificates) > 0){
            //格式化数据
            foreach($certificates as $certificate){
                $certificate_list[] = array(
                    'key' => keys_encrypt($certificate->id),
                    'name'=> $certificate->name,
                    'citizen_code'=> $certificate->citizen_code,
                    'certi_code'=> $certificate->certi_code,
                    'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                    'phone'=> $certificate->phone,
                    'last_status'=> $certificate->last_status,
                    'create_date'=> $certificate->create_date,
                );
            }
            $pages = array(
                'count' => $count,
                'count_page' => $count_page,
                'now_page' => $page,
                'type' => 'certificate',
            );
        }
        $this->page_data['pages'] = $pages;
        $this->page_data['certificate_list'] = $certificate_list;
        $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function create(Request $request)
    {
        $pageContent = view('judicial.manage.service.certificateAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function show(Request $request)
    {
        $certificate_detail = array();
        $id = keys_decrypt($request->input('key'));
        $certi = DB::table('service_certificate')->where('id', $id)->first();
        if(is_null($certi)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        else{
            $certificate_detail = array(
                'key'=> keys_encrypt($certi->id),
                'name'=> $certi->name,
                'citizen_code'=> $certi->citizen_code,
                'certi_code'=> $certi->certi_code,
                'exam_date'=> $certi->exam_date,
                'phone'=> $certi->phone,
                'certificate_date'=> $certi->certificate_date,
                'create_date'=> $certi->create_date,
            );
            //处理注册记录
            if(empty($certi->register_log)){
                $certificate_detail['register_log'] = 'none';
            }
            else{
                $register_loge = json_decode($certi->register_loge, true);
            }
            //处理短信记录
            if(empty($certi->message_log)){
                $certificate_detail['message_log'] = 'none';
            }
            else{
                $message_log = json_decode($certi->message_log, true);
            }
        }
        //页面中显示
        $this->page_data['certificate_detail'] = $certificate_detail;
        $pageContent = view('judicial.manage.service.certificateDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function edit(Request $request)
    {

    }

    public function doEdit(Request $request)
    {

    }

    public function doDelete(Request $request)
    {
        $id = keys_decrypt($request->input('key'));
        $row = DB::table('service_certificate')->where('id',$id)->delete();
        if($row > 0){
            //删除成功后加载列表
            $certificate_list = array();
            $pages = '';
            $count = DB::table('service_certificate')->count();
            $count_page = ($count > 30)? ceil($count/30)  : 1;
            $offset = $page > $count_page ? 0 : ($page - 1) * 30;
            $certificates = DB::table('service_certificate')->orderBy('create_date', 'desc')->skip($offset)->take(30)->get();
            if(count($certificates) > 0){
                //格式化数据
                foreach($certificates as $certificate){
                    $certificate_list[] = array(
                        'key' => keys_encrypt($certificate->id),
                        'name'=> $certificate->name,
                        'citizen_code'=> $certificate->citizen_code,
                        'certi_code'=> $certificate->certi_code,
                        'certificate_date'=> date('Y-m-d', strtotime($certificate->certificate_date)),
                        'phone'=> $certificate->phone,
                        'last_status'=> $certificate->last_status,
                        'create_date'=> $certificate->create_date,
                    );
                }
                $pages = array(
                    'count' => $count,
                    'count_page' => $count_page,
                    'now_page' => $page,
                    'type' => 'certificate',
                );
            }
            $this->page_data['pages'] = $pages;
            $this->page_data['certificate_list'] = $certificate_list;
            $pageContent = view('judicial.manage.service.certificateList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}