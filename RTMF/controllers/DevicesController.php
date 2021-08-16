<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Devices;


class DevicesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //echo '<h1>posiadany sprzęt</h1>';
//        return view('devices.index');//katalog dot nazwa_szablonu
        
        
        $devices =  Devices::orderBy('id', 'DESC')->paginate(10);
	    $devices =  Devices::all();
	    return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $device = new Devices;
	    return view('devices.create', compact('device'));
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
     $this->validate($request, [
        'devices_lib_group_id' => 'required',
        //'serial_numbeer' => 'required|regex:/^([0-9A-Za-z\s\-\+\(\)]*)$/|min:1',
        'devices_name' => 'required',
        'devices_description'=>'required',
        'devices_status' => 'required|regex:/^\d+$/'
    ]);

    Devices::create($request->all());

    return redirect()->route('devices.index')->with('success', 'Dane zostały dodane.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show0(Devices $device)
    {
  
        return view('devices.show', compact('device'), ["doc" => 0]);
    }

    public function show(Devices $device, Int $doc)
    {
    //echo print_r($device);
  
        return view('devices.show', compact('device'), ["doc" => $doc]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
    public function edit(Devices $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Devices $device)
    {
        echo '<h1>Update</h1>';
        echo "<h2>$request->subaction</h2>";
        //dd($request);
        switch ($request->subaction)
        {
            case 'main':
                // Form validation
                $this->validate($request, [
                    'devices_lib_group_id' => 'required',
                    'devices_name' => 'required',
                    'devices_description'=>'required',
                    'devices_status' => 'required|regex:/^\d+$/'
                ]);

                //  Store data in database
                //Device::update($request->all());
                //Device::create($request->all());
                //return back()->with('success', 'Dane zostały zapisane.');

                $device->update($request->all());
                echo '<h1>Update</h1>';
                return redirect()->route('devices.show',[$device, "doc" => 0])->with('success', 'Dane zostały zapisane.');
                break;
            case 'mainphoto':
                $path = $request->file('photofile')->storeAs('img/'. $request->get('fileplace'), $request->file('photofile')->getClientOriginalName());
//                dd($device);
                $device->update(array('devices_photo' => $request->file('photofile')->getClientOriginalName() ));
                echo $request->file('photofile')."<hr>1. ";
                echo $request->file('photofile')->getClientOriginalName();
                echo '<hr>2. ';
                //dd($path);
                /*
                if ($request->file('photofile')->IsValid){
                    echo 'valid<br>'; 
                     }
                else
                    echo 'invalid<br>';
                */

               
                //dd($request->file('photofile'));
                echo '<hr>3. ';
                //dd($request->all());
                echo '<h1>Update Photo</h1>';
                return redirect()->route('devices.show',[$device, "doc" => 0])->with('success', 'Zdjęcie zostało uaktualnione.');
                break; 
//    return back()
//    ->with('success','Superancko przesłano plik....');











                break;
            default:
                echo '<h2>Coś poszło nie tak. Devices.Controller</h2>';
                dd($request);
    
        }            
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
