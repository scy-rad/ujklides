<?php

namespace App\Http\Controllers;

use App\ItemType;

class ItemTypeController extends Controller
{

    public function index($choice)
    {
    $ItemTypes = ItemType::where('item_type_parent_id',$choice)->get();
    if ($ItemTypes->count()==0)
        {
        $ItemType=ItemType::where('id',$choice)->get()->first();
        return view('itemtypes.showgroups', compact('ItemType'));
        }
    return view('itemtypes.index', compact('ItemTypes'), ['type_id' => $choice]);
    }


    public function showgroup(ItemType $ItemType)
    {
        return view('itemtypes.showgroups', compact('ItemType'));
    }
    public function showitem(ItemType $ItemType)
    {
        return view('itemtypes.showitems', compact('ItemType'));
    }


}
