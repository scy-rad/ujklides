<?php

namespace App\Http\Controllers;

use App\AjaxData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\User;

class AjaxDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
     
    public function edit($id)
    {
        echo '<h1>EDIT</h1>';
        $ajaxData = DB::table('inventories')
        ->select('*')
        ->where('id', '=', $id)
        ->first();
        //return $ajaxData;
 ////       return view('ajaxData.edit',compact('ajaxData','id'));

        /*$userData = UserData::find($id);
        return view('userData.edit',compact('userData','id'));*/
    }

public function update($id)
    {
        /*
        if (request('invit_descript')=='')
            $desc='';
        else
            $desc=request('invit_descript');
          
        $status = DB::table('inventory_items')
        ->where('id', $id)
        ->update(['inventory_item_status' => request('invit_status'),
        'inventory_item_description' => $desc,
        'inventory_item_date' => date('Y-m-d H:i:s'),
        'inventory_item_user_id' => Auth::user()->id
        ]);

        return json_encode(array('statusCode'=>$id, 'SQLcode'=> $status));
        */
      
    }   
   
   
   
   
   
   
   
   
   
   
   
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AjaxData  $ajaxData
     * @return \Illuminate\Http\Response
     */
    public function show(AjaxData $ajaxData)
    {
        //
    }

}
