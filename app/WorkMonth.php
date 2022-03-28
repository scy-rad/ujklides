<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WorkMonth extends Model
{
    //
    public $timestamps = true;

    function owner()
    {
        return $this->hasOne(User::class,'id','user_id')->get()->first();
    }

}
