<?php

namespace App\Http\Controllers\Manage\Cms;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Forms extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //取出频道
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        $this->page_data['channel_list'] = $channels_data;
        $pageContent = view('judicial.manage.cms.formsAdd',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }

        //判断是否有重名的
        $id = DB::table('cms_forms')->select('id')->where('title',$inputs['title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['title'].'的表单']);
        }
        //处理文件上传
        $file = $request->file('file');
        if(is_null($file) || !$file->isValid()){
            $file_path = '';
        }
        else{
            $destPath = realpath(public_path('uploads/files'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('FILE_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $file_path = '';
            }
            else{
                $file_path = URL::to('/').'/uploads/files/'.$filename;
            }
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'title'=> $inputs['title'],
            'disabled'=> (isset($inputs['disabled']) && $inputs['disabled']=='no') ? 'no' : 'yes',
            'file'=> $file_path,
            'channel_id'=> keys_decrypt($inputs['channel_id']),
            'description'=> $inputs['description'],
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('cms_forms')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出频道
            $channels_data = array();
            $channels = DB::table('cms_channel')->get();
            foreach($channels as $channel){
                $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
            }
            //取出数据
            $forms_data = array();
            $forms = DB::table('cms_forms')->get();
            foreach($forms as $key=> $form){
                $forms_data[$key]['key'] = keys_encrypt($form->id);
                $forms_data[$key]['title'] = $form->title;
                $forms_data[$key]['disabled'] = $form->disabled;
                $forms_data[$key]['channel_id'] = keys_encrypt($form->channel_id);
                $forms_data[$key]['file'] = $form->file;
                $forms_data[$key]['create_date'] = $form->create_date;
            }
            //返回到前段界面
            $this->page_data['channel_list'] = $channels_data;
            $this->page_data['form_list'] = $forms_data;
            $pageContent = view('judicial.manage.cms.formsList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $form_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出频道
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        $this->page_data['channel_list'] = $channels_data;
        //取出详情
        $forms = DB::table('cms_forms')->where('id',$id)->first();
        if(is_null($forms)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $form_detail['key'] = keys_encrypt($forms->id);
        $form_detail['title'] = $forms->title;
        $form_detail['disabled'] = $forms->disabled;
        $form_detail['channel_id'] = keys_encrypt($forms->channel_id);
        $form_detail['file'] = empty($forms->file) ? 'none' : $forms->file;
        $form_detail['description'] = $forms->description;
        $form_detail['create_date'] = $forms->create_date;

        //页面中显示
        $this->page_data['form_detail'] = $form_detail;
        $pageContent = view('judicial.manage.cms.formsDetail',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * 修改标签页面
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $form_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出频道
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        $this->page_data['channel_list'] = $channels_data;
        //取出详情
        $forms = DB::table('cms_forms')->where('id',$id)->first();
        if(is_null($forms)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        $form_detail['key'] = keys_encrypt($forms->id);
        $form_detail['title'] = $forms->title;
        $form_detail['disabled'] = $forms->disabled;
        $form_detail['channel_id'] = keys_encrypt($forms->channel_id);
        $form_detail['file'] = empty($forms->file) ? 'none' : $forms->file;
        $form_detail['description'] = $forms->description;
        $form_detail['create_date'] = $forms->create_date;

        //页面中显示
        $this->page_data['form_detail'] = $form_detail;
        $pageContent = view('judicial.manage.cms.formsEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        if(empty($inputs['title'])){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'标题不能为空！']);
        }

        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM cms_flinks WHERE `title` = "'.$inputs['title'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在标题为：'.$inputs['fi_title'].'的推荐链接']);
        }
        //处理文件上传
        $file = $request->file('file');
        if(is_null($file) || !$file->isValid()){
            $file_path = '';
        }
        else{
            $destPath = realpath(public_path('uploads/files'));
            if(!file_exists($destPath)){
                mkdir($destPath, 0755, true);
            }
            $extension = $file->getClientOriginalExtension();
            $filename = gen_unique_code('FILE_').'.'.$extension;
            if(!$file->move($destPath,$filename)){
                $file_path = '';
            }
            else{
                $file_path = URL::to('/').'/uploads/files/'.$filename;
            }
        }
        //执行插入数据操作
        $save_data = array(
            'title'=> $inputs['title'],
            'disabled'=> (isset($inputs['disabled']) && $inputs['disabled']=='no') ? 'no' : 'yes',
            'file'=> $file_path,
            'channel_id'=> keys_decrypt($inputs['channel_id']),
            'description'=> $inputs['description'],
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('cms_forms')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $channels_data = array();
        $channels = DB::table('cms_channel')->get();
        foreach($channels as $channel){
            $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
        }
        //取出数据
        $forms_data = array();
        $forms = DB::table('cms_forms')->get();
        foreach($forms as $key=> $form){
            $forms_data[$key]['key'] = keys_encrypt($form->id);
            $forms_data[$key]['title'] = $form->title;
            $forms_data[$key]['disabled'] = $form->disabled;
            $forms_data[$key]['channel_id'] = keys_encrypt($form->channel_id);
            $forms_data[$key]['file'] = $form->file;
            $forms_data[$key]['create_date'] = $form->create_date;
        }
        //返回到前段界面
        $this->page_data['channel_list'] = $channels_data;
        $this->page_data['form_list'] = $forms_data;
        $pageContent = view('judicial.manage.cms.formsList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('cms_forms')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $channels_data = array();
            $channels = DB::table('cms_channel')->get();
            foreach($channels as $channel){
                $channels_data[keys_encrypt($channel->channel_id)] = $channel->channel_title;
            }
            //取出数据
            $forms_data = array();
            $forms = DB::table('cms_forms')->get();
            foreach($forms as $key=> $form){
                $forms_data[$key]['key'] = keys_encrypt($form->id);
                $forms_data[$key]['title'] = $form->title;
                $forms_data[$key]['disabled'] = $form->disabled;
                $forms_data[$key]['channel_id'] = keys_encrypt($form->channel_id);
                $forms_data[$key]['file'] = $form->file;
                $forms_data[$key]['create_date'] = $form->create_date;
            }
            //返回到前段界面
            $this->page_data['channel_list'] = $channels_data;
            $this->page_data['form_list'] = $forms_data;
            $pageContent = view('judicial.manage.cms.formsList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}
