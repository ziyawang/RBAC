<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class AdminUserGroup extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

    protected $table = 'admin_user_group';

}
