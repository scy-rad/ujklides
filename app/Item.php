<?php
//php71-cli artisan make:model Item -mc
//php71-cli artisan make:model ItemType -mc
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
  
    public function group() {
        return $this->hasOne(ItemGroup::class,'id','item_group_id')->get()->first();
    }


    public function storage() {
        return $this->hasOne(RoomStorage::class,'id','room_storage_id')->get()->first();
    }
    
    public function current_storage() {
        return $this->hasOne(RoomStorage::class,'id','room_storage_current_id')->get()->first();
    }
    public function photo_OK() {
        if ($this->item_photo!='')
            return $this->item_photo;
        if ($this->group()->item_group_photo!='')
            return $this->group()->item_group_photo;
        //if ($this->group()->type()->item_type_photo!='')
        //    return $this->group()->type()->item_type_photo;
        return $this->group()->type()->photo_OK();
        
            return "_no_photo.png";
    }

    public function galleries() {
        //dump($this->id);
        //dump($this->belongsToMany(Gallery::class, 'gallery_for_items', 'items_id', 'galleries_id')->toSql());
        return $this->belongsToMany(Gallery::class, 'gallery_for_items', 'item_id', 'gallery_id')->withTimestamps();//->get();
    }


    function inventories()
    {
        return $this->hasMany(InventoryItem::class,'item_id');//->select('*','items.id as id_item')
        //->Join('item_groups', 'items.item_group_id', '=', 'item_groups.id');
        
    }
    function active_inventory()
    {
        //dump($this->hasMany(InventoryItem::class,'item_id')->toSql());
        return $this->hasMany(InventoryItem::class,'item_id')->first();//->where('item_id', '=', $item_id);
        //->Join('item_groups', 'items.item_group_id', '=', 'item_groups.id');
    }

    function open_faults()
    {
        return $this->hasMany(Fault::class,'item_id')->where('fault_status', '<>', 100);
    }
    function close_faults()
    {
        return $this->hasMany(Fault::class,'item_id')->where('fault_status', '=', 100);
    }

    function active_reviews()
    {
        return $this->hasMany(Review::class,'item_id')->where('rev_status', '<>', 100);
    }

       

    function get_inventory($item_id)
    {
    $retry = DB::table('inventories')
    ->join('inventory_items', 'inventories.id', '=', 'inventory_id')
    ->select('*')
    ->where('inventory_status', '=', '1')
    ->where('item_id', '=', $item_id);
    return $retry;
    }

/*

    function get_type_id(String $type_name)
    {
    dump('usuń tą funkcję (get_type_id) model item.php');
    $retry = DB::table('item_types')
    ->select('*')
    ->where('item_type_name', '=', $type_name)
    ->first()->id;
    return $retry;
    }

    function get_items_by_type(Int $type_id)
    {
    dump('usuń tą funkcję (get_items_by_type) model item.php');
    $retryX = DB::table('items')
    ->join('item_groups', 'item_groups.id', '=', 'item_group_id')
    ->join('item_types', 'item_types.id', '=', 'item_type_id')
    ->select('*','items.id AS item_ID', 
    DB::raw('IF(`item_photo`="",IF(`item_group_photo`="",IF(`item_type_photo`="","_no_photo.png",item_type_photo),item_group_photo),item_photo) AS photo_OK'));

    if ($type_id>0)
        $retry = $retryX->where('item_type_master_id', '=', $type_id )->get();
    else
        $retry = $retryX->get();
    
    return $retry;
    }

    function get_item(Item $item)
    {
    dump('usuń tą funkcję (get_items) model item.php');
    $retry = DB::table('items')
    ->join('item_groups', 'item_groups.id', '=', 'item_group_id')
    ->join('item_types', 'item_types.id', '=', 'item_type_id')
    ->select('*','items.id AS item_ID', 
    DB::raw('IF(`item_photo`="",IF(`item_group_photo`="",IF(`item_type_photo`="","_no_photo.png",item_type_photo),item_group_photo),item_photo) AS photo_OK'))
    ->where('items.id', '=', $item->id )
    ->get()->first();
    return $retry;
    }
*/
} //model Item
