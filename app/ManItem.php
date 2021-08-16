<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inventory;
use App\Item;
use App\Room;
use App\InventoryItem;
use Illuminate\Support\Facades\Auth;

class ManItem extends Model
{
    
    function get_rooms(Int $centerID)
    {
    return DB::table('rooms')
    ->where('center_id', '=', $centerID )
    ->get();
    }

    function make_inventory(Int $RoomID)
    {
        $zmEQ = new Inventory();
        $zmEQ->room_id = $RoomID;
        $zmEQ->inventory_name = 'nazwa inwentaryzacji';
        $zmEQ->inventory_date = '2020-12-16';
        $zmEQ->inventory_description = 'opis inwentaryzacji';
        $zmEQ->inventory_status = '1';  //rozpoczęta
        $zmEQ->save();
        return $zmEQ;
    }


    function add_inventory_to_items(Int $RoomID, Int $InvID, Int $InvType, String $InvDescr, String $InvDate)
    {
        $items_list = room::where('id',$RoomID)->first()->own_items()->get(); //get own items for room
        $ret=$RoomID.' - ';
        $count=0;

        foreach ($items_list as $one_item)
            {
                if (InventoryItem::where('item_id', '=', $one_item->id)
                ->where('inventory_id', '=', $InvID)
                ->exists()) 
                    {
                    
                    }
                 else
                    {
                    $count++;

                    $zmEQ = new InventoryItem();
                    $zmEQ->inventory_id=$InvID;
                    $zmEQ->item_id=$one_item->id;
                    $zmEQ->user_id=Auth::id();
                    $zmEQ->inventory_item_type_id=$InvType;
                    $zmEQ->inventory_item_description=$InvDescr;
                    $zmEQ->inventory_item_status=0;
                    $zmEQ->inventory_item_date=$InvDate;
                    $zmEQ->save();
                    }
            }
        if ($count>0)
            $ret.="ilość dodanych pozycji: $count.";
        else
            $ret.="nic nie dodano";
        return $ret;
    }

    

}
