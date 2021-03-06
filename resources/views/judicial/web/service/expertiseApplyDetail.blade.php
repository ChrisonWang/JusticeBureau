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
            <span style="color: #929292;">提交审核</span>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">司法鉴定申请流程</span>
            <ul>
                <li><i>■</i>申请人根据司法鉴定类型下载对应表格。</li>
                <li><i>■</i>申请人在本系统提交司法鉴定申请，并上传相关附件。</li>
                <li><i>■</i>申请人在收到司法局审批通过短信通知后，携带所有资料到司法局一次性办理业务。</li>
                <li><i>■</i>如果审批不通过，请按提示重新提交申请。</li>
            </ul>
        </div>
        <div class="xx_tit" style="padding-left: 148px">
            司法鉴定申请
            <span style="font-family: MicrosoftYaHei;font-size: 12px;color: #929292;letter-spacing: 0; float: right">
                受理编号:{{$record_detail['record_code']}}
            </span>
        </div>
        <div class="text_a post_btn gjf" style="height: auto; padding-bottom: 50px">
            <form id="expertiseForm">
            <ul>
                <li>
                    <span class="wsc_txt" style="width: 70px">申请人姓名</span>
                    <div class="cx_inp">
                        <input disabled type="text" value="{{$record_detail['apply_name']}}" name="apply_name" placeholder="请输入申请人姓名"/>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 70px">联系电话</span>
                    <div class="cx_inp">
                        <input disabled type="text" value="{{$record_detail['cell_phone']}}" name="cell_phone" placeholder="请输入联系电话"/>
                    </div>
                </li>
                <li>
                    <span class="wsc_txt" style="width: 70px">类型</span>
                    <div class="cx_inp">
                        <input disabled type="text" value="{{$record_detail['type']}}" name="cell_phone" placeholder="请输入联系电话"/>
                    </div>
                </li>
            </ul>
            <div class="mt_btn">
                <span class="wsc_txt" style="width: 80px">附件</span>
                <div class="cx_inp">
                    <a href="{{ $record_detail['apply_table'] }}" target="_blank" style="color:#4990E2;">{{ $record_detail['apply_table_name'] }}</a>
                </div>
            </div>

            <div class="last_btn" style="background: #ECECEC">
                @if($record_detail['approval_result'] == 'reject')
                    审核不通过
                @elseif($record_detail['approval_result'] == 'pass')
                    审核成功
                @else
                    待审核
                @endif
            </div>
            </form>
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>