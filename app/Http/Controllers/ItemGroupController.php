<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;
use App\ItemType;
use App\ItemGroup;
use Illuminate\Support\Facades\Auth;

class ItemGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($choice)
    {
   
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */

    public function showitem(ItemGroup $ItemGroup)
    {
        $pliki=\App\PlikForGroupitem::where('item_group_id',$ItemGroup->id)->get();
        return view('itemgroups.showitems', compact('ItemGroup'),['Pliki' => $pliki]);
    }

    
    public function show_something(ItemGroup $ItemGroup, String $do_what, Int $id_what)
    {
        $return_tab["do_what"]  = $do_what;
        $return_tab["id_what"]  = $id_what;
        $return_tab["pliki"]    = \App\PlikForGroupitem::where('item_group_id',$ItemGroup->id)->get();

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
                $return_tab["item_id"]=0;
            }
        
            $return_tab["item_group_id"]=$ItemGroup->id;    
            $return_tab["group_name"]=$ItemGroup->item_group_name;
            $return_tab["all_items"]=\App\Item::where('item_group_id',$ItemGroup->id)->get();
            $return_tab["plik"] = $plik;        // For edit plik modal window
        }
     
        return view('itemgroups.showitems', compact('ItemGroup'), $return_tab);
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

    }

}
