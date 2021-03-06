<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Room;
use App\RoomStorage;
use App\Item;


//UPDATE rooms SET room_photo=CONCAT("/storage/images/rooms/",room_photo) where id>0;

class RoomController extends Controller
{
    public function index()
    {
        $rooms =  Room::where('room_status',1)->orderBy('room_number')->get();
	    return view('rooms.index', compact('rooms'));
    }

    public function index_all()
    {
        if ( ! ( 
            (Auth::user()->hasRoleCode('coordinators')) || 
            (Auth::user()->hasRoleCode('itemoperators')) || 
            (Auth::user()->hasRoleCode('technicians'))
        ) )
        return back()->withErrors(['head'=>'błąd wywołania funkcji index_all kontrolera Room','title'=>'Brak uprawnień...','description'=>'Nie masz wystarczających uprawnień, aby wykonać tą operację...']);

        $rooms =  Room::orderBy('room_number')->get();
	    return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {

        return view('rooms.show', compact('room'));
    }
    
    public function showinventory(Room $room)
    {
        return view('rooms.showinventory', compact('room'));
    }
    public function showstorages(Room $room)
    {
        return view('rooms.showstorages', compact('room'));
    }

    public function store_inventory(Request $request)
    {
        $data = $request->all();

        $status = DB::table('inventory_items')
        ->where('id', $request->inv)
        ->update(['inventory_item_status' => $request->b_type,
                    'inventory_item_description' => $request->descript,
                    'inventory_item_date' => date('Y-m-d H:i:s'),
                    'user_id' => Auth::user()->id
                ]);

        $costam=$request->inv.': '.$request->b_type.' + '.$request->descript.' Status: ';

        return response()->json(['success'=>'wpis inwentaryzacyjny dokonany ','new_descr'=>$request->descript]);
    }

    public function check_inventory($id)
    {
        $status = DB::table('inventories')
        ->where('id', 1)
        //->update(['inventory_name' => request('inventory_name')]);
        ->update(['inventory_name' => 'tescik']);

        return json_encode(array('statusCode'=>$id, 'SQLcode'=> $status));

        /*$userData = UserData::find($id);
        $userData->type = request('type');
        $userData->name = request('name');
        $userData->email = request('email');
        $userData->phone = request('phone');
        $userData->city = request('city');
        $userData->save();
        return json_encode(array('statusCode'=>200));*/
      
    } 


}
