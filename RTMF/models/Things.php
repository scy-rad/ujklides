<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Things extends Model
{
    function get_type(Things $thing)
    {
    $retry = DB::table('thing_types')
    ->select('*')
    ->where('thing_types.id', '=', $thing->things_thing_types_id )
    ->first();
    return $retry;
    }
	
	function get_room(Things $thing){

        $retry = DB::table('rooms')
        ->select('*')
        ->where('rooms.id', '=', $thing->things_room_id)
        ->first();
        return $retry;
    }
}
