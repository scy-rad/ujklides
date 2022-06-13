<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;
use App\ItemType;
use App\ItemGroup;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($choice)
    {

    if (($choice=="wszystko") || ($choice=="0"))
            {
                $Items=Item::all();
                $Items_table=null;
            }
        else
            {
                if (ItemType::where('item_type_code','=',$choice)->count()>0)
                    {
                    $type_analyse=ItemType::where('item_type_code','=',$choice)->pluck('id');
                    $return[]=$type_analyse->toArray();
                    }
                else
                    {
                    $type_analyse=ItemType::where('id','=',$choice)->pluck('id');
                    $return[]=$type_analyse->toArray();
                    }

                    $groups_analyse=ItemGroup::whereIn('item_type_id',$type_analyse)->pluck('id');
                    $Items_table=Item::whereIn('item_group_id',$groups_analyse)->pluck('id')->toArray();
                    //$Items_table=array_merge($Items_table,Item::whereIn('item_group_id',$groups_analyse)->pluck('id'));
                

                
                do {
                $type_analyse=ItemType::whereIn('item_type_parent_id',$type_analyse)->pluck('id');
                $return=array_merge($return,$type_analyse->toArray());
                    $groups_analyse=ItemGroup::whereIn('item_type_id',$type_analyse)->pluck('id');
                    //dump($Items_table);
                    $Items_table=array_merge($Items_table,Item::whereIn('item_group_id',$groups_analyse)->pluck('id')->toArray());
                }
                while ($type_analyse->count()>0);

                $return=ItemType::whereIn('item_type_parent_id',$return)->orderBy('item_type_parent_id')->pluck('id');
                                
                //dump($Items_table);

              
                $Groups=ItemGroup::whereIn('item_type_id',$return)->pluck('id');
                //dump($Groups->count());
                $Items=Item::whereIn('item_group_id',$Groups)->get();
                //dump($Items->count());
                //$Items=Item::whereIn('id',$Items_table)->get();
            }

        return view('items.index', compact('Items'), ['type_name' => $choice,'items_table' => $Items_table]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }

    public function doc(Item $item, Int $id_what)
    {
        return view('items.show', compact('item'), ["do_what" => "docs", "id_what" => $id_what]);
    }
    
    public function gal(Item $item, Int $id_what)
    {
        return view('items.show', compact('item'), ["do_what" => "gals", "id_what" => $id_what]);
    }

    public function fil(Item $item, Int $id_what)
    {
        return view('items.show', compact('item'), ["do_what" => "fils", "id_what" => $id_what]);
    }

    public function fault(Item $item, String $fault_action, String $id_what)
    {
        return view('items.show', compact('item'), ["do_what" => "fault", "fault_action" => $fault_action, "id_what" => $id_what]);
    }

     

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {

        switch ($request->input('action')) {
            case 'loan':
                //echo '<h1>wypożyczenie lub zwrot sprzętu</h1>';
                //echo "<h2>$request->new_room_storage</h2>";
                
                //print_r($request->all());


               // $item = Page::find($id);

                // Make sure you've got the Page model
                if($item) {
                    $item->room_storage_current_id = $request->new_room_storage;
                    $item->save();
                }
        
                break;
            default:
                echo '<h1> tu miał być pewnie jakiś update item - ale cóś nie wyszło...';
        }
        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }


    public function save_inv(Request $request, Item $item)
    {
        $item->item_serial_number = $request->item_serial_number;
        $item->item_inventory_number = $request->item_inventory_number;
        $item->item_purchase_date = $request->item_purchase_date;
        $item->item_warranty_date = $request->item_warranty_date;
        $item->item_description = $request->item_description;
        $ret = $item->save();

        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }

    public function save_loc(Request $request, Item $item)
    {
        $item->room_storage_id = $request->room_storage_id;
        $item->item_storage_shelf = $request->item_storage_shelf;
        $ret = $item->save();

        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }

    public function save_sta(Request $request, Item $item)
    {
        dd('save_sta',$request);
        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }

    public function save_pho(Request $request, Item $item)
    {
        $item->item_photo=substr($request->picture_name,strlen($request->server('HTTP_ORIGIN').'/storage/image/')+1,100);
        $ret = $item->save();
        return view('items.show', compact('item'), ["do_what" => "nothing", "doc" => 0]);
    }


}
