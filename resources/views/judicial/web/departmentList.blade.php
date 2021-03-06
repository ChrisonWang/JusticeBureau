<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--内容-->
<div class="w1024 zw_mb">
    <!--左侧菜单-->
    @include('judicial.web.layout.left')

    <div class="zw_right w810">
        <div class="zwr_top">
            <span><a href="/" style="color: #222222">首页</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/list/169" style="color: #222222">政务公开</a>&nbsp;&nbsp;>&nbsp;</span>
            <span><a href="/intro" style="color: #222222">司法局简介</a>&nbsp;&nbsp;>&nbsp;</span>
            <span style="color: #929292;"> 机构设置</span>
        </div>
        <div class="wz_body">
            @if( $department_list!='none' && is_array($department_list))
                @foreach($department_list as $department)
                    <div class="sf_link">
                        <span class="vd_tit">{{ $department['type_name'] }}</span>
                        @if($department['sub'] != 'none' && is_array($department['sub']))
                            <ul>
                                @foreach($department['sub'] as $key => $name)
                                    <li><a href="{{ URL::to('/department/intro').'/'.$key }}">{{ $name['department_name'] }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            该分类下暂无部门
                        @endif
                    </div>
                @endforeach
            @else
                <div class="sf_link">
                    <span class="vd_tit">暂无信息</span>
                </div>
            @endif
        </div>
    </div>

</div>


<!--底部-->
@include('judicial.web.chips.foot')
<script>
    $(function(){
        $('#header').load('header.html');
        $('#footer').load('footer.html');
    })
</script>
</body>
</html>