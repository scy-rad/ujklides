<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Equipments extends Model
{
    public function get_abc()
    {
    $retry = DB::table('equipments')
    ->select('*')
//    ->where('lib_type', '=', 1 )
//    ->orderBy('lastname', 'asc')
    ->get();
    return $retry;
    }

}
