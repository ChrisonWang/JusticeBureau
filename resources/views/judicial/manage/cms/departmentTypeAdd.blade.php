<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构分类管理
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="typeAddForm">
            <div class="form-group">
                <label for="typeName" class="col-md-1 control-label">名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="typeName" name="typeName" placeholder="请输入分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="create_date" class="col-md-1 control-label">创建时间：</label>
                <div class="col-md-3">
                    <p id="create_date">自动生成</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-3">
                    <p class="text-left hidden" id="addTypeNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-1">
                    <button type="button" class="btn btn-info btn-block" onclick="addDepartmentType()">确认</button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-departmentType" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>