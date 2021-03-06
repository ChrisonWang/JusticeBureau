<div class="panel-member-body a" id="s_apply" @if($apply_count == 0 || $expertise_count > 0) hidden @endif>
    <div class="container-fluid" style="padding-left: 0; background: #F9F9F9">
        <div class="left_title" style="margin-top: 10px">
            <span>我的法律援助记录</span>
        </div>
    </div>
    @if(isset($apply_list) && count($apply_list)>0)
        <table class="table">
            <thead>
            <tr>
                <th>提交时间</th>
                <th>审批编号</th>
                <th>案件分类</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($apply_list as $apply)
                <tr>
                    <td style="vertical-align: middle">{{ date("Y-m-d H:i", strtotime($apply->apply_date)) }}</td>
                    <td style="vertical-align: middle">{{ $apply->record_code }}</td>
                    <td style="vertical-align: middle">{{ isset($apply_type[$apply->type]) ? $apply_type[$apply->type] : '-' }}</td>
                    <td style="vertical-align: middle">
	                    @if($apply->status == 'archived')
                            <div class="shtg" style="color: #4684CD;">已结案</div>
                        @elseif($apply->status == 'pass')
		                    <div class="dsh" style="color: #7DA750;">待指派</div>
	                    @elseif($apply->status == 'dispatch')
		                    <div class="shtg" style="color: #4684CD;">已指派</div>
                        @elseif($apply->status == 'reject')
                            <div class="btg" style="color: #222222;">审核不通过/
                                <a href="#" data-key="{{ $apply->record_code }}" data-type="service_legal_aid_apply" onclick="show_opinion($(this))" style="color: #DD3938">查看原因</a>
                            </div>
                        @else
                            <div class="dsh" style="color: #7DA750">待审批</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle">
                        @if($apply->status == 'reject')
                            <a href="{{ URL::to('service/aidApply/edit').'/'.$apply->record_code }}" class="tb_btn">编辑</a>
                        @else
                            <a href="{{ URL::to('service/aidApply/detail').'/'.$apply->record_code }}" class="tb_btn">查看</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!--分页-->
        <div class="zwr_ft" style="display: none">
            <div class="fy_left">
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="first" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))"> 首页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="per" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))">上一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="next" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))">下一页</a>
                </span>
                <span>
                    <a href="javascript: void(0) ;" data-type="apply" data-method="last" data-now="{{ $apply_pages['now_page'] }}" data-c="s_apply" onclick="service_page($(this))"> 尾页</a>
                </span>
                <div class="fy_right">
                    <span>总记录数：{{ $apply_pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页: {{ $apply_pages['now_page'] }}/{{ $apply_pages['count_page'] }}</span>
                </div>
            </div>
        </div>

    @else
        <p class="lead text-center">无记录</p>
    @endif
</div>