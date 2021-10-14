<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    //

    function storages()
    {
    return $this->hasMany(RoomStorage::class,'room_id');//->get();
    }

    function all_items()
    {
    return Item::whereIn('room_storage_id',RoomStorage::where('room_id','=',$this->id)->pluck('id'))//;//->get();
    ->orWhereIn('room_storage_current_id',RoomStorage::where('room_id','=',$this->id)->pluck('id'));//->get();
    }

    function own_items()
    {
    return Item::whereIn('room_storage_id',RoomStorage::where('room_id','=',$this->id)->pluck('id'));
    }

    function galleries()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_for_rooms', 'room_id', 'gallery_id')->withTimestamps();
    }


    public static function find_xp_room($room_xp_code) {
        $room = Room::where('room_xp_code',$room_xp_code);
        if ($room->first()!==NULL)
            return $room->first()->id;
        return 0;
        }

    public static function find_xls_room($room_xls_number) {
        $room = Room::where('room_number',$room_xls_number);
        if ($room->first()!==NULL)
            return $room->first()->id;
        return 0;
        }



    public static function json_room_education() {
        //$rooms=Room::whereIn('room_type_id',[11,21]);
        $rooms=Room::where('room_XP_code','<>','')->orderBy('room_number');

        $data=[];
        foreach ($rooms->get() as $rowroom)
            {
                $data[] = [
                    'id' => $rowroom->id,
                    'text' => $rowroom->room_number.' '.$rowroom->room_name
                ];
            }
        return json_encode($data);    
    }




    //return $this->hasMany(RoomStorages::class,'room_storages_room_id');//->get();


    function itemsByType(Int $MasterType)
    {
    return Item::select('*','items.id as id_item')->leftJoin('item_groups', 'item_groups.id', '=', 'items.item_group_id')
    ->whereIn('item_groups.item_type_id',ItemType::where('item_type_master_id','=',$MasterType)->pluck('id'))
    ->where(function($q) {
        $q->whereIn('room_storage_id',RoomStorage::where('room_id','=',$this->id)->pluck('id'))
          ->orWhereIn('room_storage_current_id',RoomStorage::where('room_id','=',$this->id)->pluck('id'));
        });
    }


    function get_rooms(Int $centerID)
    {
    dump('Usuń to zapytanie: get_rooms - model Rooms.php');
    return DB::table('rooms')
    ->where('rooms_center_id', '=', $centerID )
    ->get();
    }


    function get_Xitems(Int $room_id, string $aCase, string $aRoot)
    {
    dump('Usuń to zapytanie: get_Xitems - model Rooms.php');
    
        $inv_ID=1;

    $F_return=DB::table('items')
    ->join('item_groups', 'item_groups.id', '=', 'item_group_id')
    ->join('item_types', 'item_types.id', '=', 'item_group_type_id')
    ->join('room_storages', 'room_storages.id', '=', 'item_room_storage_id')
    ->join('rooms', 'rooms.id', '=', 'room_storages_room_id')
    ->leftJoin('inventory_items', function($join) use ($inv_ID) {
        $join->on('items.id', '=', 'inventory_items.inventory_item_item_id');
        $join->where('inventory_items.inventory_item_inventories_id', '=', $inv_ID);
    })
    ->select('*', 'items.id AS id_item', 'inventory_items.id AS id_inv');
    
    $Fx_present_foreign_sign='=';
    switch ($aCase)
        {
        case 'type':
            // echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera przedmioty danego typu (np. infrastruktura czy meble)
            $Fx_where_value=DB::table('item_types')
                ->where('item_type_name', '=', $aRoot )
                ->first()->id;
            $Fx_where_others=DB::table('room_storages')->select('id')->where('room_storages_room_id', $room_id);
                
            $F_return->where(function ($query) use ($room_id, $Fx_where_others) {
                $query->where('room_storages_room_id', '=', $room_id)
                ->orWhereIn('item_room_storage_current_id', $Fx_where_others);})
                ->where('item_type_master_id', '=', $Fx_where_value );
            
            
        break;
        case 'storage':
            //echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera przedmioty z danego magazynu (szafy,podłogi) - także te, które są wypożyczone gdzie indziej, ale nie tek, tóre są wypożyczone skąd indziej
            $F_return->where('room_storages.id', '=', intval($aRoot) );
        break;
        case 'only_present':
            echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera tylko te przedmioty, które są przypisane do sali i się w niej znajdują
            $F_return->where('room_storages_room_id', '=', $room_id )
                     ->where('item_room_storage_current_id', '=', $room_id );
        break;
        case 'foreign_out':
            echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera tylko te przedmioty, które są przypisane do sali i są wypożyczone do innych sal
            $F_return->where('room_storages_room_id', '=', $room_id )
                     ->where('item_room_storage_current_id', '<>', $room_id );
        break;
        case 'foreign_in':
            echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera tylko te przedmioty, które są wypożyczone z innych sal

            $F_return->where('room_storages_room_id', '<>', $room_id )
                     ->where('item_room_storage_current_id', '=', $room_id );
        break;
        case 'ALL':
            echo "<h1>look at the App/Rooms -> $aCase</h1>";
            // pobiera przedmioty, które są przypisane do sali (nawet jak są wypożyczone do innych sal)
            $F_return->where('room_storages_room_id', '=', $room_id )
                     ->where('room_storages_room_id', '>', 0 );
        break;
        }

    $XY=$F_return->orderBy('item_storage_shelf', 'desc')
        ->orderBy('item_type_master_id', 'asc')
        ->orderBy('item_type_parent_id', 'asc')
        ->orderBy('item_group_name', 'asc')
        ->orderBy('item_inventory_number', 'asc')
        ->get();

    return $XY;

    }



    function get_Xitems2(Int $room_id, string $aCase, string $aRoot)
    {
    dump('Usuń to zapytanie: get_Xitems2 - model Rooms.php');
    
        $Fx_present_foreign_sign='=';
    switch ($aCase)
        {
        case 'type':
            // pobiera przedmioty danego typu (np. infrastruktura czy meble)
            $Fx_where_name='item_type_master_id';
            $Fx_where_sign='=';
            $Fx_where_value=DB::table('item_types')
                ->where('item_type_name', '=', $aRoot )
                ->first()->id;
        break;
        case 'storage':
            // pobiera przedmioty z danego magazynu (szafy,podłogi)
            $Fx_where_name='room_storages.id';
            $Fx_where_sign='=';
            $Fx_where_value=intval($aRoot);
        break;
        case 'only_present':
            // pobiera tylko te przedmioty, które są przypisane do sali i się w niej znajdują
            $Fx_where_name='item_room_storage_current_id';
            $Fx_where_sign='=';
            $Fx_where_value=$room_id;
        break;
        case 'foreign_out':
            // pobiera tylko te przedmioty, które są przypisane do sali i są wypożyczone do innych sal
            $Fx_where_name='item_room_storage_current_id';
            $Fx_where_sign='<>';
            $Fx_where_value=$room_id;
        break;
        case 'foreign_in':
            // pobiera tylko te przedmioty, które są wypożyczone z innych sal
            $Fx_where_name='item_room_storage_current_id';
            $Fx_where_sign='=';
            $Fx_where_value=$room_id;
            $Fx_present_foreign_sign='<>';
        break;
        case 'ALL':
            // pobiera przedmioty, które są przypisane do sali (nawet jak są wypożyczone do innych sal)
            $Fx_where_name='room_storages_room_id';
            $Fx_where_sign='>';
            $Fx_where_value='0';
            $Fx_present_foreign_sign='=';
        break;
        }
    
        $inv_ID=1;

        /*
        ->leftJoin('inventory_items', function($join) use ($inv_ID) {
            $join->on('item.id', '=', 'inventory_items.inventory_item_item_id');
            $join->on('inventory_items.inventory_item_inventories_id', '=', $inv_ID);
        })
    
        $query->on('bookings.arrival', '=', $param1);
        $query->orOn('departure', '=',$param2);
        */
        
    /*    */

    return DB::table('items')
    ->join('item_groups', 'item_groups.id', '=', 'item_group_id')
    ->join('item_types', 'item_types.id', '=', 'item_group_type_id')
    ->join('room_storages', 'room_storages.id', '=', 'item_room_storage_id')
    ->join('rooms', 'rooms.id', '=', 'room_storages_room_id')
    ->leftJoin('inventory_items', function($join) use ($inv_ID) {
        $join->on('items.id', '=', 'inventory_items.inventory_item_item_id');
        $join->where('inventory_items.inventory_item_inventories_id', '=', $inv_ID);
    })
    ->select('*', 'items.id AS id_item', 'inventory_items.id AS id_inv')
    ->where('room_storages_room_id', $Fx_present_foreign_sign, $room_id )
    ->where($Fx_where_name, $Fx_where_sign, $Fx_where_value )
    ->orderBy('item_storage_shelf', 'desc')
    ->orderBy('item_type_master_id', 'asc')
    ->orderBy('item_type_parent_id', 'asc')
    ->orderBy('item_group_name', 'asc')
    ->orderBy('item_inventory_number', 'asc')
    ->get();
    }


function NO_get_type_name(Int $aID)
    {
    dump('Usuń to zapytanie: NO_get_type_name - model Rooms.php');
    
    /*    */
    return DB::table('item_types')
    ->select('*')
    ->where('id', '=', $aID )
    ->get()->first();
    }

 function get_type_name(Int $type_id)
    {
    dump('Usuń to zapytanie: get_type_name - model Rooms.php');
    $retry = DB::table('item_types')
    ->select('*')
    ->where('id', '=', $type_id)
    ->first()->item_type_name;
    return $retry;
    }

}
