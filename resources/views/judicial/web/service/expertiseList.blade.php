<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="{{ URL::to('/') }}" style="color: #222222">首页&nbsp;&nbsp;>&nbsp;</a></span>
            <span><a href="/service" style="color: #222222">网上办事</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/service/expertise/list/1" style="color: #222222">司法鉴定</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;">审批状态查询</span>
        </div>
        @if(isset($record_list) && is_array($record_list) && count($record_list)>0)
        <table class="ws_table sh_tb">
            <thead>
            <th>提交时间</th>
            <th>审批编号</th>
            <th>鉴定类型</th>
            <th>状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach($record_list as $record)
            <tr>
                <td>{{ $record['apply_date'] }}</td>
                <td>{{ $record['record_code'] }}</td>
                <td>{{ isset($type_list[$record['type_id']]) ? $type_list[$record['type_id']] : '-' }}</td>
                <td>
                    @if($record['approval_result'] == 'pass')
                        <div class="shtg" style="color: #4684CD;">审核通过</div>
                    @elseif($record['approval_result'] == 'reject')
                        <div class="btg">审核不通过/
                            <a href="#" data-key="{{ $record['record_code'] }}" style="color: #E23939;" data-type="service_judicial_expertise" onclick="show_opinion($(this))">查看原因</a>
                        </div>
                    @else
                        <div class="dsh" style="color: #7DA750;">待审核</div>
                    @endif
                </td>
                <td>
                    @if($record['approval_result'] == 'reject')
                        <a href="{{ URL::to('service/expertise/edit').'/'.$record['record_code'] }}" class="tb_btn">编辑</a>
                        @else
                        <a href="{{ URL::to('service/expertise/detail').'/'.$record['record_code'] }}" class="tb_btn">查看</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <!--弹窗-->
        <div class="alert_sh" id="alert_sh" style="display: none">
            <a href="javascript:void(0)" class="closed">X</a>
            <div class="als_top">审核不通过原因</div>
            <div class="als_down">请按照流程填写申请表，重新提交审核。</div>
        </div>
        @else
            <p style="width: 100%; text-align: center; margin: 0 auto; line-height: 50px; padding: 10px; font-size: 14px; color: #929292">
                暂无记录！
            </p>
        @endif

        @if(is_array($pages) && count($pages)>0 && isset($record_list) && is_array($record_list) && count($record_list)>0)
            <div class="zwr_ft">
                <div class="fy_left">
                    <span>@if($pages['count_page']>1 )<a href="{{ URL::to('service/'.$pages['type']) }}"> 首页</a> @else 首页 @endif</span>
                <span>
                    @if($pages['now_page'] >1 ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']-1) }}">上一页</a> @else 上一页 @endif
                </span>
                <span>
                    @if($pages['now_page']<$pages['count_page'] ) <a href="{{ URL::to('service/'.$pages['type']).'/'.($pages['now_page']+1) }}">下一页</a> @else 下一页 @endif
                </span>
                    <span>@if($pages['count_page']>1 && $pages['now_page']<$pages['count_page'] )<a href="{{ URL::to('service/'.$pages['type']).'/'.$pages['count_page'] }}"> 尾页</a> @else 尾页 @endif</span>
                </div>
                <div class="fy_right">
                    <span>总记录数：{{ $pages['count'] }}</span>
                    <span>每页显示10条记录</span>
                    <span>当前页{{ $pages['now_page'] }}/{{ $pages['count_page'] }}</span>
                    <span>跳转至第<input id="page_no_input" type="text" value="1"/>页</span>
                    <a class="fy_btn" onclick="cms_page_jumps($(this))" data-type="{{ '/service/'.$pages['type'] }}">跳转</a>
                </div>
            </div>
        @endif
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>