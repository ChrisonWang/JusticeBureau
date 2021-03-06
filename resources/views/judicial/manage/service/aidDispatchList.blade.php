<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{ $thisPageName }}
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            @if(isset($is_archived))
                <button type="button" class="btn btn-danger" data-node="system-archivedMng" onclick="loadContent($(this))">返回归档列表</button>
            @endif
        </div>
        @if(!isset($is_archived))
            <div class="container-fluid">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline">
                        <div class="container-fluid">
                            <div class="form-group" style="padding: 10px">
                                <label for="record_code">审批编号：</label>
                                <input type="text" class="form-control" id="record_code" name="record_code" placeholder="请输入审批编号">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="status">审批状态：</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="none">不限</option>
                                    <option value="waiting">待审批</option>
                                    <option value="pass">待指派</option>
                                    <option value="dispatch">已指派</option>
	                                <option value="archived">结案</option>
                                    <option value="reject">驳回</option>
                                </select>
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="apply_office">申请单位：</label>
                                <input type="text" class="form-control" id="apply_office" name="apply_office" placeholder="请输入申请单位">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="apply_aid_office">申请援助单位：</label>
                                <input type="text" class="form-control" id="apply_aid_office" name="apply_aid_office" placeholder="请输入申请援助单位">
                            </div>
                            <div class="form-group" style="padding: 10px">
                                <label for="case_name">案件名称：</label>
                                <input type="text" class="form-control" id="case_name" name="case_name" placeholder="请输入案件名称">
                            </div>
	                        <div class="form-group" style="padding: 10px">
                                <label for="aid_type">事项分类：</label>
                                <select class="form-control" name="aid_type" id="aid_type">
                                    <option value="none">不限</option>
	                                @if( isset($legal_types) && !empty($legal_types) )
										@foreach($legal_types as $l_type)
                                            <option value="{{ $l_type['type_id'] }}">{{ $l_type['type_name'] }}</option>
		                                @endforeach
	                                @endif
                                </select>
                            </div>
                            <button id="search" type="button" class="btn btn-info" onclick="search_aidDispatch($(this), $('#this-container'))">搜索</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        <hr/>
        <div class="container-fluid" id="this-container">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="15%" class="text-center">操作</th>
                        <th width="10%" class="text-center">申请编号</th>
                        <th width="10%" class="text-center">事项分类</th>
                        <th width="10%" class="text-center">审批状态</th>
                        <th width="15%" class="text-center">申请单位</th>
                        <th width="20%" class="text-center">申请援助单位</th>
                        <th width="20%" class="text-center">案件名称</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @foreach($apply_list as $apply)
                <tr>
                    <td>
                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-archived_key="{{ isset($archived_key)?$archived_key:'' }}" data-method="show" data-archived="{{ (isset($is_archived)&&$is_archived=='yes') ? 'yes' : 'no' }}" onclick="aidDispatchMethod($(this))">查看</a>
                        @if($apply['status'] == 'waiting' && !isset($is_archived))
	                        &nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-key="{{ $apply['key'] }}" data-method="edit" onclick="aidDispatchMethod($(this))">指派</a>
	                        &nbsp;&nbsp;
						@elseif($apply['status'] == 'pass' && !isset($is_archived))
							&nbsp;&nbsp;
	                        <a href="javascript: void(0) ;" data-r_code="{{ $apply['record_code'] }}" data-key="{{ $apply['key'] }}" data-method="archived" onclick="aidDispatchMethod($(this))">结案</a>
	                        &nbsp;&nbsp;
                        @endif
                    </td>
                    <td>{{ $apply['record_code'] }}</td>
                    <td>{{ $legal_types[$apply['aid_type']]['type_name'] }}</td>
                    <td>
                        @if($apply['status'] == 'pass')
                            <p style="color:green; font-weight: bold">已指派</p>
                        @elseif($apply['status'] == 'reject')
                            <p style="color:red; font-weight: bold">审核不通过</p>
	                    @elseif($apply['status'] == 'archived')
                            <p style="color:red; font-weight: bold">已结案</p>
                        @else
                            <p style="color:#FFA500; font-weight: bold">待指派</p>
                        @endif
                    </td>
                    <td>{{ spilt_title($apply['apply_office'], 20) }}</td>
                    <td>{{ spilt_title($apply['apply_aid_office'], 20) }}</td>
                    <td>{{ spilt_title($apply['case_name'], 20) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!--分页-->
        @if(isset($pages) && is_array($pages) && $pages != 'none')
            @include('judicial.manage.chips.servicePages')
        @endif
    </div>
</div>