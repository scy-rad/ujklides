<?php

namespace App\Http\Controllers;

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

}
