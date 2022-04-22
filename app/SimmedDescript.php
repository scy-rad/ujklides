<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SimmedDescript extends Model
{

    public $timestamps = true;

    public function simmed() {
        return $this->hasOne(Simmed::class,'id','simmed_id')->get()->first();
    }
}
