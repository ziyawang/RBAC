@extends('layouts.admin-app')

@section('content')
    <style>
        .sub-permissions-ul li {
            float: left;

        }
    </style>
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> RBAC <span>系统设置</span></h2>
        {!! Breadcrumbs::render('admin-group-role') !!}
    </div>

    <div class="contentpanel">

        <div class="row">

            @include('admin._partials.rbac-left-menu')

            <div class="col-sm-9 col-lg-10">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="" class="panel-close">×</a>
                            <a href="" class="minimize">−</a>
                        </div>
                        <h4 class="panel-title">编辑[{{ $group->display_name }}]权限</h4>
                    </div>

                    <form action="{{ route('admin.group.roles',['id'=>$group->id]) }}" method="post"
                          id="group-roles-form">
                        <div class="panel-body panel-body-nopadding">
                                <div class="col-sm-6">
                                    @inject('rolePresenter','App\Presenters\RolePresenter')

                                    {!! $rolePresenter->rolesCheckbox($hasRoles) !!}
                                </div>
                            {{ csrf_field() }}
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <button class="btn btn-primary" id="save-group-roles">保存</button>
                                </div>
                            </div>
                        </div><!-- panel-footer -->

                    </form>

                </div>

            </div><!-- col-sm-9 -->

        </div><!-- row -->

    </div>

@endsection

@section('javascript')
    @parent
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script>
        $(".display-sub-role-toggle").toggle(function () {
            $(this).children('span').removeClass('glyphicon-minus').addClass('glyphicon-plus')
                    .parents('.top-role').next('.sub-roles').hide();
        }, function () {
            $(this).children('span').removeClass('glyphicon-plus').addClass('glyphicon-minus')
                    .parents('.top-role').next('.sub-roles').show();
        });

        $(".top-role-checkbox").change(function () {
            $(this).parents('.top-role').next('.sub-roles').find('input').prop('checked', $(this).prop('checked'));
        });

        $(".sub-role-checkbox").change(function () {
            if ($(this).prop('checked')) {
                $(this).parents('.sub-roles').prev('.top-role').find('.top-role-checkbox').prop('checked', true);
            }
        });
    </script>
    <script type="text/javascript">
        $("#save-group-roles").click(function (e) {
            e.preventDefault();
            Rbac.ajax.request({
                href: $("#group-roles-form").attr('action'),
                data: $("#group-roles-form").serialize(),
                successTitle: '用户组权限保存成功'
            });
        });
    </script>
@endsection