<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Plik;

class PlikController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($type_code)
    {
        $plik=Plik::where('id',1)->first();
        return view('pliks.index', compact('plik'),['type_code' => $type_code]);
    }

    public function show($plik_id)
    {
        $plik=Plik::where('id',$plik_id)->first();
        return view('pliks.show', compact('plik'));
    }

    public function update_for(Request $request, Plik $plik_id)
    {
        if ( ! Auth::user()->hasRoleCode('itemoperators') )
            return back()->withErrors(['head'=>'błąd wywołania funkcji update_for kontrolera Plik','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

        switch ($request->update_action)
        {
            case "groups":
                if ($request->plik_for_id>0)
                    {
                    $plik_group=\App\PlikForGroupitem::where('id',$request->plik_for_id)->first();
                    if ($plik_group->plik_id != $plik_id->id)
                        return back()->withErrors(['Błąd akcji update_for kontrolera Plik', 'Niezgodność ID pliku: '.$plik_group->plik_id.' != '.$plik_id->id]);
                    }
                else
                    $plik_group=new \App\PlikForGroupitem;
                
                if ($request->choose_id==0)
                    {
                    if ($request->plik_for_id == 0)
                        return back()->withErrors(['Błąd akcji update_for kontrolera Plik', 'Nie można usunąć wpisu, którego się nie zapisało']);
                    else
                        $plik_group->delete();
                        return back()->with('success','Usunięcie zapisu powiodło się.');
                    }
                else
                    {
                    $plik_group->plik_id = $plik_id->id;
                    $plik_group->item_id = NULL;
                    $plik_group->item_group_id = $request->choose_id;
                    $plik_group->save();
                    }
                break;
            case "rooms":
                break;
            case "items":
                break;
            default:
                return back()->withErrors(['Błąd akcji w funkcji update_for kontrolera Plik', 'Powiadom admnistratora systemu']);
        }
        return back()->with('success','Zapis powiódł się.');
        //return back()->withErrors(['name.required', 'Usuwanie roli nie powiodło się']);
    }


    public function ajx_rooms(Request $request)
    {        
        $choose_table = \App\Room::select('id as id',
        \DB::raw('concat(room_number,": ",room_name) as choose_value') )
        ->get();
        return response()->json([
            'choose_table' => $choose_table
        ]);
    }
    public function ajx_groups(Request $request)
    {        
        $choose_table = \App\ItemGroup::select('id as id',
        'item_group_name as choose_value', 'item_group_name' )
        ->orderBy('item_group_name')
        ->get()
        ;
        return response()->json([
            'choose_table' => $choose_table
        ]);
    }

}
