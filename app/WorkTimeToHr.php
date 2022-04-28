<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WorkTimeToHr extends Model
{
    //
    public $timestamps = true;

    // public function type_name() {
    //     return $this->hasOne(UserPhoneType::class,'id','user_phone_type_id');//->get()->first();
    // }
    public function type() {
        return $this->belongsTo(WorkTimeType::class, 'work_time_types_id');//->first();
    }


}
