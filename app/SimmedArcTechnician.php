<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SimmedArcTechnician extends Model
{
    //
    public $timestamps = true;

    function technician()
    {
        return $this->hasOne(User::class,'id','technician_id')->get()->first();
    }

    function changer()
    {
        return $this->hasOne(User::class,'id','user_id')->get()->first();
    }

    function name_of_technician()
    {
    if ($this->technician_id>0)
        return $this->hasOne(User::class,'id','technician_id')->get()->first()->full_name();
    else
        return '- - -';
    }

    function name_of_changer()
    {
    if ($this->user_id>0)
        return $this->hasOne(User::class,'id','user_id')->get()->first()->name;
    else
        return '- - -';
    }

}
