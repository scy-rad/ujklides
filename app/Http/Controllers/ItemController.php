<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;
use App\ItemType;
use App\ItemGroup;
use Illuminate\Support\Facades\Auth;

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
        $return_tab["do_what"]  = "show";
        $return_tab["id_what"]  = 0;
        $return_tab["rooms"]    = \App\Room::all();
        $return_tab["roomstorages"] = \App\RoomStorage::where('room_id',$item->current_storage()->room()->id)->get();
     
        if (Auth::user()->hasRoleCode('itemoperators'))
        {
                $plik = new \App\PlikForGroupitem;
                $plik->id=0;

                $return_tab["item_id"]=$item->id;
                $return_tab["item_group_id"]=$item->item_group_id;
                $return_tab["group_name"]=$item->group()->item_group_name;
                $return_tab["all_items"]=\App\Item::where('item_group_id',$item->item_group_id)->get();
                $return_tab["plik"] = $plik;
        }

        return view('items.show', compact('item'), $return_tab);

        // return view('items.show', compact('item'), ["do_what" => "basic_view", "doc" => 0, "rooms" => $rooms, "roomstorages" => $roomstorages]);
    }

    public function show_something(Item $item, String $do_what, Int $id_what)
    {
        $return_tab["do_what"]  = $do_what;
        $return_tab["id_what"]  = $id_what;
        $return_tab["rooms"]    = \App\Room::all();
        $return_tab["roomstorages"] = \App\RoomStorage::where('room_id',$item->current_storage()->room()->id)->get();
     
        if (Auth::user()->hasRoleCode('itemoperators'))
        {
            if ($do_what=="fils")
            {
                $plik=\App\PlikForGroupitem::where('id',$id_what)->get()->first();
                $return_tab["item_id"]=$plik->item_id;
            }
            else 
            {
                $plik = new \App\PlikForGroupitem;
                $plik->id=0; 
                $return_tab["item_id"]=$item->id;
            }

            $return_tab["item_group_id"]=$item->item_group_id;   
            $return_tab["group_name"]=$item->group()->item_group_name;
            $return_tab["all_items"]=\App\Item::where('item_group_id',$item->item_group_id)->get();
            $return_tab["plik"] = $plik;
        }

        return view('items.show', compact('item'), $return_tab);
    }

    

    public function fault(Item $item, String $fault_action, String $id_what)
    {
        $rooms=\App\Room::all();
        dd($rooms);
        $roomstorages=\App\RoomStorage::where('room_id',$item->current_storage()->room()->id)->get();
        dump('try replace fault function by show_something in ItemController');
        return view('items.show', compact('item'), ["do_what" => "fault", "id_what" => $id_what, "rooms" => $rooms, "roomstorages" => $roomstorages, "fault_action" => $fault_action]);
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
        if ( ! ( ( ( (Auth::user()->hasRoleCode('serviceworkers')) || (Auth::user()->hasRoleCode('technicians')) ) && $request->update=='relocate' )
              ||  (Auth::user()->hasRoleCode('itemoperators')) ))
              return back()->withErrors(['head'=>'błąd wywołania funkcji update kontrolera Item','title'=>'Brak uprawnień...','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

        $rooms=\App\Room::all();
        switch ($request->update)
        {
            case 'realocate':
                if($item) {
                    $item->room_storage_current_id = $request->new_room_storage;
                    $item->save();
                }
                break;
            case "localization":
                $item->room_storage_id = $request->roomstorage;
                $item->room_storage_current_id = $request->roomstorage;
                $item->item_storage_shelf = $request->item_storage_shelf;
                $ret = $item->save();
                break;
            case "invent_data":
                $item->item_serial_number = $request->item_serial_number;
                $item->item_inventory_number = $request->item_inventory_number;
                $item->item_purchase_date = $request->item_purchase_date;
                $item->item_warranty_date = $request->item_warranty_date;
                $item->item_description = $request->item_description;
                $ret = $item->save();
                break;
            case "picture":
                $item->item_photo=substr($request->picture_name,strlen($request->server('HTTP_ORIGIN'))+1,256);
                $ret = $item->save();
                break;
            default:
                return back()->withErrors(['head'=>'błąd wywołania funkcji update kontrolera Item','title'=>'coś posszło nie tak...','description'=>'...']);

        }

        return back()->with('success',' Zapis zakończył się sukcesem.');

    }


    public function ajx_room_storages(Request $request)
    {        
        $roomstorages = \App\RoomStorage::where('room_id',$request->room_id)
                              ->orderBy('room_storage_sort')
                              ->get();
        return response()->json([
            'roomstorages' => $roomstorages
        ]);
    }

    
    public function ajx_shelf_count(Request $request)
    {
        $shelf_count = \App\RoomStorage::where('id',$request->room_storage_id)
                              ->first()->room_storage_shelf_count;
        return response()->json([
            'shelf_count' => $shelf_count
        ]);
    }

}
