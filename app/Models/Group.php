<?php

namespace App\Models;

use Zizaco\Entrust\EntrustGroup;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Group extends EntrustGroup implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * belongs to many for admin_user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\AdminUser');
    }

}