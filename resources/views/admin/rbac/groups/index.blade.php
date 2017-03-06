@extends('layouts.admin-app')

@section('content')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> RBAC <span>系统设置</span></h2>
        {!! Breadcrumbs::render('admin-group-index') !!}
    </div>

    <div class="contentpanel panel-email">

        <div class="row">

            @include('admin._partials.rbac-left-menu')

            <div class="col-sm-9 col-lg-10">

                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="pull-right">
                            <div class="btn-group mr10">
                                <a href="{{ route('admin.group.create') }}" class="btn btn-white tooltips"
                                   data-toggle="tooltip" data-original-title="新增"><i
                                            class="glyphicon glyphicon-plus"></i></a>
                                <a class="btn btn-white tooltips deleteall" data-toggle="tooltip"
                                   data-original-title="删除" data-href="{{ route('admin.group.destroy.all') }}"><i
                                            class="glyphicon glyphicon-trash"></i></a>
                            </div>
                        </div><!-- pull-right -->

                        <h5 class="subtitle mb5">用户组列表</h5>
                        @include('admin._partials.show-page-status',['result'=>$groups])

                        <div class="table-responsive col-md-12">
                            <table class="table mb30">
                                <thead>
                                <tr>
                                    <th>
                                        <span class="ckbox ckbox-primary">
                                            <input type="checkbox" id="selectall"/>
                                            <label for="selectall"></label>
                                        </span>
                                    </th>
                                    <th>标识</th>
                                    <th>用户组名</th>
                                    <th>说明</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($groups as $group)
                                    <tr>
                                        <td>
                                            <div class="ckbox ckbox-default">
                                                <input type="checkbox" name="id" id="id-{{ $group->id }}"
                                                       value="{{ $group->id }}" class="selectall-item"/>
                                                <label for="id-{{ $group->id }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->display_name }}</td>
                                        <td>{{ $group->description }}</td>
                                        <td>{{ $group->created_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.group.edit',['id'=>$group->id]) }}"
                                               class="btn btn-white btn-xs"><i class="fa fa-pencil"></i> 编辑</a>
                                            <a href="{{ route('admin.group.roles',['id'=>$group->id]) }}"
                                            class="btn btn-info btn-xs group-permissions"><i class="fa fa-wrench"></i> 权限</a>
                                            <a class="btn btn-danger btn-xs group-delete"
                                               data-href="{{ route('admin.group.destroy',['id'=>$group->id]) }}">
                                                <i class="fa fa-trash-o"></i> 删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! $groups->render() !!}

                    </div><!-- panel-body -->
                </div><!-- panel -->

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>
@endsection

@section('javascript')
    @parent
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script type="text/javascript">
        $(".group-delete").click(function () {
            Rbac.ajax.delete({
                confirmTitle: '确定删除用户组?',
                href: $(this).data('href'),
                successTitle: '用户组删除成功'
            });
        });

        $(".deleteall").click(function () {
            Rbac.ajax.deleteAll({
                confirmTitle: '确定删除选中的用户组?',
                href: $(this).data('href'),
                successTitle: '用户组删除成功'
            });
        });
    </script>

@endsection
