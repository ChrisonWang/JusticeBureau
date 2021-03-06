/**
 * Created by Auser on 2017/2/10.
 */
window.onload = function(){
    var browserInfo = getBrowserInfo();
    if(browserInfo != false){
        var top_bar = document.getElementById('top_bar');
        var myBrowser = document.getElementById('myBrowser');
        top_bar.style.display = 'block';
        myBrowser.innerHTML = browserInfo;
    }
};
$(document).ready(function(){
    var browserInfo = getBrowserInfo();
    if(browserInfo != false){
        var top_bar = document.getElementById('top_bar');
        var myBrowser = document.getElementById('myBrowser');
        top_bar.style.display = 'block';
        myBrowser.innerHTML = browserInfo;
    }

    //首页tab
    $('#_top_tab_a li').hover(
        function(){
            $(this).addClass('idbr_topsd_hover');
        },
        function(){
            $(this).removeClass('idbr_topsd_hover');
        }
    );
    $('#_top_tab_b li').hover(
        function(){
            $(this).addClass('idbr_topsd_hover');
        },
        function(){
            $(this).removeClass('idbr_topsd_hover');
        }
    );

    //左侧树状菜单
    $('.law_body span').hover(
        function(){
            $(this).addClass('law_hover');
            cancelBubble();
            event.stopPropagation();
        },
        function(){
            $(this).removeClass('law_hover');
            cancelBubble();
            event.stopPropagation();
        }
    );

    //首页选中
    $(".idbr_top ul li").click(function(){
        $(this).siblings("li").removeClass("idbr_topsd");
        $(this).addClass("idbr_topsd");
    });
    $(".hd_ful .hd_lv3 span").click(function(){
        $(this).siblings("span").removeClass("lv3_sd");
        $(this).addClass("lv3_sd");
    });

    //政务公开
    $(".zw_left div").click(function(){
        //$(this).parent().parent().siblings("li").removeClass("lb_select");
        $(this).parent().toggleClass("lb_select");
    });
    $(".law_body span").click(function(){
        $(this).siblings("span").removeClass("lb_act");
        $(this).addClass("lb_act");
        cancelBubble();
        event.stopPropagation();
    });

    $(".closed").click(function(){
        $(".alert_sh").hide();
    });

    //图片列表hover
    $('.image_list').hover(
        function(){
            $(this).addClass('picture_hover');
        },
        function(){
            $(this).removeClass('picture_hover');
        }
    );
    //网上办事hover
    $('#ico_1').mouseover(function(){
        $(this).hide();
        $('#ico_1_sub').show();
    });
    $('#ico_1_sub').mouseout(function(){
        $(this).hide();
        $('#ico_1').show();
    });

    $('#ico_2').mouseover(function(){
        $(this).hide();
        $('#ico_2_sub').show();
    });
    $('#ico_2_sub').mouseout(function(){
        $(this).hide();
        $('#ico_2').show();
    });

    $('#ico_3').mouseover(function(){
        $(this).hide();
        $('#ico_3_sub').show();
    });
    $('#ico_3_sub').mouseout(function(){
        $(this).hide();
        $('#ico_3').show();
    });

    $('#ico_4').mouseover(function(){
        $(this).hide();
        $('#ico_4_sub').show();
    });
    $('#ico_4_sub').mouseout(function(){
        $(this).hide();
        $('#ico_4').show();
    });

    //个人中心tab
    $('#member_tabs').find('.panel-member-switch').eq(0).addClass('on');

});

function getEvent(){
    if(window.event)    {return window.event;}
    fun=getEvent.caller;
    while(fun!=null){
        var arg0=fun.arguments[0];
        if(arg0){
            if((arg0.constructor==Event || arg0.constructor ==MouseEvent
                || arg0.constructor==KeyboardEvent)
                ||(typeof(arg0)=="object" && arg0.preventDefault
                && arg0.stopPropagation)){
                return arg0;
            }
        }
        fun=fun.caller;
    }
    return null;
}

//阻止冒泡
function cancelBubble()
{
    var e=getEvent();
    if(window.event){
        //e.returnValue=false;//阻止自身行为
        e.cancelBubble=true;//阻止冒泡
    }else if(e.preventDefault){
        //e.preventDefault();//阻止自身行为
        e.stopPropagation();//阻止冒泡
    }
}

function getBrowserInfo()
{
    var agent = navigator.userAgent.toLowerCase() ;
    var regStr_ie = /msie [\d.]+;/gi ;
    var regStr_ff = /firefox\/[\d.]+/gi ;
    var regStr_opr = /opr\/[\d.]+/gi ;
    var regStr_opera = /version\/[\d.]+/gi ;
    var regStr_chrome = /chrome\/[\d.]+/gi ;
    var regStr_saf = /version\/[\d.]+/gi ;
    var regStr_edge = /edge\/[\d.]+/gi ;

    var browser = '';
    var v = '';
    var version = '';
    //IE11以下
    if(agent.indexOf("msie") > -1)
    {
        browser = agent.match(regStr_ie) ;
        version = browser[0].split("msie");
        return 'Internet Explorer' + version[1]
    }
    //IE11版本中不包括MSIE字段
    if(agent.indexOf("trident") > 0&&agent.indexOf("rv") > 0){
        return false
    }
    //Edge
    if(agent.indexOf("edge") > -1){
        browser = agent.match(regStr_edge) ;
        version = browser[0].split("/");
        if(version[0]+'.'+version[1] < 13.1){
            return 'Microsoft Edge(' + version[1] + ')'
        }
        else{
            return false
        }
    }
    //firefox
    if(agent.indexOf("firefox") > -1)
    {
        browser = agent.match(regStr_ff) ;
        v = browser[0].split("/");
        version = v[1].split(".");
        if(version[0]+'.'+version[1] < 47.0){
            return "Mozilla Firefox("+ v[1] +")"
        }
        else if(version[0]+'.'+version[1] == 47.0){
            if(version[2] < 1){
                return "Mozilla Firefox("+ v[1] +")"
            }
            else{
                return false
            }
        }
        else {
            return false
        }
    }
    //Chrome
    if(agent.indexOf("chrome") > -1 && agent.indexOf("opr") < 0)
    {
        browser = agent.match(regStr_chrome);
        v = browser[0].split("/");
        version = v[1].split(".");
        if(version[0]+'.'+version[1] < 51.0){
            return "Google Chrome("+ v[1] +")"
        }
        else {
            return false
        }
    }
    //OperaNew
    if(agent.indexOf("opr") > -1)
    {
        browser = agent.match(regStr_opr);
        v = browser[0].split("/");
        version = v[1].split(".");
        if(version[0]+'.'+version[1] < 11.64){
            return "Opera("+ v[1] +")"
        }
        else {
            return false
        }
    }
    //OperaOld
    if(agent.indexOf("opera") > -1)
    {
        browser = agent.match(regStr_opera);
        v = browser[0].split("/");
        version = v[1].split(".");
        if(version[0]+'.'+version[1] < 11.64){
            return "Opera("+ v[1] +")"
        }
        else {
            return false
        }
    }
    //Safari
    if(agent.indexOf("safari") > -1 && agent.indexOf("chrome") < 0)
    {
        browser = agent.match(regStr_saf) ;
        v = browser[0].split("/");
        version = v[1].split(".");
        if(version[0]+'.'+version[1] < 9.1){
            return "Safari("+ v[1] +")"
        }
        else {
            return false
        }
    }
}

function file_download(t){
    var file = t.data('link');
    var url = '/download';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: {file:file},
        success: function(re){
            re;
        }
    });
}

function sendVerify(){
    var nu = $('#cellPhone').val();
    var url = '/user/sendVerify';
    var imgVerifyCode = $('#imgVerifyCode').val();
    $("#message_notice").addClass('hidden');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {phone:nu, img:imgVerifyCode},
        success: function(re){
            if(re.status == 'succ'){
                alert("已发送，请注意查收");
                var count = 90;
                var countdown = setInterval(CountDown, 1000);
                function CountDown() {
                    $("#sendVerifyBtn").attr("disabled", true);
                    $("#sendVerifyBtn").val(count + "秒");
                    if (count == 0) {
                        $("#sendVerifyBtn").removeAttr("disabled");
                        $("#sendVerifyBtn").val('获取验证码');
                        clearInterval(countdown);
                    }
                    count--;
                }
            }
            else if(re.status == 'failed'){
                $("#message_notice").removeClass('hidden');
                $("#message_notice").find("p").text(re.res);
            }
        }
    });
}

function jumpToFlink(s){
    var url = s.find("option:selected").val();
    if(url == 'type'){
        return true;
    }
    else {
        window.open(url);
    }
}

function changeFontSize(s){
    var s = s||'12px';
    $("#content").css("font-size", s);
}

function loadArticle(t,c){
    var channel = t.data('channel');
    var channel_id = t.data('key');
    var top = t.data('top');
    var url = 'index/loadArticle';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {channel_id: channel_id, top: top},
        success:function(re){
            if(channel == 'zwgk'){
                if(re.status == 'succ'){
                    var list = jQuery.parseJSON(re.res);
                    var a_list = '';
                    $.each(list, function(i,sub){
                        a_list += '<li><a href="'+sub.url+'" target="_blank"> <span class="idbrd_l" style="color: #222222">'+sub.article_title+'</span> <span class="idbrd_r" style="color: #222222">'+sub.publish_date+'</span></a></li>';
                    });
                    c.html(a_list);
                    $('#more_2').show();
                    $('.more_2').attr('href', '/list/' + channel_id);
                }
                else if(re.status == 'failed'){
                    var none_data = '<div style=" width: 100%; padding: 10px; margin: 0 auto; height: 250px; line-height: 250px; text-align: center; font-size: 14px; color: #929292">暂无数据</div>';
                    c.html(none_data);
                    $('#more_2').hide();
                    return false;
                }
            }
            else if(channel == 'sfdt'){
                if(re.status == 'succ'){
                    var list = jQuery.parseJSON(re.res);
                    var a_list = '';
                    $.each(list, function(i,sub){
                        a_list += '<li><span class="idbrd_l"><a href="'+sub.url+'" target="_blank">'+sub.article_title+'</a></span><span class="idbrd_r">'+sub.publish_date+'</span></li>';
                    });
                    c.html(a_list);
                }
                else if(re.status == 'failed'){
                    c.html('暂无数据');
                    return false;
                }
            }
        }
    });
    return false;
}

function addSuggestion(){
    var name = $("input[name='name']").val();
    var email = $("input[name='email']").val();
    var cell_phone = $("input[name='cell_phone']").val();
    var title = $("input[name='title']").val();
    var content = $("textarea[name='content']").val();
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#suggestionNotice').addClass('hidden');
    var url = '/suggestions/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: $('#suggestionForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $('#suggestionNotice').removeClass('hidden');
                $('#suggestionNotice').text(re.res);
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
                window.location.href='/suggestions/list/1'
            }
        }
    });

}

function addConsultion(){
    var name = $("input[name='name']").val();
    var email = $("input[name='email']").val();
    var cell_phone = $("input[name='cell_phone']").val();
    var title = $("input[name='title']").val();
    var content = $("textarea[name='content']").val();
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    $('#consultionNotice').addClass('hidden');
    var url = '/consultions/add';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: $('#consultionForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $('#consultionNotice').removeClass('hidden');
                $('#consultionNotice').text(re.res);
            }
            else if(re.status == 'succ'){
                alert('提交成功！');
                window.location.href='/consultions/list/1'
            }
        }
    });
}

function aidDispatchApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    //计算长度
    var len_apply_office = str_count_len($("input[name='apply_office']").val());
    var len_apply_aid_office = str_count_len($("input[name='apply_aid_office']").val());
    var len_case_name = str_count_len($("input[name='case_name']").val());
    var len_case_description = str_count_len($("textarea[name='case_description']").val());
    var len_detention_location = str_count_len($("input[name='detention_location']").val());
    var len_judge_description = str_count_len($("textarea[name='judge_description']").val());
    if(len_apply_office > 200){
        $(".alert_sh .als_down").text('“申请单位”应为长度200以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    if(len_apply_aid_office > 200){
        $(".alert_sh .als_down").text('“申请援助单位”应为长度200以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    if(len_case_name > 200){
        $(".alert_sh .als_down").text('“案件名称”应为长度200以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    if(len_case_description > 1000){
        $(".alert_sh .als_down").text('“涉嫌犯罪内容”应为长度1000以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    if(len_detention_location > 200){
        $(".alert_sh .als_down").text('“收押居住地”应为长度200以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    if(len_judge_description > 1000){
        $(".alert_sh .als_down").text('“判刑处罚内容”应为长度1000以内的字符串');
        $(".alert_sh").show();
        return false;
    }
    //弹窗提示
    $(".alert_sh .als_top").text('正在提交！');
    $(".alert_sh .als_down").text('附件上传中,请耐心等待……');
    $(".alert_sh").show();

    $('#notice').attr('hidden',true);
    var url = '/service/aidDispatch/doApply';
    $('#aidDispatchForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: $('#aidDispatchForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $(".alert_sh .als_top").text('提交错误！');
                $(".alert_sh .als_down").text(re.res + '！');
                return false;
            }
            else if(re.status == 'succ'){
                $(".alert_sh").hide();
                alert('提交成功！');
                window.location.href=re.link;
            }
        },
        error:function(re,s,et){
            $(".alert_sh .als_top").text('提交错误！');
            $(".alert_sh .als_down").text('您上传的文件过大，服务器拒绝了您的请求');
            return false;
        }
    });
}

function aidApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    //计算长度
    var len_apply_address = str_count_len($("input[name='apply_address']").val());
    var len_defendant_company = str_count_len($("input[name='defendant_company']").val());
    var len_defendant_addr = str_count_len($("input[name='defendant_addr']").val());
    var len_case_location = str_count_len($("input[name='case_location']").val());
    if(len_apply_address > 200){
        $(".alert_sh .als_top").text('提交错误');
        $(".alert_sh .als_down").text('“通讯地址”应为长度200以内的字符串！');
        $(".alert_sh").show();
        return false;
    }
    if(len_defendant_company > 200){
        $(".alert_sh .als_top").text('提交错误');
        $(".alert_sh .als_down").text('“被告人单位名称”应为长度200以内的字符串！');
        $(".alert_sh").show();
        return false;
    }
    if(len_defendant_addr > 200){
        $(".alert_sh .als_top").text('提交错误');
        $(".alert_sh .als_down").text('“被告人通讯地址”应为长度200以内的字符串！');
        $(".alert_sh").show();
        return false;
    }
    if(len_case_location > 200){
        $(".alert_sh .als_top").text('提交错误');
        $(".alert_sh .als_down").text('“发生地点”应为长度200以内的字符串！');
        $(".alert_sh").show();
        return false;
    }
    //弹窗提示
    $(".alert_sh .als_top").text('正在提交！');
    $(".alert_sh .als_down").text('附件上传中,请耐心等待……');
    $(".alert_sh").show();

    $('#notice').attr('hidden',true);
    var url = '/service/aidApply/doApply';
    $('#aidApplyForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: $('#aidApplyForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $(".alert_sh .als_top").text('提交错误！');
                $(".alert_sh .als_down").text(re.res + '！');
            }
            else if(re.status == 'succ'){
                $(".alert_sh").hide();
                alert('提交成功！');
                window.location.href=re.link;
            }
        },
        error:function(re,s,et){
            $(".alert_sh .als_top").text('提交错误！');
            $(".alert_sh .als_down").text('您上传的文件过大，服务器拒绝了您的请求');
        }
    });
}

function expertiseApply(){
    var c = confirm("确认提交？");
    if(c != true){
        return false;
    }
    //弹窗提示
    $(".alert_sh .als_top").text('正在提交！');
    $(".alert_sh .als_down").text('附件上传中,请耐心等待……');
    $(".alert_sh").show();

    $('#notice').attr('hidden',true);
    var url = '/service/expertise/doApply';
    $('#expertiseForm').ajaxSubmit({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: $('#expertiseForm').serialize(),
        success: function(re){
            if(re.status == 'failed'){
                $(".alert_sh .als_top").text('提交错误！');
                $(".alert_sh .als_down").text(re.res + '！');
            }
            else if(re.status == 'succ'){
                $(".alert_sh").hide();
                window.location.href=re.link;
            }
        },
        error:function(re,s,et){
            $(".alert_sh .als_top").text('提交错误！');
            $(".alert_sh .als_down").text('您上传的文件过大，服务器拒绝了您的请求');
        }
    });
}

function loadExpertiseFile(t){
    var type_id = t.find("option:selected").val();
    var url = '/service/expertise/loadFile';
    if(type_id == 'none'){
        return false;
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async:false,
        url: url,
        method: "POST",
        data: {type_id: type_id},
        success: function(re){
            if(re.status == 'failed'){
                var h = '<a href="javascript: void(0) ;">未上传附件</a>';
                $('#expertiseFile').html(h);
            }
            else if(re.status == 'succ'){
                var h = '<a href="'+ re.file_url +'" target="_blank">'+ re.file_name+ '</a>';
                $('#expertiseFile').html(h);
            }
        }
    });
}

function switch_service(t){
    var id = 's_'+t.data('key');
    $(".panel-member-switch").removeClass('on');
    t.parents(".panel-member-switch").addClass('on');

    $("#s_apply").hide();
    $("#s_dispatch").hide();
    $("#s_consultions").hide();
    $("#s_expertise").hide();
    $("#s_suggestions").hide();

    $("#"+id).show();
}

function switch_service2(t){
    $(".wsrb_left").find('li').find('a').removeClass('wsrb_hv');
    t.addClass('wsrb_hv');

    $('.wsrb_right').hide();
    $("#"+ t.data('key')).show();
}

function show_opinion(t){
    var record_code = t.data('key');
    var type = t.data('type');
    var url = '/service/getOpinion';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {record_code:record_code, type:type},
        success: function(re){
            if(re.status == 'succ'){
                $('#alert_sh').show();
                $('#alert_sh').find('.als_down').text(re.res);
            }
        }
    });
}

function service_page(t){
    var method = t.data('method');
    var type = t.data('type');
    var c = t.data('c');
    var url = '/user/service/list';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {method: method, type: type},
        success: function(re){
            if(re.status == 'succ'){
                $("#"+c).html(re.res);
            }
        }
    });
}

function cms_page_jumps(t){
    var page = $('#page_no_input').val();
    var type = t.data('type');
    url = type + '/' + page;
    window.location.href = url;
}

function str_count_len(str){
    var realLength = 0, len = str.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128)
            realLength += 1;
        else
            realLength += 1;
    }
    return realLength;
}

function member_show_reason(t){
    var code = t.data('key');
    var url = '/suggestions/detail';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        method: "POST",
        data: {code: code},
        success: function(re){
            if(re.status == 'succ'){
                $("#record_no").val(re.res.record_code);
                $("#type").val(re.res.type);
                $("#title").html(re.res.title);
                $("#create_date").val(re.res.create_date);
                $("#content").html(re.res.content);
                $("#answer_content").html(re.res.answer_content);
                $("#answer_date").val(re.res.answer_date);

                $('#reason_model').fadeIn(300);
                return false
            }
            else {
                $('#reason_model').hide();
                return false;
            }
        }
    });
}