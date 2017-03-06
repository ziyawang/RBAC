<?php namespace App\Presenters;

use App\Repositories\GroupRepositoryEloquent;
use App\Transformers\GroupTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class GroupPresenter
 *
 * @package namespace App\Presenters;
 */
class GroupPresenter extends FractalPresenter
{
    protected $group;

    public function __construct(GroupRepositoryEloquent $group)
    {
        $this->group = $group;
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new GroupTransformer();
    }

    /**
     *  Groups checkbox
     * @param array $groups
     * @return string
     */
    public function groupsCheckbox($hasGroups = [])
    {
        $groups = $this->group->all();
        if(!$groups->count()) {
            return '<a class="btn btn-danger-alt btn-xs" href="'.route('admin.group.create').'"> <i class="fa fa-hand-o-right"> 添加用户组</i></a>';
        }

        $checkbox = '';
        foreach ($groups as $group) {
            if(in_array($group->name,$hasGroups)) {
                $checkbox .= '<div class="checkbox block"><label><input type="checkbox" checked="checked"  name="groups[]" value="' . $group->id . '">' . $group->display_name . '</label></div>';
            } else {
                $checkbox .= '<div class="checkbox block"><label><input type="checkbox" name="groups[]" value="' . $group->id . '">' . $group->display_name . '</label></div>';
            }
        }

        return $checkbox;
    }
}
