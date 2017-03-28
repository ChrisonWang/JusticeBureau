<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
@include('judicial.web.chips.headIndexWB')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!-- 左侧菜单 -->
    @include('judicial.web.layout.serviceLeft');

    <div class="zw_right w810">
        <div class="zwr_top">
            <div class="zwr_top">
                <span onclick="javascript: window.location.href='{{ URL::to('/') }}'">首页&nbsp;&nbsp;>&nbsp;</span>
                <span onclick="javascript: window.location.href='{{ URL::to('service') }}'">网上办事&nbsp;&nbsp;>&nbsp;</span>
                <span>法律援助&nbsp;&nbsp;>&nbsp;</span>
                <span style="color: #101010;">群众预约</span>
            </div>
        </div>

        <div class="wsfy_tit">
            <span class="wfb_tit">群众预约援助流程</span>
            <ul>
                <li><i>■</i>申请人填写《法律援助申请表》<a href="{{ URL::to('/').'/uploads/system/files/群众预约援助表.xlsx'}}" target="_blank">点击下载附件</a></li>
                <li><i>■</i>申请人填写《法律援助申请人经济情况证明表》，需证明单位签章（民工讨薪无需提供）<a href="{{ URL::to('/').'/uploads/system/files/群众预约援助表.xlsx'}}" target="_blank">点击下载附件</a></li>
                <li><i>■</i>申请人在本系统提交群众预约申请，并上传扫描附件</li>
                <li><i>■</i>法律援助科科长审核后，根据律师情况，在系统中指派律师对用户进行援助。</li>
                <li><i>■</i>指派成功后系统短信通知申请人律师联系信息。</li>
            </ul>
        </div>
        <div class="xx_tit">
            群众预约援助申请
            <span style="font-family: MicrosoftYaHei;font-size: 12px;color: #929292;letter-spacing: 0; float: right">
                受理编号:{{$record_detail['record_code']}}
            </span>
        </div>
        <form id="aidApplyForm">
            <input type="hidden" name="key" value="{{$record_detail['record_code']}}">
            <div class="text_a">
                <span class="vd_tit">申请人信息</span>
                <ul>
                    <li>
                        <span class="wsc_txt">姓名</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['apply_name'] }}' name="apply_name" placeholder="请输入姓名">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">政治面貌</span>
                        <div class="cx_inp">
                            <select name="political">
                                <option value="citizen" @if($record_detail['political']=='citizen') selected @endif>群众</option>
                                <option value="cp" @if($record_detail['political']=='cp') selected @endif>党员</option>
                                <option value="cyl" @if($record_detail['political']=='cyl') selected @endif>团员</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">性别</span>
                        <div class="cx_inp">
                            <select name="sex">
                                <option value="male" selected>男</option>
                                <option value="female" selected>女</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">联系电话</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['apply_phone'] }}' name="apply_phone" placeholder="请输入联系电话" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">身份证号码</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['apply_identity_no'] }}' name="apply_identity_no" placeholder="请输入身份证号码" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">通讯地址</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['apply_address'] }}' name="apply_address" placeholder="请输入通讯地址" class="w590">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text_a">
                <span class="vd_tit">被告人概况</span>
                <ul>
                    <li>
                        <span class="wsc_txt">姓名</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['defendant_name'] }}' name="defendant_name" placeholder="请输入姓名" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">联系电话</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['defendant_phone'] }}' name="defendant_phone" placeholder="请输入联系电话" class="w250">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">单位名称</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['defendant_company'] }}' name="defendant_company" placeholder="请输入单位名称" class="w590">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">通讯地址</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['defendant_addr'] }}' name="defendant_addr" placeholder="请输入通讯地址" class="w590">
                        </div>
                    </li>
                </ul>
            </div>
            <div class="text_a post_btn">
                <span class="vd_tit">案件描述</span>
                <ul>
                    <li>
                        <span class="wsc_txt">发生时间</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['happened_date'] }}' name="happened_date" placeholder="例：YYYY-MM-DD" />
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">所属区域</span>
                        <div class="cx_inp">
                            <select name="case_area_id">
                                @if(!isset($area_list) || !is_array($area_list) || count($area_list)<1)
                                    <option value="none">未设置区域</option>
                                @else
                                    @foreach($area_list as $k=> $area)
                                        <option value="{{ $k }}">{{ $area }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt">案件分类</span>
                        <div class="cx_inp">
                            <select name="type">
                                @foreach($type_list as $k=> $type)
                                    <option value="{{ $k }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                    <li class="w590" style="margin-bottom: 10px">
                        <span class="wsc_txt" style="width: 100px">
                            是否为讨薪:&nbsp;&nbsp;
                            <input type="checkbox" name="salary_dispute" @if($record_detail['salary_dispute'] == 'yes') checked @endif value="yes" class=""/>
                        </span>
                    </li>
                    <li>
                        <span class="wsc_txt">发生地点</span>
                        <div class="cx_inp">
                            <input type="text" value='{{ $record_detail['case_location'] }}' name="case_location" placeholder="请输入发生的具体地点" class="w590">
                        </div>
                    </li>
                    <li>
                        <span class="wsc_txt" style="vertical-align: top;">举报问题描述</span>
                        <div class="cx_inp">
                            <textarea name="dispute_description" placeholder="请对举报的问题进行具体描述" class="w590">
                                {{ $record_detail['dispute_description'] }}
                            </textarea>
                        </div>
                    </li>
                </ul>
                <div class="mt_btn">
                    <span class="mtb_text">附件</span>
                    <span class="mtb_m">
                        <input type="file" name="file">
                    </span>
                </div>
                <div class=mt_last>
                    <span class="mtl_txt">温馨提示</span>
                    <div class="mt_ul">
                        <span>1. 如果有多个文件可放入文件夹压缩后再上传压缩文件。</span>
                        <span>2. 民工讨薪事件无需上传《法律援助经济状况证明表》。</span>
                    </div>
                </div>
                <div class="last_btn" onclick="aidApply()">
                    提交申请
                </div>
            </div>
        </form>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
</body>
</html>