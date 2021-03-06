<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            后台推荐链接管理/查看
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="recommendEditForm">
            <input type="hidden" value="{{ $r_detail['key'] }}" name="key"/>
            <div class="form-group">
                <label for="r_title" class="col-md-2 control-label">标题：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="r_title" name="r_title" value="{{ $r_detail['r_title'] }}" placeholder="请输入链接标题" />
                </div>
            </div>
            <div class="form-group">
                <label for="r_link" class="col-md-2 control-label">链接：</label>
                <div class="col-md-3">
                    <input disabled type="text" class="form-control" id="r_link" name="r_link" value="{{ $r_detail['r_link'] }}" placeholder="请输入链接地址" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-2 control-label">创建时间：</label>
                <div class="col-md-3">
                    <label for="create_date" class="control-label">{{ $r_detail['create_date'] }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="recommendEditNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-recommendLink" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>