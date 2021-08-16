<?php
//php71-cli artisan make:controller DevitemsController --resource
namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Devitems extends Model
{
    //public $fillable = ['lib_group_id', 'photo', 'name', 'description', 'status'];


    function get_device(Devitems $devitem)
    {
            
    $retry = DB::table('devices')
    ->select('*')
    ->where('id', '=', $devitem->devitems_device_id )
    ->first();
    return $retry;
    }

    function get_room_storages(Devitems $devitem)
    {
            
    $retry = DB::table('room_storages')
    ->join('rooms', 'rooms.id', '=', 'room_storages_room_id')
    ->select('*')
    ->where('room_storages.id', '=', $devitem->devitems_room_storage_id )
    ->first();
    return $retry;
    }

    function get_docs(Devitems $devitem, Int $ID_doc)
    {
if ($ID_doc ==0)
{
    return DB::table('device_docs')
//    ->join('devices', 'device.id', '=', 'device_docs.device_id')
    ->join('docs', 'device_docs_doc_id', '=', 'docs.id')
    //->join('phpbb_users', 'device_docs.topic_poster', '=', 'phpbb_users.user_id')
    ->where('device_docs_device_id', '=', $devitem->devitems_device_id )
    ->orderBy('docs_title', 'desc')
    //->take(10)
    ->get(array(
        'device_docs_doc_id',
        'device_docs_device_id',
        'docs_title',
        'docs_subtitle',
        'docs_date',
        'docs_status'
    ));
}
else
{
    return DB::table('device_docs')
    ->join('docs', 'device_docs_doc_id', '=', 'docs.id')
    ->where('device_docs_device_id', '=', $devitem->devitems_device_id )
    ->where('docs.id', '=', $ID_doc )
    ->orderBy('docs_title', 'desc')
    //->take(10)
    ->get(array(
        'device_docs_doc_id',
        'device_docs_device_id',
        'docs_title',
        'docs_subtitle',
        'docs_description',
        'docs_date',
        'docs_status'
    ));
}


    }

}
