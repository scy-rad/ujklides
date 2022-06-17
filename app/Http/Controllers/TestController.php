<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\ItemType;
use App\Room;
use App\RoomStorage;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index()
    {
        // $categoris=ItemType::where('item_type_parent_id','=',0)->get();        
        // return view('tests.cat',["categoris" => $categoris]);

        $rooms=Room::all();
        $roomstorages=RoomStorage::where('room_id',$rooms[0]->id)->get();
        return view('tests.cat',["rooms" => $rooms, "roomstorages" => $roomstorages]);
    }

    public function ajx_room_storages(Request $request)
    {        
        $roomstorages = RoomStorage::where('room_id',$request->room_id)
                              ->orderBy('room_storage_sort')
                              ->get();
        return response()->json([
            'roomstorages' => $roomstorages
        ]);
    }

    
    public function ajx_shelf_count(Request $request)
    {
        $shelf_count = RoomStorage::where('id',$request->room_storage_id)
                              ->first()->room_storage_shelf_count;
        return response()->json([
            'shelf_count' => $shelf_count
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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

 //   echo '<h1>Store</h1>';
     //   dd($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Docs $doc)
    {
        //return view('docs.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Docs $doc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
