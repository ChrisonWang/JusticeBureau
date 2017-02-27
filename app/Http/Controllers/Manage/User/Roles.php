<?php

namespace App\Http\Controllers\Manage\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;

use App\Http\Requests;

use App\Http\Controllers\Controller;

class Roles extends Controller
{
    private $page_data = array();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $menu_list = array();
        $menus = DB::table('user_menus')->get();
        //取出菜单
        foreach($menus as $key=> $menu){
            $menu_list[$key]['key'] = keys_encrypt($menu->id);
            $menu_list[$key]['menu_name'] = $menu->menu_name;
            $menu_list[$key]['nodes'] = json_decode($menu->nodes,true);
        }
        //取出第一个菜单
        $menu_first = DB::table('user_menus')->first();
        foreach(json_decode($menu_first->nodes, true) as $node_key => $node_name){
            $node_list[] = array(
                'node_key'=> keys_encrypt($node_key),
                'node_name'=> $node_name
            );
        }
        $this->page_data['node_list'] = $node_list;
        $this->page_data['menu_list'] = $menu_list;
        $pageContent = view('judicial.manage.user.rolesAdd',$this->page_data)->render();
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
        $id = DB::table('user_roles')->select('id')->where('name',$inputs['title'])->get();
        if(count($id) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['title'].'的角色菜单']);
        }
        //处理功能点
        $subs = json_decode($inputs['sub'], true);
        $permission = array();
        foreach($subs as $sub){
            $permission[] = $sub['menus'].'||'.$sub['nodes'].'||'.$sub['permission'];
        }
        $permission = array_unique($permission);
        //执行插入数据操作
        $now = date('Y-m-d H:i:s', time());
        $save_data = array(
            'name'=> $inputs['title'],
            'permission'=> json_encode($permission),
            'create_date'=> $now,
            'update_date'=> $now
        );
        $id = DB::table('user_roles')->insertGetId($save_data);
        if($id === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'添加失败']);
        }
        //添加成功后刷新页面数据
        else{
            //取出数据
            $role_list = array();
            $roles = DB::table('user_roles')->get();
            foreach($roles as $key=> $role){
                $role_list[$key]['key'] = keys_encrypt($role->id);
                $role_list[$key]['name'] = $role->name;
            }
            //返回到前段界面
            $this->page_data['role_list'] = $role_list;
            $pageContent = view('judicial.manage.user.rolesList',$this->page_data)->render();
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
        $role_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出菜单
        $menu_list = array();
        $menus = DB::table('user_menus')->get();
        $menu_nodes = array();
        foreach($menus as $key=> $menu){
            $menu_list[$key]['key'] = keys_encrypt($menu->id);
            $menu_list[$key]['menu_name'] = $menu->menu_name;
            foreach(json_decode($menu->nodes, true) as $n_k=> $_node){
                $menu_nodes[keys_encrypt($menu->id)][keys_encrypt($n_k)] = $_node;
            }
        }
        //取出详情
        $roles = DB::table('user_roles')->where('id',$id)->first();
        if(is_null($roles)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        //处理权限
        if(!empty($roles->permission)){
            $p_list = array();
            $permissions = json_decode($roles->permission,true);
            foreach($permissions as $key=> $permission){
                $permission = explode("||", $permission);
                $p_list[$key]['menus'] = $permission[0];
                $p_list[$key]['nodes'] = $permission[1];
                $p_list[$key]['permission'] = $permission[2];
                $p_list[$key]['node_list'] = $menu_nodes[$permission[0]];
            }
        }
        else{
            $p_list = 'none';
        }
        //处理详情
        $role_detail = array(
            'title' => $roles->name,
            'permissions' => $p_list,
            'create_date' => $roles->create_date
        );
        //取出第一个菜单
        $f_node_list = array();
        $menu_first = DB::table('user_menus')->first();
        foreach(json_decode($menu_first->nodes, true) as $node_key => $node_name){
            $f_node_list[] = array(
                'node_key'=> keys_encrypt($node_key),
                'node_name'=> $node_name
            );
        }
        //页面中显示
        $this->page_data['f_node_list'] = $f_node_list;
        $this->page_data['menu_list'] = $menu_list;
        $this->page_data['role_detail'] = $role_detail;
        $pageContent = view('judicial.manage.user.rolesDetail',$this->page_data)->render();
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
        $role_detail = array();
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //取出菜单
        $menu_list = array();
        $menus = DB::table('user_menus')->get();
        $menu_nodes = array();
        foreach($menus as $key=> $menu){
            $menu_list[$key]['key'] = keys_encrypt($menu->id);
            $menu_list[$key]['menu_name'] = $menu->menu_name;
            foreach(json_decode($menu->nodes, true) as $n_k=> $_node){
                $menu_nodes[keys_encrypt($menu->id)][keys_encrypt($n_k)] = $_node;
            }
        }
        //取出详情
        $roles = DB::table('user_roles')->where('id',$id)->first();
        if(is_null($roles)){
            json_response(['status'=>'failed','type'=>'redirect', 'res'=>URL::to('manage')]);
        }
        //处理权限
        if(!empty($roles->permission)){
            $p_list = array();
            $permissions = json_decode($roles->permission,true);
            foreach($permissions as $key=> $permission){
                $permission = explode("||", $permission);
                $p_list[$key]['menus'] = $permission[0];
                $p_list[$key]['nodes'] = $permission[1];
                $p_list[$key]['permission'] = $permission[2];
                $p_list[$key]['node_list'] = $menu_nodes[$permission[0]];
            }
        }
        else{
            $p_list = 'none';
        }
        //处理详情
        $role_detail = array(
            'key'=> keys_encrypt($roles->id),
            'title' => $roles->name,
            'permissions' => $p_list,
            'create_date' => $roles->create_date
        );
        //取出第一个菜单
        $f_node_list = array();
        $menu_first = DB::table('user_menus')->first();
        foreach(json_decode($menu_first->nodes, true) as $node_key => $node_name){
            $f_node_list[] = array(
                'node_key'=> keys_encrypt($node_key),
                'node_name'=> $node_name
            );
        }
        //页面中显示
        $this->page_data['f_node_list'] = $f_node_list;
        $this->page_data['menu_list'] = $menu_list;
        $this->page_data['role_detail'] = $role_detail;
        $pageContent = view('judicial.manage.user.rolesEdit',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doEdit(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        //判断是否有重名的
        $sql = 'SELECT `id` FROM user_roles WHERE `name` = "'.$inputs['title'].'" AND `id` != "'.$id.'"';
        $res = DB::select($sql);
        if(count($res) != 0){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'已存在名称为：'.$inputs['title'].'的菜单']);
        }
        //处理功能点
        $subs = json_decode($inputs['sub'], true);
        $permission = array();
        foreach($subs as $sub){
            $permission[] = $sub['menus'].'||'.$sub['nodes'].'||'.$sub['permission'];
        }
        $permission = array_unique($permission);
        //执行更新数据操作
        $save_data = array(
            'name'=> $inputs['title'],
            'permission'=> json_encode($permission),
            'update_date'=> date('Y-m-d H:i:s', time())
        );
        $rs = DB::table('user_roles')->where('id',$id)->update($save_data);
        if($rs === false){
            json_response(['status'=>'failed','type'=>'notice', 'res'=>'修改失败']);
        }
        //修改成功则回调页面,取出数据
        $role_list = array();
        $roles = DB::table('user_roles')->get();
        foreach($roles as $key=> $role){
            $role_list[$key]['key'] = keys_encrypt($role->id);
            $role_list[$key]['name'] = $role->name;
        }
        //返回到前段界面
        $this->page_data['role_list'] = $role_list;
        $pageContent = view('judicial.manage.user.rolesList',$this->page_data)->render();
        json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
    }

    public function doDelete(Request $request)
    {
        $inputs = $request->input();
        $id = keys_decrypt($inputs['key']);
        $row = DB::table('user_roles')->where('id',$id)->delete();
        if( $row > 0 ){
            //删除成功则回调页面,取出数据
            $role_list = array();
            $roles = DB::table('user_roles')->get();
            foreach($roles as $key=> $role){
                $role_list[$key]['key'] = keys_encrypt($role->id);
                $role_list[$key]['name'] = $role->name;
            }
            //返回到前段界面
            $this->page_data['role_list'] = $role_list;
            $pageContent = view('judicial.manage.user.rolesList',$this->page_data)->render();
            json_response(['status'=>'succ','type'=>'page', 'res'=>$pageContent]);
        }
        else{
            json_response(['status'=>'failed','type'=>'alert', 'res'=>'删除失败！']);
        }
    }

}