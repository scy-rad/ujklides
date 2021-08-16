<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Docs;
use App\Device_docs;
use Illuminate\Support\Facades\Redirect;

class DocsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('docs.create', ['For_id' => $request->id, 'For_table' => $request->table]);
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
        'docs_title' => 'required',
        'docs_subtitle' => 'required',
        'docs_description'=>'required',
        'docs_status' => 'required|regex:/^\d+$/'
    ]);

    $new_docs = Docs::create($request->all());



    switch ($request->ForTable){
        case 'device':
            echo 'device';

            $device_Doc = new Device_docs();
            $device_Doc->device_docs_device_id = $request->ForID;
            $device_Doc->device_docs_doc_id = $new_docs->id;

            $device_Doc->save();

            
            
            //return view('devices.show',  'device/1');
            //return view('devices.show',  ['id' => $request->ForID]);
            return Redirect::to('device/'.$request->ForID);

            break;
        default:
    }

        return back()->with('success', 'Dane zostały zapisane.');
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
        return view('docs.edit', compact('doc'));
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
        echo '<h1>Update</h1>';
                // Form validation
                //dd($request);

                $this->validate($request, [
                    'docs_title' => 'required',
                    'docs_subtitle' => 'required',
                    'docs_description'=>'required',
                    'docs_status' => 'required|regex:/^\d+$/'
                ]);

                $doc->update($request->all());
                echo '<h1>Update</h1>';

                //return redirect()->route('devices.show',[$device, "doc" => 0])->with('success', 'Dane zostały zapisane.');
                return redirect()->back();

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
