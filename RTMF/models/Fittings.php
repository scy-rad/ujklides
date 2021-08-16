<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Fittings extends Model
{
    function get_room($fitting){

        $retry = DB::table('rooms')
        ->select('*')
        ->where('rooms.id', '=', $fitting->fittings_room_id)
        ->first();
        return $retry;
    }

}


?>