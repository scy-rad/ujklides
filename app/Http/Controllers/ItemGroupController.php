<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;
use App\ItemType;
use App\ItemGroup;

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
