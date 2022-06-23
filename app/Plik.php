<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plik extends Model
{
    public function groups() {
        return $this->hasMany(PlikForGroupitem::class,'plik_id')->whereNull('item_id');//->get();
    }
    public function items() {
        return $this->hasMany(PlikForGroupitem::class,'plik_id')->whereNull('item_group_id');//->get();
    }
    public function rooms() {
        return $this->hasMany(PlikForRoom::class,'plik_id');//->get();
    }

}
