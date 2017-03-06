<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RoleRepositoryEloquent;
use App\Repositories\GroupRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\Admin\Group\CreateRequest;
use App\Http\Requests\Admin\Group\UpdateRequest;
use Toastr, Breadcrumbs;
use DB;
use PDO;

class GroupController extends BaseController
{
    protected $group;
    protected $role;

    public function __construct(GroupRepositoryEloquent $group, RoleRepositoryEloquent $role)
    {
        parent::__construct();
        $this->group = $group;
        $this->role = $role;

        Breadcrumbs::setView('admin._partials.breadcrumbs');

        Breadcrumbs::register('admin-group', function ($breadcrumbs) {
            $breadcrumbs->parent('dashboard');
            $breadcrumbs->push('用户组管理', route('admin.group.index'));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Breadcrumbs::register('admin-group-index', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-group');
            $breadcrumbs->push('用户组列表', route('admin.group.index'));
        });

        $groups = $this->group->paginate(10);
        return view('admin.rbac.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Breadcrumbs::register('admin-group-create', function ($breadcrumbs) {
            $breadcrumbs->parent('admin-group');
            $breadcrumbs->push('添加用户组', route('admin.group.create'));
        });

        return view('admin.rbac.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $result = $this->group->create($request->all());

        if(!$result) {
            Toastr::error('新用户组添加失败!');
            return redirect('admin/group/create');
        } else {
            Toastr::success('新用户组添加成功!');
            return redirect('admin/group');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Breadcrumbs::register('admin-group-edit', function ($breadcrumbs) use ($id) {
            $breadcrumbs->parent('admin-group');
            $breadcrumbs->push('编辑用户组', route('admin.group.edit', ['id' => $id]));
        });

        $group = $this->group->find($id);
        return view('admin.rbac.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $result = $this->group->update($request->all(), $id);
        if(!$result['status']) {
            Toastr::error($result['msg']);
        } else {
            Toastr::success('用户组更新成功');
        }
        return redirect(route('admin.group.edit', ['id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->group->delete($id);
        return response()->json($result ? ['status' => 1] : ['status' => 0]);
    }

    /**
     * Delete multi groups
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll(Request $request)
    {
        if(!($ids = $request->get('ids', []))) {
            return response()->json(['status' => 0, 'msg' => '请求参数错误']);
        }

        foreach ($ids as $id) {
            $result = $this->group->delete($id);
        }
        return response()->json($result ? ['status' => 1] : ['status' => 0]);
    }

    /**
     * Display a group's roles
     * @param $id
     * @return \Illuminate\View\View
     */
    public function role($id)
    {
        Breadcrumbs::register('admin-group-role', function ($breadcrumbs) use ($id) {
            $breadcrumbs->parent('admin-group');
            $breadcrumbs->push('编辑用户组权限', route('admin.group.roles', ['id' => $id]));
        });

        $group = $this->group->find($id);
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $hasRoles = DB::table("roles")->select('name')->get();
        DB::setFetchMode(PDO::FETCH_ASSOC);
        $groupRoles = $this->group->groupRoles($id);
        return view('admin.rbac.groups.roles', compact('group', 'roles', 'groupRoles','hasRoles'));
    }

    /**
     * Set group's roles
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeRoles($id, Request $request)
    {
        $result = $this->group->saveRoles($id, $request->input('roles', []));
        return response()->json($result ? ['status' => 1] : ['status' => 0]);
    }
}
