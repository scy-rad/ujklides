<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlikForGroupitem extends Model
{
    //
    // public function plik() {
    //     return $this->hasOne(Plik::class,'id','plik_id');//->get()->first();
    // }
    public function group() {
        return $this->hasOne(ItemGroup::class,'id','item_group_id');//->get()->first();
    }

}
