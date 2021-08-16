<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlikType extends Model
{
    public function pliks() {
        return $this->hasMany(Plik::class);//->get();
    }
}
