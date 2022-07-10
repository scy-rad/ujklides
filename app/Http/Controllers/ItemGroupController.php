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
     * @param  \App\ItemGroup  $ItemGroup
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
            $return_tab["item_types"]=\App\ItemType::all();
            $return_tab["plik"] = $plik;        // For edit plik modal window
        }
        return view('itemgroups.showitems', compact('ItemGroup'), $return_tab);
    }


    public function update(Request $request, ItemGroup $ItemGroup)
    {
        if ( ! (Auth::user()->hasRoleCode('itemoperators')) )
              return back()->withErrors(['head'=>'błąd wywołania funkcji update kontrolera ItemGroup','title'=>'Brak uprawnień...','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

        switch ($request->update)
        {
            case "basic_data":
                $ItemGroup->item_type_id = $request->item_type_id ;
                $ItemGroup->item_group_name = $request->item_group_name ;
                $ItemGroup->item_group_producent = $request->item_group_producent ;
                $ItemGroup->item_group_model = $request->item_group_model ;
                $ItemGroup->item_group_description = $request->item_group_description ;
                $ItemGroup->item_group_status = $request->item_group_status ;
                $ret = $ItemGroup->save();
                break;
            case "picture":
                $ItemGroup->item_group_photo=substr($request->picture_name,strlen($request->server('HTTP_ORIGIN'))+1,strlen($request->picture_name));
                $ret = $ItemGroup->save();
                break;
            default:
                return back()->withErrors(['head'=>'błąd wywołania funkcji update kontrolera ItemGroup','title'=>'coś posszło nie tak...','description'=>'...']);

        }

        return back()->with('success',' Zapis zakończył się sukcesem.');


    }

}
