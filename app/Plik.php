<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plik extends Model
{
    public function groups() {
        return $this->hasMany(PlikForGroup::class,'plik_id');//->get();
    }
    public function items() {
        return $this->hasMany(PlikForItem::class,'plik_id');//->get();
    }
    public function rooms() {
        return $this->hasMany(PlikForRoom::class,'plik_id');//->get();
    }

}
