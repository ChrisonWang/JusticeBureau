<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            机构简介管理
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" id="departmentAddForm">
            <div class="form-group">
                <label for="department_name" class="col-md-2 control-label"><strong style="color: red">*</strong> 名称：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="department_name" name="department_name" placeholder="请输入分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="type_id" class="col-md-2 control-label"><strong style="color: red">*</strong> 分类：</label>
                <div class="col-md-3">
                    <select id="type_id" name="type_id" class="form-control">
                        @foreach ($type_list as $type)
                        <option value="{{ $type['type_id'] }}">{{ $type['type_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{--<div class="col-md-1">
                    <button type="button" class="btn btn-default btn-block" data-tag-key='none' data-method="add" onclick="typeMethod($(this))">新增分类</button>
                </div>--}}
            </div>
            <div class="form-group">
                <label for="typeName" class="col-md-2 control-label">排序：</label>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort" value="0" name="sort" placeholder="请输入权重（数字越大越靠前）" />
                </div>
            </div>
            <div class="form-group">
                <label for="UE_Content" class="col-md-2 control-label"><strong style="color: red">*</strong> 简介：</label>
                <div class="col-md-8">
                    <script id="UE_Content" name="description" type="text/plain"></script>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-10">
                    <p class="text-left hidden" id="addDepartmentNotice" style="color: red"></p>
                </div>
            </div>
            <div class="form-group">
                <hr/>
                <div class="col-md-offset-1 col-md-2">
                    <button type="button" class="btn btn-info btn-block" onclick="addDepartment()">确认</button>
                </div>
                <div class="col col-md-2">
                    <button type="button" class="btn btn-danger btn-block" data-node="cms-department" onclick="loadContent($(this))">返回列表</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        UE.delEditor('UE_Content');
        var UE_Content = UE.getEditor('UE_Content');
    });
</script>