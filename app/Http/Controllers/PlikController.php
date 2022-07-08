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
        return view('pliks.index', ['type_code' => $type_code]);
    }

    public function show($plik_id)
    {
        $plik=Plik::where('id',$plik_id)->first();
        if (is_null($plik))
            return back()->withErrors(['head'=>'błąd wywołania funkcji show kontrolera Plik','description'=>'Plik nie znaleziony...']);
        else
            return view('pliks.show', compact('plik'));
    }

    public function update_plik_for(Request $request, Plik $id)
    {
        if ( ! Auth::user()->hasRoleCode('itemoperators') )
            return back()->withErrors(['head'=>'błąd wywołania funkcji update_plik_for kontrolera Plik','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

        switch ($request->update_action)
        {
            case "itemgroup":
                if ($request->id>0)
                    {
                    $plik_one=\App\PlikForGroupitem::find($request->id);
                    }
                else
                    $plik_one=new \App\PlikForGroupitem;
// dd($request);
                $slash_pos=strripos($request->plik_dir_name,'/')+1;
                if ($request->item_id==0)
                    {
                    $plik_one->item_id          = null;
                    $plik_one->item_group_id    = $request->item_group_id;
                    }
                else
                    {
                    $plik_one->item_id          = $request->item_id;
                    $plik_one->item_group_id    = null;
                    }

                $plik_one->plik_type_id     = $request->plik_type_id;
                $plik_one->plik_directory   = substr($request->plik_dir_name,0,$slash_pos);
                if (!(strpos($plik_one->plik_directory,$request->server('HTTP_ORIGIN'))===false))
                    {
                        $plik_one->plik_directory=substr($plik_one->plik_directory,strlen($request->server('HTTP_ORIGIN')),strlen($plik_one->plik_directory));
                    }
                $plik_one->plik_name        = substr($request->plik_dir_name,$slash_pos,strlen($request->plik_dir_name)-$slash_pos);
                $plik_one->plik_title       = $request->plik_title;
                $plik_one->plik_description = $request->plik_description;
                $plik_one->save();

                
                break;
            case "rooms":
                break;
            default:
                return back()->withErrors(['Błąd akcji w funkcji update_plik_for kontrolera Plik', 'Powiadom admnistratora systemu']);
        }
        return back()->with('success','Zapis powiódł się.');
        //return back()->withErrors(['name.required', 'Usuwanie roli nie powiodło się']);
    }


    public function delete_plik_for(Request $request, Int $id)
    {
        if ( ! Auth::user()->hasRoleCode('itemoperators') )
            return back()->withErrors(['head'=>'błąd wywołania funkcji delete_plik_for kontrolera Plik','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

            switch ($request->update_action)
            {
                case "itemgroup":
                    if (isset($request->agree))
                        if ($request->item_id>0)
                        {
                            $plik_one=\App\PlikForGroupitem::find($id);
                            $plik_one->delete();
                            return app('App\Http\Controllers\ItemController')->show_something(\App\Item::where('id',$request->item_id)->first(),'show',0);
                        }
                        elseif ($request->item_group_id>0)
                        {
                            $plik_one=\App\PlikForGroupitem::find($id);
                            $plik_one->delete();
                            return app('App\Http\Controllers\ItemGroupController')->show_something(\App\ItemGroup::where('id',$request->item_group_id)->first(),'show',0);
                        }
                        else
                        {
                            return back()->withErrors(['nie usunięto pliku', 'System nie znalazł wskazówki co do powrotu. Zgłoś to administratorowi...']);
                        }
                    else
                        return back()->withErrors(['nie usunięto pliku', 'Aby to zrobić musisz zaznaczyć dodatkowe pole...']);
                
                    break;
                case "rooms":
                    break;
                default:
                    return back()->withErrors(['Błąd akcji w funkcji delete_plik_for kontrolera Plik', 'Powiadom admnistratora systemu']);
            }
        return back()->with('success','Usunięcie pliku powiodło się.');
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
