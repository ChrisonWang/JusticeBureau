<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th width="20%" class="text-center">操作</th>
        <th class="text-center">名称</th>
        <th class="text-center">负责人</th>
        <th class="text-center">统一社会信用代码</th>
        <th class="text-center">类型</th>
        <th class="text-center">区域</th>
        <th class="text-center">状态</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @foreach($office_list as $office)
        <tr>
            <td>
                <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="show" onclick="lawyerOfficeMethod($(this))">查看</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="edit" onclick="lawyerOfficeMethod($(this))">编辑</a>
                &nbsp;&nbsp;
                <a href="javascript: void(0) ;" data-key="{{ $office['key'] }}" data-method="delete" data-title="{{ $office['name'] }}" onclick="lawyerOfficeMethod($(this))">删除</a>
            </td>
            <td>{{ $office['name'] }}</td>
            <td>{{ isset($office['director']) ? $office['director'] : '-' }}</td>
            <td>{{ isset($office['usc_code']) ? $office['usc_code'] : '-' }}</td>
            <td>{{ isset($area_list)&&is_array($area_list) ? $area_list[$office['area_id']] : '-' }}</td>
            <td>{{ isset($type_list[$office['type']]) ? $type_list[$office['type']] : '-'}}</td>
            <td>{{ $office['status']=='normal' ? '正常' : '注销' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>