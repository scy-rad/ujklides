<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Simmed;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

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


        function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) 
        {
            $dates = [];
            $current = strtotime( $first );
            $last = strtotime( $last );
        
            while( $current <= $last ) {
        
                $dates[] = ['date' => date( $format, $current ), 'wd' => date( 'N', $current ) ];
                $current = strtotime( $step, $current );
            }
        
            return $dates;
        }

        $ret=[];
        foreach (dateRange( date('Y-m-d'), date( 'Y-m-d', strtotime("+ 7 days") ) ) as $row_data)
            {
            $ret[$row_data['date']]['date'] = $row_data['date'];
            $ret[$row_data['date']]['wd'] = $row_data['wd'];
            $ret[$row_data['date']]['wdname'] = DB::table('pl_days')
                    ->select('*')
                    ->where('dateN',$row_data['wd'])
                    ->get()
                    ->first()
                    ->pl_day_short;

            $ret[$row_data['date']]['simmeds'] = Simmed::where('simmed_technician_id',Auth::user()->id)
                            ->where('simmed_status','<>',4)
                            ->where('simmed_date', $row_data['date'])
                            ->orderBy('simmed_time_begin')->get();
            $ret[$row_data['date']]['work_times'] =\App\WorkTime::calculate_work_time(Auth::user()->id, $row_data['date']);;
            }
        // dump($ret);




        if (date('N',strtotime(date('Y-m-d')))>4)
            $add_date=8-date('N',strtotime(date('Y-m-d')));
        else
            $add_date=1;
            $add_date=2;
        $main_simulations=$simmeds =  Simmed::where('simmed_technician_id',Auth::user()->id)
        ->where('simmed_status','<>',4)
        ->whereBetween('simmed_date', [date('Y-m-d'), date( 'Y-m-d', strtotime("+ 7 days") )])
        ->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();
        
        $work_times=\App\WorkTime::calculate_work_time(Auth::user()->id, date('Y-m-d'));


        return view('home',compact('main_simulations'),['home_data' => $ret, 'sch_date' => '$sch_date', 'work_times' => $work_times]);
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
