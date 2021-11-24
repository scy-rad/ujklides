<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Simmed;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Auth::user()->id=1;
        // dump('przywróć ID użytkownika do rzeczywistego w HomeController');

        if (date('N',strtotime(date('Y-m-d')))>4)
            $add_date=15-date('N',strtotime('2021-11-21'));
        else
            $add_date=1;

        $main_simulations=$simmeds =  Simmed::where('simmed_technician_id',Auth::user()->id)
        ->where('simmed_status','<>',4)
        ->where('simmed_date','>=',"date('Y-m-d')")
        ->where('simmed_date','<',date( 'Y-m-d', strtotime("+ 7 days") ) )
        ->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();

        dump(date( 'Y-m-d', strtotime("+7 days") ));
        
        return view('home',compact('main_simulations'),['sch_date' => '$sch_date']);
    }
}
