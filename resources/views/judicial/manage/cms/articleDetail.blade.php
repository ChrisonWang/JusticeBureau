<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            文章管理/查看
        </h3>
    </div>
    <!--隐藏的模板-->
    <div id="node-row" hidden >
        <table class="table table-bordered table-hover table-condensed">
            <tbody class="text-center">
            <tr>
                <td>
                    <input type="text" class="form-control" name="file-name[]" placeholder="请输入附件名称" />
                </td>
                <td>
                    <input type="file" class="btn btn-default form-control" name="file[]"/>
                </td>
                <td>
                    <a href="javascript: void(0) ;" onclick="delRow($(this))">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="panel-body">
        <form class="form-horizontal" id="articleEditForm">
            <input type="hidden" value="{{ $article_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="article_title" class="col-md-2 col-sm-3 control-label">标题：</label>
                <div class="col-md-3">
                    <input type="text" disabled class="form-control" id="article_title" name="article_title" value="{{ $article_detail['article_title'] }}" placeholder="请输入文章标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="disabled" class="col-md-2 col-sm-3 control-label">是否发布：</label>
                <div class="col-md-3">
                    <input type="checkbox" class="" id="disabled" name="disabled" value="no" @if($article_detail['disabled'] == 'no') checked @endif/>
                </div>
            </div>
            <div class="form-group">
                <label for="publish_date" class="col-md-2 col-sm-3 control-label">发布时间：</label>
                <div class="col-md-3">
                    <input id="publish_date" class="form-control" name="publish_date" type="text" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 col-sm-3 control-label">创建人：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $manager['nickname'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <label for="tags" class="col-md-2 col-sm-3 control-label">关联标签：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label text-left" style="text-align: left">
                        @if(is_array($article_detail['tags']) && $article_detail['tags']!='')
                            @foreach($article_detail['tags'] as $tag_title)
                                {{ $tag_title }}&nbsp;&nbsp;
                            @endforeach
                        @endif
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="channel_id" class="col-md-2 col-sm-3 control-label">频道：</label>
                <div class="col-md-3">
                    <select disabled name="channel_id" class="form-control" onchange="getSubChannel($(this), $('#sub_channel_id'))">
                        @foreach($channel_list as $channel)
                            <option value="{{ $channel['channel_key'] }}" @if($article_detail['channel_id'] == $channel['channel_key']) selected @endif>{{ $channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sub_channel_id" class="col-md-2 col-sm-3 control-label">二级频道：</label>
                <div class="col-md-3">
                    <select disabled id="sub_channel_id" name="sub_channel_id" class="form-control">
                        @foreach($sub_channel_list as $sub_channel)
                            <option value="{{ $sub_channel['channel_key'] }}" @if($article_detail['sub_channel_id'] == $sub_channel['channel_key']) selected @endif>{{ $sub_channel['channel_title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if( isset($article_detail['thumb']) && $article_detail['thumb'] != "none" )
                <div class="form-group" id="image-thumbnail">
                    <label for="image-holder" class="col-md-2 col-sm-3 control-label">预览：</label>
                    <div class="col-md-8" id="image-holder">
                        <img src="{{ $article_detail['thumb'] }}" class="img-thumbnail img-responsive">
                    </div>
                </div>
            @else
                <div class="form-group hidden" id="image-thumbnail">
                    <label for="leader_photo" class="col-md-2 col-sm-3 control-label">预览：</label>
                    <div class="col-md-8" id="image-holder"></div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-md-2 col-sm-3 control-label">附件：</label>
                <div class="col-md-8">
                    <div class="container-fluid" style="margin-left: 0; padding-left: 0">
                        @if($article_detail['files'] != 'none' && is_array($article_detail['files']))
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th class="text-center">标题</th>
                                    <th class="text-center">附件</th>
                                </tr>
                                </thead>
                                <tbody class="text-center" id="menu-nodes">
                                @foreach($article_detail['files'] as $files)
                                    <tr>
                                        <td>
                                            <input type="text" disabled class="form-control" style="height: 40px" name="file-name" value="{{ $files['filename'] }}" placeholder="请输入附件名称" />
                                        </td>
                                        <td>
                                            <input type="file" disabled class="btn btn-default btn-file" name="file"/>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            无附件！
                        @endif
                    </div>
                    <div class="container-fluid">
                        <p class="text-left hidden" id="add-row-notice" style="color: red"></p>
                    </div>
                    <div class="container-fluid">
                        <hr/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-2 col-sm-3 control-label">正文：</label>
                <div class="col-md-10">
                    <script id="UE_Content" name="content" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 col-sm-3 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $article_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <label class="text-left hidden control-label" id="articleEditNotice" style="color: red"></label>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col col-md-offset-1 col-md-2">
                    @if($archived == 'yes')
                        <button type="button" class="btn btn-danger btn-block" data-key="{{ $archived_key }}" data-method="show" onclick="archivedMethod($(this))">返回列表</button>
                    @else
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-articleMng" onclick="loadContent($(this))">返回列表</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        UE.delEditor('UE_Content');
        var UE_Content = UE.getEditor('UE_Content',{
            'readonly': true,
        });
        UE_Content.ready(function(){
            var value = '{!! $article_detail['content'] !!}';
            UE_Content.execCommand('insertHtml',value);
        });
    });

    var logic = function( currentDateTime ){
        if (currentDateTime && currentDateTime.getDay() == 6){
            this.setOptions({
                minTime:'11:00'
            });
        }else
            this.setOptions({
                minTime:'8:00'
            });
    };
    $('#publish_date').datetimepicker({
        value: '{{ $article_detail['publish_date'] }}',
        lang: 'zh',
        format: "Y-m-d H:i",
        onChangeDateTime: logic,
        onShow: logic,
        todayHighlight: true,
    }).setLocale('zh');

</script>