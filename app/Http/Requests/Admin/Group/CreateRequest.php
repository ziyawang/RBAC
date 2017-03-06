<?php

namespace App\Http\Requests\Admin\Group;

use App\Http\Requests\Admin\Request;

class CreateRequest extends Request
{

    public function rules()
    {
        return [
            'name' => 'required|max:100|unique:groups,name',
            'display_name' => 'sometimes|max:100',
            'description' => 'sometimes|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户组名称必须',
            'name.max' => '用户组名称最多100个字符',
            'name.unique' => '该用户组名称已存在',
            'display_name.max' => '用户组显示名称最多100个字符',
            'description.max' => '用户组说明最多100字符'
        ];
    }
}
