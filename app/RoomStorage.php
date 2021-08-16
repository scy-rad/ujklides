<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomStorage extends Model
{
    public function room() {
        return $this->hasOne(Room::class,'id','room_id')->get()->first();
    }
    function items_current() {
        return $this->hasMany(Item::class,'room_storage_current_id');//->get();
    }
    function items(){
        return $this->hasMany(Item::class,'room_storage_id');//->select('*','items.id as id_item')
        //->Join('item_groups', 'items.item_group_id', '=', 'item_groups.id');
    }
}
