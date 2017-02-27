<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Users extends Controller

{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type_list = array();
        $types = DB::table('user_type')->get();
        //取出用户类型
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = $type->type_name;
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userAdd',$this->page_data)->render();
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
        //判断是否有重名的
        $type_id = keys_decrypt($inputs['user_type']);
        if($type_id == 1){
            $id = DB::table('user_members')->select('id')->where('login_name',$inputs['login_name'])->get();
        }
        else{
            $id = DB::table('user_manager')->select('id')->where('login_name',$inputs['login_name'])->get();
        }
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['login_name'].'的用户']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        if($type_id == 1){
            //获取用户提交的信息并格式化
            $member_code = gen_unique_code("MEM_");
            $saveMembers = array(
                'member_code' => $member_code,
                'login_name' => $inputs['login_name'],
                'password' => password_hash($inputs['password'],PASSWORD_BCRYPT),
                'cell_phone' => $inputs['cell_phone'],
                'email'=> $inputs['email'],
                'user_type'=> 1,
                'office_id'=> keys_decrypt($inputs['user_office']),
                'role_id'=> keys_decrypt($inputs['user_role']),
                'member_level'=> 1,
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'create_date'=> $now,
            );
            $saveMemberInfo = array(
                'member_code' => $member_code,
                'citizen_name' => $inputs['nickname'],
                'create_date' => $now,
                'update_date' => $now
            );
            //以事物的方式储存账号
            DB::beginTransaction();
            $id = DB::table('user_members')->insertGetId($saveMembers);
            if($id===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
            if($iid===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            DB::commit();
        }
        else{
            $save_data = array(
                'manager_code'=> gen_unique_code('MEM_'),
                'password'=> password_hash($inputs['password'],PASSWORD_BCRYPT),
                'login_name'=> $inputs['login_name'],
                'cell_phone'=> $inputs['cell_phone'],
                'nickname'=> $inputs['nickname'],
                'email'=> $inputs['email'],
                'role_id'=> keys_decrypt($inputs['user_role']),
                'office_id'=> keys_decrypt($inputs['user_office']),
                'type_id'=> $type_id,
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'create_date'=> $now,
                'update_date'=> $now
            );
            $id = DB::table('user_manager')->insertGetId($save_data);
            if($id===false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
        }
        //插入数据成功取出管理员
        $user_list = array();
        $managers = DB::table('user_manager')->get();
        foreach($managers as $key=> $managers){
            $user_list[$key]['key'] = $managers->manager_code;
            $user_list[$key]['login_name'] = $managers->login_name;
            $user_list[$key]['type_id'] = $managers->type_id;
            $user_list[$key]['nickname'] = $managers->nickname;
            $user_list[$key]['cell_phone'] = $managers->cell_phone;
            $user_list[$key]['disabled'] = $managers->disabled;
            $user_list[$key]['create_date'] = $managers->create_date;
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        //取出用户类型
        $user_type = DB::table('user_type')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //返回到前段界面
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $inputs = $request->input();
        $code = $inputs['key'];
        //取出用户类型
        $type_list = array();
        $types = DB::table('user_type')->get();
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = $type->type_name;
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        if($inputs['type'] == 1){
            $members = DB::table('user_members')->where('user_members.member_code','=', $code)->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $members->login_name,
                'cell_phone'=> $members->cell_phone,
                'email'=> $members->email,
                'nickname'=> $members->citizen_name,
                'create_date'=> $members->create_date,
                'disabled'=> $members->disabled=='no' ? 'no' : 'yes',
                'type_id'=> keys_encrypt($members->user_type),
                'role_id'=> keys_encrypt($members->role_id),
                'office_id'=> keys_encrypt($members->office_id),
            );
        }
        else{
            $manager = DB::table('user_manager')->where('manager_code', $code)->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $manager->login_name,
                'cell_phone'=> $manager->cell_phone,
                'email'=> $manager->email,
                'nickname'=> $manager->nickname,
                'create_date'=> $manager->create_date,
                'disabled'=> $manager->disabled=='no' ? 'no' : 'yes',
                'role_id'=> keys_encrypt($manager->role_id),
                'type_id'=> keys_encrypt($manager->type_id),
                'office_id'=> keys_encrypt($manager->office_id),
            );
        }
        $this->page_data['user_detail'] = $user_detail;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userDetail',$this->page_data)->render();
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
        $inputs = $request->input();
        $code = $inputs['key'];
        //取出用户类型
        $type_list = array();
        $types = DB::table('user_type')->get();
        foreach($types as $type){
            $type_list[keys_encrypt($type->type_id)] = $type->type_name;
        }
        $office_list = array();
        $office = DB::table('user_office')->get();
        //取出科室
        foreach($office as $o){
            $office_list[keys_encrypt($o->id)] = $o->office_name;
        }
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        //取出角色
        foreach($roles as $role){
            $role_list[keys_encrypt($role->id)] = $role->name;
        }
        if($inputs['type'] == 1){
            $members = DB::table('user_members')->where('user_members.member_code','=', $code)->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $members->login_name,
                'cell_phone'=> $members->cell_phone,
                'email'=> $members->email,
                'nickname'=> $members->citizen_name,
                'create_date'=> $members->create_date,
                'disabled'=> $members->disabled=='no' ? 'no' : 'yes',
                'type_id'=> keys_encrypt($members->user_type),
                'role_id'=> keys_encrypt($members->role_id),
                'office_id'=> keys_encrypt($members->office_id),
            );
        }
        else{
            $manager = DB::table('user_manager')->where('manager_code', $code)->first();
            $user_detail = array(
                'key'=> $code,
                'login_name'=> $manager->login_name,
                'cell_phone'=> $manager->cell_phone,
                'email'=> $manager->email,
                'nickname'=> $manager->nickname,
                'create_date'=> $manager->create_date,
                'disabled'=> $manager->disabled=='no' ? 'no' : 'yes',
                'role_id'=> keys_encrypt($manager->role_id),
                'type_id'=> keys_encrypt($manager->type_id),
                'office_id'=> keys_encrypt($manager->office_id),
            );
        }
        $this->page_data['user_detail'] = $user_detail;
        $this->page_data['type_list'] = $type_list;
        $this->page_data['office_list'] = $office_list;
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.userEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $code = $inputs['key'];
        //判断是否有重名的
        $type_id = keys_decrypt($inputs['user_type']);
        if($type_id == 1){
            $sql = 'SELECT `member_code` FROM user_members WHERE `login_name` = "'.$inputs['login_name'].'" AND `member_code` != "'.$code.'"';
            $res = DB::select($sql);
        }
        else{
            $sql = 'SELECT `manager_code` FROM user_manager WHERE `login_name` = "'.$inputs['login_name'].'" AND `manager_code` != "'.$code.'"';
            $res = DB::select($sql);
        }
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['login_name'].'的用户']);
        }
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        if($type_id == 1){
            //获取用户提交的信息并格式化
            $saveMembers = array(
                'login_name' => $inputs['login_name'],
                'password' => empty($inputs['password'])? '' : password_hash($inputs['password'],PASSWORD_BCRYPT),
                'cell_phone' => $inputs['cell_phone'],
                'email'=> $inputs['email'],
                'user_type'=> keys_decrypt($inputs['user_type']),
                'office_id'=> keys_decrypt($inputs['user_office']),
                'role_id'=> keys_decrypt($inputs['user_role']),
                'member_level'=> 1,
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'create_date'=> $now,
            );
            $saveMemberInfo = array(
                'citizen_name' => $inputs['nickname'],
                'update_date' => $now
            );
            if(empty($inputs['password']))
                unset($saveMembers['password']);
            //以事物的方式储存账号
            DB::beginTransaction();
            $id = DB::table('user_members')->where('member_code', $code)->update($saveMembers);
            if($id===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            $iid = DB::table('user_member_info')->insertGetId($saveMemberInfo);
            if($iid===false){
                DB::rollback();
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
            DB::commit();
        }
        else{
            $save_data = array(
                'password'=> empty($inputs['password'])? '' : password_hash($inputs['password'],PASSWORD_BCRYPT),
                'login_name'=> $inputs['login_name'],
                'cell_phone'=> $inputs['cell_phone'],
                'nickname'=> $inputs['nickname'],
                'email'=> $inputs['email'],
                'role_id'=> keys_decrypt($inputs['user_role']),
                'office_id'=> keys_decrypt($inputs['user_office']),
                'type_id'=> keys_decrypt($inputs['user_type']),
                'disabled'=> isset($inputs['disabled']) ? 'no' : 'yes',
                'update_date'=> $now
            );
            if(empty($inputs['password']))
                unset($save_data['password']);
            $id = DB::table('user_manager')->where('manager_code', $code)->update($save_data);
            if($id===false){
                json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
            }
        }
        //修改成功则回调页面,取出数据
        $user_list = array();
        $managers = DB::table('user_manager')->get();
        foreach($managers as $key=> $managers){
            $user_list[$key]['key'] = $managers->manager_code;
            $user_list[$key]['login_name'] = $managers->login_name;
            $user_list[$key]['type_id'] = $managers->type_id;
            $user_list[$key]['nickname'] = $managers->nickname;
            $user_list[$key]['cell_phone'] = $managers->cell_phone;
            $user_list[$key]['disabled'] = $managers->disabled;
            $user_list[$key]['create_date'] = $managers->create_date;
        }
        //取出用户
        $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
        foreach($members as $member){
            $user_list[] = array(
                'key'=> $member->member_code,
                'login_name'=> $member->login_name,
                'type_id'=> $member->user_type,
                'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                'cell_phone'=> $member->cell_phone,
                'disabled'=> $member->disabled,
                'create_date'=> $member->create_date,
            );
        }
        //取出用户类型
        $user_type = DB::table('user_type')->get();
        foreach($user_type as $type){
            $type_list[$type->type_id] = $type->type_name;
        }
        //返回到前段界面
        $this->page_data['type_list'] = $type_list;
        $this->page_data['user_list'] = $user_list;
        $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $code = $inputs['key'];
        $type = keys_decrypt($inputs['type']);
        if($type == 1){
            DB::beginTransaction();
            $res = DB::table('user_members')->where('member_code',$code)->delete();
            if($res === false){
                DB::rollBack();
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
            }
            $res = DB::table('user_member_info')->where('member_code',$code)->delete();
            if($res === false){
                DB::rollBack();
                json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
            }
            DB::commit();
            $row = 1;
        }
        else{
            $row = DB::table('user_manager')->where('manager_code',$code)->delete();
        }
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $user_list = array();
            $managers = DB::table('user_manager')->get();
            foreach($managers as $key=> $managers){
                $user_list[$key]['key'] = $managers->manager_code;
                $user_list[$key]['login_name'] = $managers->login_name;
                $user_list[$key]['type_id'] = $managers->type_id;
                $user_list[$key]['nickname'] = $managers->nickname;
                $user_list[$key]['cell_phone'] = $managers->cell_phone;
                $user_list[$key]['disabled'] = $managers->disabled;
                $user_list[$key]['create_date'] = $managers->create_date;
            }
            //取出用户
            $members = DB::table('user_members')->join('user_member_info','user_members.member_code','=','user_member_info.member_code')->get();
            foreach($members as $member){
                $user_list[] = array(
                    'key'=> $member->member_code,
                    'login_name'=> $member->login_name,
                    'type_id'=> $member->user_type,
                    'nickname'=> empty($member->citizen_name) ? '未命名' : $member->citizen_name,
                    'cell_phone'=> $member->cell_phone,
                    'disabled'=> $member->disabled,
                    'create_date'=> $member->create_date,
                );
            }
            //取出用户类型
            $user_type = DB::table('user_type')->get();
            foreach($user_type as $type){
                $type_list[$type->type_id] = $type->type_name;
            }
            //返回到前段界面
            $this->page_data['type_list'] = $type_list;
            $this->page_data['user_list'] = $user_list;
            $pageContent = view('judicial.manage.user.userList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}