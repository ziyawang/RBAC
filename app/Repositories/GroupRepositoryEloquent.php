<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GroupRepository;
use App\Models\Group;

/**
 * Class GroupRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * delete group
     * @param $id
     * @return bool|int
     */
    public function delete($id)
    {
        $group = $this->model->find($id);
        if(!$group) {
            return false;
        }
        $group->users()->detach();
        return parent::delete($id);
    }

    /**
     * update group
     * @param array $attributes
     * @param $id
     * @return array
     */
    public function update(array $attributes, $id)
    {
        $isAble = $this->model->where('id', '<>', $id)->where('name', $attributes['name'])->count();
        if($isAble) {
            return [
                'status' => false,
                'msg' => '用户组名已被使用'
            ];
        }


        $data = [];
        $data['name'] = $attributes['name'];
        $data['display_name'] = $attributes['display_name'];
        $data['description'] = $attributes['description'];
        $result = parent::update($data, $id);
        if(!$result) {
            return [
                'status' => false,
                'msg' => '用户组更新失败'
            ];
        }

        return ['status' => true];
    }

    /**
     * save group permissions
     * @param $id
     * @param array $perms
     * @return bool
     */
    public function saveRoles($id, $perms = [])
    {
        $group = $this->model->find($id);
        $group->perms()->sync($perms);

        return true;
    }

    /**
     * get group's permissions
     * @param $id
     * @return array
     */
    public function groupRoles($id)
    {
        $perms = [];
        $permissions = $this->model->find($id)->perms()->get();

        foreach ($permissions as $item) {
            $perms[$item->id] = $item->name;
        }

        return $perms;
    }
}
