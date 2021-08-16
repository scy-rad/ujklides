<?php

namespace App\Http\Controllers;

use App\Fittings;
use Illuminate\Http\Request;

class FittingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fittings =  Fittings::all();
	    return view('fittings.index', compact('fittings'));
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
     * @param  \App\Fitting  $fitting
     * @return \Illuminate\Http\Response
     */
    public function show(Fittings $fitting)
    {
        return view('fittings.show', compact('fitting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fitting  $fitting
     * @return \Illuminate\Http\Response
     */
    public function edit(Fittings $fitting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fitting  $fitting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fittings $fitting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fitting  $fitting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fittings $fitting)
    {
        //
    }
}
