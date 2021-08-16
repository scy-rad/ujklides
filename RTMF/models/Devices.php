<?php
//php71-cli artisan make:controller DevicesController --resource
namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Devices extends Model
{
    public $fillable = ['devices_lib_group_id', 'devices_photo', 'devices_name', 'devices_description', 'devices_status'];

    function get_device_types(Int $id)
    {
    if ($id == 0)
        {
        $retry = DB::table('libraries')
        ->select('id', 'lib_name AS value')
        ->where('lib_type', '=', 1 )
        ->orderBy('lib_sort', 'asc')
        ->get();
        }
    else
        {
        $retry = DB::table('libraries')
        ->select('*')
        ->where('id', '=', $id )
        ->first();
        }

    return $retry;
    }

    function get_items(Devices $device)
    {
            
    $retry = DB::table('devitems')
    ->select('id', 'devitems_inventory_number AS value')
    ->where('devitems_device_id', '=', $device->id )
    ->orderBy('devitems_inventory_number', 'asc')
    ->get();
    //->get(array(
    //    'id',
    //    'value'
    //));

    return $retry;
    }

    function get_docs(Devices $device, Int $ID_doc)
    {
/*
    $retry = DB::table('')
    ->select('id', 'lastname AS value')
//    ->where('lib_type', '=', 1 )
    ->orderBy('lastname', 'asc')
    ->get();

    return $retry;
*/

if ($ID_doc ==0)
{
    return DB::table('device_docs')
    ->join('docs', 'device_docs_doc_id', '=', 'docs.id')
    //->join('phpbb_users', 'device_docs.topic_poster', '=', 'phpbb_users.user_id')
    ->where('device_docs_device_id', '=', $device->id )
//    ->orderBy('title', 'desc')
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
    //->join('phpbb_users', 'device_docs.topic_poster', '=', 'phpbb_users.user_id')
    ->where('device_docs_device_id', '=', $device->id )
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
