<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    public function users() {
        return $this->belongsToMany(User::class, 'roles_has_users', 'roles_has_users_roles_id', 'roles_has_users_users_id')->withTimestamps()->get();
    }
    
    public static function find_by_name($role_name) {
        return Roles::select('id')->where('roles_name', $role_name)->first()->id;
        }
}