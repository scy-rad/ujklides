<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Simmed;
use Illuminate\Support\Facades\Hash;

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
        //date('N',strtotime('2021-11-21'));

        if (date('N',strtotime(date('Y-m-d')))>4)
            $add_date=8-date('N',strtotime(date('Y-m-d')));
        else
            $add_date=1;
            $add_date=2;
        $main_simulations=$simmeds =  Simmed::where('simmed_technician_id',Auth::user()->id)
        ->where('simmed_status','<>',4)
        ->whereBetween('simmed_date', [date('Y-m-d'), date( 'Y-m-d', strtotime("+ 7 days") )])
        ->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();

        $next_simulations=$simmeds =  Simmed::where('simmed_status','<>',4)
        ->whereBetween('simmed_date', [date('Y-m-d'), date( 'Y-m-d', strtotime("+ $add_date days") )])
        ->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();

        return view('home',compact('main_simulations'),compact('next_simulations'),['sch_date' => '$sch_date']);
    }


    public function showChangePasswordForm(){
        return view('auth.changepassword');
    }



    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Twoje hasło nie spełnia minimalnych wymagań. Spróbuj ponownie...");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","Nowe hasło nie może być takie same jak poprzednio. Postaraj się lepiej...");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success","No i udało się zmienić hasło !");

    }

}
