<?php

namespace App\Http\Controllers;

use App\Equipments;
use App\EquipmentTypes;
use Illuminate\Http\Request;

class EquipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$equipments =  Equipments::all();
        //$equipments =  $this->get_abc();

        //$costam=Equipments::where('equipments_model', 'One Touch')->first()->id;
        //print_r($costam);

        $equipments =  Equipments::where('equipments.id', '>', 0)
        ->join('equipment_types', 'equipment_types.id', '=', 'equipments_equipment_types_id')
        ->get();

	    return view('equipments.index', compact('equipments'));
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
     * @param  \App\Equipments  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipments $equipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipments  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipments $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipments  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipments $equipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipments  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipments $equipment)
    {
        //
    }
}
