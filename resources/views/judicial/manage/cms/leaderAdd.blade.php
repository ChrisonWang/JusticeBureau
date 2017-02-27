<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            领导简介管理/新增
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="leaderAddForm">
            <div class="form-group">
                <label for="leader_name" class="col-md-1 control-label">姓名：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="leader_name" name="leader_name" placeholder="请输入领导姓名" />
                </div>
            </div>
            <div class="form-group">
                <label for="leader_job" class="col-md-1 control-label">岗位：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="leader_job" name="leader_job" placeholder="请输入领导岗位" />
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-md-1 control-label">排序权重：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort" name="sort" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label for="leader_photo" class="col-md-1 control-label">照片：</label>
                <div class="col-md-3">
                    <i class="fa fa-paperclip"></i>上传头像图片
                    <input type="file" id="upload_photo" class="btn btn-default btn-file" name="leader_photo" onchange="upload_img($(this))"/>
                </div>
            </div>
            <div class="form-group hidden" id="image-thumbnail">
                <label for="image-holder" class="col-md-1 control-label">预览：</label>
                <div class="col-md-3" id="image-holder"></div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-1 control-label">简介：</label>
                <div class="col-md-8">
                    <script id="UE_Content" name="description" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addLeaderNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="submit" class="btn btn-info btn-block" onclick="addLeader()">确认</button>
                </div>
                <div class="col col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-leaderIntroduction" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var UE_Content = UE.getEditor('UE_Content');
</script>