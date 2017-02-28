<!DOCTYPE html>
<html>
@include('judicial.web.chips.headIndex')
<body>
<!--头部导航-->
@include('judicial.web.chips.nav')

<!--搜索栏-->
<div class="index_search">
    <div class="id_sch_l">
        今天是： {{ date('Y-m-d l',time()) }}
    </div>
    <div class="id_sch_r">
        <form action="{{ URL::route('search') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="search" name="keywords" placeholder="输入搜索关键词" value="">
            <button type="submit">搜索</button>
        </form>
    </div>
</div>

<!--banner栏-->
<div class="w980 idx_banner">
    <div class="idb_left">
        <div class="swiper-container banner_sd">
            <div class="swiper-wrapper">
                @if($pic_article_list!='none' && is_array($pic_article_list))
                    @foreach($pic_article_list as $pic_article)
                        <div class="swiper-slide">
                            <a href="{{ URL::to('/article').'/'.$pic_article['key'] }}" alt="{{ $pic_article['article_title'] }}">
                                <img src="{{ $pic_article['thumb'] }}">
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="idb_right">
        <div class="idbr_top">
            <ul>
                @foreach($recommend_list as $k=> $recommend)
                    <li @if($k == 0)class="idbr_topsd"@endif>{{ $recommend['channel_title'] }}</li>
                @endforeach
            </ul>
            <a href="javascript:void(0)">更多>></a>
        </div>
        <div class="idbr_down">
            暂无文章
            {{--@foreach($recommend_list as $k=> $recommend)
                @if($recommend['article_list'] != 'none' && is_array($recommend['article_list']))
                    <ul>
                        @foreach($recommend['article_list'] as $k=> $recommend)
                            <li>
                                <span class="idbrd_l">
                                    <a href="{{ URL::to('/article').'/'.$recommend['key'] }}" target="_blank">{{ $recommend['article_title'] }}</a>
                                </span>
                                <span class="idbrd_r">{{ $recommend['publish_date'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    暂无文章
                @endif
            @endforeach--}}
        </div>
    </div>
</div>
<!--视频部分-->
<div class="w980 idx_vedio">
    <div class="vd_left">
        <span class="vd_tit">宣传视频</span>
        <span class="vid_more">更多视频>></span>
        <video src="images/cs.mp4"  controls="controls" width="300" height="200"></video>
        <span style="display: block;">市公证协会举办全市公证业务培训</span>
    </div>

    <div class="vd_mid">
        <span class="vd_tit">推荐链接</span>
            <ul>
                <li class="ico_1" onclick="javascript: window.open('#'); ">
                    <span>公务员普法在线学习</span>
                </li>
                <li class="ico_2" onclick="javascript: window.open('http://www.64365.com/lawtxt/'); ">
                    <span>常用法律文书格式</span>
                </li>
                    <li class="ico_3" onclick="javascript: window.open('http://search.chinalaw.gov.cn/search.html'); ">
                    <span>法律法规查询</span>
                </li>
                <li class="ico_4" onclick="javascript: window.open('http://www.sfjz.com.cn/'); ">
                    <span>社区矫正</span>
                </li>
            </ul>
    </div>

    <div class="vd_right">
        <span class="vd_tit">官方微信</span>
        <img src="images/ew.png" width="160">
        <span class="vr_sp">扫一扫</span>
        <span class="vr_sp">关注「三门峡司法局」公众号</span>
        <span class="vr_sp">查询事务更方便！</span>
    </div>
</div>
<!--新闻-->
<div class="w980 news_2">
    <div class="new2_left">
        <div class="idbr_top">
            <ul>
                <li class="idbr_topsd">热点新闻</li>
                <li>业务动态</li>
                <li>公告通知</li>
                <li>公告通知</li>
                <li>公告通知</li>
                <li>公告通知</li>
            </ul>
        </div>
        <div class="idbr_down">
            <ul>
                暂无数据
                {{--<li>
                    <span class="idbrd_l">中信公证处积极开展公证宣传活动</span>
                    <span class="idbrd_r">2017-01-14</span>
                </li>--}}
            </ul>
        </div>
    </div>
    <div class="new2_right vd_mid">
        <span class="vd_tit">网上办事</span>
        <ul>
            <li class="ico_1">
                <span>律师事务</span>
            </li>
            <li class="ico_btn">
                <div class="ib_top">群众预约援助</div>
                <div class="ib_down">公检法指派援助</div>
            </li>
            <li class="ico_3">
                <span>司法鉴定</span>
            </li>
            <li class="ico_4">
                <span>政民互动</span>
            </li>
        </ul>
    </div>
</div>

<!--底部滚动-->
<div class="ft_mall w980">
    <span class="vd_tit">图片中心</span>
    <div class="ft_sid swiper-container w980">
        <ul class="swiper-wrapper">
            {{--<li class="swiper-slide"><img src="images/csftsd.png"><span>风雨历程二十载 同心同德铸辉煌</span></li>--}}
        </ul>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>


<div class="ft_last w980">
    <span class="vd_tit">友情链接</span>
    <div class="last_link">
        @if($flink_type_list != 'none' && is_array($flink_type_list))
            @foreach($flink_type_list as $key=> $flink_type)
                <span>
                    <select onchange="jumpToFlink($(this))" style="text-align: center">
                        <option value="type">==&nbsp;&nbsp;{{ $flink_type }}&nbsp;&nbsp;==</option>
                        @foreach($flinks_list[$key] as $flinks)
                            <option value="{{ $flinks['link'] }}" style="text-align: center">==&nbsp;&nbsp;{{ $flinks['title'] }}&nbsp;&nbsp;==</option>
                        @endforeach
                    </select>
                </span>
            @endforeach
        @endif
    </div>
    <div class="ft_sidlast swiper-container w980">
        @if(isset($img_flink_list) && is_array($img_flink_list))
            <ul class="swiper-wrapper">
                @foreach($img_flink_list as $img_flink)
                    <li class="swiper-slide">
                        <a href="{{ $img_flink['links'] }}" target="_blank">
                            <img src="{{ $img_flink['image'] }}" alt="{{ $img_flink['title'] }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

<!--底部-->
@include('judicial.web.chips.foot')
<script>
    var swiper_banner = new Swiper('.banner_sd', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        direction: 'vertical',
        loop:true
    });
    var swiper_ft = new Swiper('.ft_sid', {
        nextButton: '.ft_sid .swiper-button-next',
        prevButton: '.ft_sid .swiper-button-prev',
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 0
    });
    var swiper_last = new Swiper('.ft_sidlast', {
        nextButton: '.ft_sidlast .swiper-button-next',
        prevButton: '.ft_sidlast .swiper-button-prev',
        slidesPerView: 6,
        paginationClickable: true,
        spaceBetween: 0
    });

</script>
</body>
</html>