<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\WorkTime;
use App\User;
use App\TechnicianCharacter;

class WorkTimeController extends Controller
{
    

    public function month_data(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Technik'))
        return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        $ret_row=WorkTime::selectRaw(" MIN(date) AS StartDate, MAX(date) AS EndDate")->get()->first();
        
        $current=date('Y-m-01',strtotime($ret_row->StartDate));
        $stop=date('Y-m-01',strtotime($ret_row->EndDate));

        while ($current<=$stop)
        {
        $months[date('Y-m',strtotime($current))]=date('Y-m',strtotime($current));
        $current=date('Y-m-01',strtotime('+  1 month',strtotime($current)));
        }
        
        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }

        if (isset($request->month))
            $filtr['month'] = $request->month;
        else
            $filtr['month'] = date('Y-m');
        if ($request->technician==0)
            {
            if (Auth::user()->hasRole('Technik'))
                $filtr['user'] = Auth::user()->id;
            else
                $filtr['user'] = User::nobody()->id;
            }
        else
            $filtr['user'] = $request->technician;

        $user = user::find($filtr['user']);

        $begin = strtotime($filtr['month'].'-01');
        //$end   = strtotime(date("Y-m-t", strtotime($filtr['month'].'-01')));
        $end   = strtotime($filtr['month'].'-01 + 1 month');
        
        $total['minutes']=0;
        $total_minutes=0;
        $y=1;
        $ret=[];
        for($i = $begin; $i < $end; $i = $i+86400 )
        {
            $cur_date=date('Y-m-d',$i);


            $ret_row=WorkTime::calculate_work_time($filtr['user'], $cur_date);
            $ret_row['sims'] = \App\Simmed::simmeds_join('without_free','without_deleted')
                ->where('simmed_date','=',$cur_date)
                ->where('simmed_technician_id','=',$filtr['user'])
                ->orderBy('simmed_date')
                ->orderBy('time')
                ->orderBy('room_number')
                ->get()
                ->toArray()
                ;
            $ret[$cur_date]=$ret_row;
            $ret[$cur_date]['day_name']= DB::table('pl_days')->find(date('w', strtotime($cur_date))+1)->pl_day;
            $ret[$cur_date]['day_name_short']= DB::table('pl_days')->find(date('w', strtotime($cur_date))+1)->pl_day_short;
            $ret[$cur_date]['hoursmin']=m2h($ret_row['minutes']);
            $total['minutes']+=$ret_row['minutes'];            
        }

        $total['times'] = m2h($total['minutes']);

        $total['work_characters_month'] = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->where('simmed_date','>=',date('Y-m-d',$begin))
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
    

        $total['work_characters'] = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->get();

        return view('worktime/month',['user'=>$user, 'months' => $months, 'filtr' => $filtr, 'tabelka' => $ret, 'total' => $total ]);

    }


    public function day_data($date, $user_id)
    {
        if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Technik'))
        return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        $simmeds =
            \App\Simmed::simmeds_join('without_free','without_deleted')
                ->where('simmed_date','=',$date)
                ->where('simmeds.simmed_technician_id',$user_id)
                ->orderBy('time')
                ->orderBy('room_number')
                ->get();

        $work_times = \App\WorkTime::work_time_join('without_breake')
        ->where('user_id',$user_id)
        ->where('date','=',$date)
        ->get();

        $work_time_types = \App\WorkTimeType::select('*')
            ->orderBy('short_name')
            ->get();

        $userdata=User::where('id', '=', $user_id)
                    ->get()->first();

        $dateT['date']=$date;
        $dateT['dayname']= DB::table('pl_days')->find(date('w', strtotime($date))+1)->pl_day;
        

    return view('worktime/dayinfo',['user'=>$userdata, 'simmeds' => $simmeds, 'work_times' => $work_times, 'work_time_types' => $work_time_types, 'dateT' => $dateT ]);
    }


    public function save_data(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Technik'))
        return view('error',['head'=>'błąd wywołania funkcji save_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        $date_back=\App\Param::select('*')->orderBy('id','desc')->get()->first()->worktime_days_edit_back;

        if ($date_back<0)
            $date_back='+'.$date_back*(-1);
        else
            $date_back='-'.$date_back;

        if (!Auth::user()->hasRole('Operator Kadr') 
            && !(Auth::user()->id == ($request->user_id*1)) 
            )
            {
                return back()->withErrors('tylko Operator Kadr może edytować innych użytkowników');
            }
        elseif (!Auth::user()->hasRole('Operator Kadr') 
            && ($request->date < date('Y-m-d',strtotime('now '.$date_back.' days')))
            )
            {
                return back()->withErrors('zbyt wczesna data do edycji. Dopuszczalna ilość dni to: '.$date_back.'. (nie dotyczy Operatora Kadr)');        
            }
        if ($request->id>0)
        {
            if ($request->modal_start < $request->modal_end)
            {
                $TimeWork=\App\WorkTime::find($request->id);
                $TimeWork->work_time_types_id   = $request->work_time_types_id;
                $TimeWork->time_begin           = $request->modal_start;
                $TimeWork->time_end             = $request->modal_end;
                if ($request->modal_description==null)
                    $TimeWork->description      ='';
                else
                    $TimeWork->description      = $request->modal_description;
                // $TimeWork->date                 = $request->date;
                // $TimeWork->user_id              = $request->user_id;
                $TimeWork->save();
                return back()->with('success',' Zapis zakończył się sukcesem.');
            }
            elseif (($request->modal_start == $request->modal_end))
            {
                $TimeWork=\App\WorkTime::find($request->id);
                $TimeWork->status=4;
                $TimeWork->save();
                return back()->with('success','Usunięto zapis...');
            }
            else
            {
                return back()->withErrors('zakończenie nie może być wcześniej niż początek...');
            }
        }
        elseif ($request->modal_start < $request->modal_end)
        {
            $TimeWork=new \App\WorkTime;
            $TimeWork->work_time_types_id   = $request->work_time_types_id;
            $TimeWork->time_begin           = $request->modal_start;
            $TimeWork->time_end             = $request->modal_end;
            if ($request->modal_description==null)
                    $TimeWork->description      ='';
                else
                    $TimeWork->description      = $request->modal_description;
            $TimeWork->date                 = $request->date;
            $TimeWork->user_id              = $request->user_id;
            $TimeWork->save();
            return back()->with('success','Dodano nową pozycję.');
        }
        else
        {
            return back()->withErrors('nie mogę tego zrobić...');
        }
        
        //return app('App\Http\Controllers\WorkTimeController')->day_data($request->date, $request->user_id);
        }



    public function all_days(Request $request)
    {
        
        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }

        if (isset($request->month))
            $filtr['month'] = $request->month;
        else
            $filtr['month'] = date('Y-m');


        $begin = strtotime($filtr['month'].'-01');
        $end   = strtotime($filtr['month'].'-01 + 1 month');
        

//        foreach \App\Users

        $total['work_characters_month'] = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->where('simmed_date','>=',date('Y-m-d',$begin))
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
    

        $total['work_characters'] = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();

        return view('worktime/month',['user'=>$user, 'months' => $months, 'filtr' => $filtr, 'total' => $total ]);

    }

    
    public function statistics(Request $request)
    {
        $extra_tab=null;
        if (!isset($request->start))
            {
                //$filtr['start'] = date('Y-m').'-01';
                $filtr['start'] = \App\Simmed::selectRaw('min(simmed_date) as minvalue')->get()->first()->minvalue;
                $filtr['stop'] = date('Y-m-t');
                $filtr['technician'] = 777;
                $filtr['character'] = 777;
                $filtr['room'] = 777;
                $filtr['instructor'] = 777;
                $filtr['subject'] = 777;
            }
        else
            {
            $filtr['start'] = $request->start;
            $filtr['stop'] = $request->stop;
            $filtr['technician'] = $request->technician;
            $filtr['character'] = $request->character;
            $filtr['room'] = $request->room;
            $filtr['instructor'] = $request->instructor;
            $filtr['subject'] = $request->subject;
            if ( ($filtr['technician'] != 777)  || 
                 ($filtr['character'] != 777)  || 
                 ($filtr['room'] != 777) ||
                 ($filtr['instructor'] != 777) ||
                 ($filtr['subject'] != 777)
                 
                 )
                {
                    $return=\App\Simmed::simmeds_join('with_free','without_deleted');
                    if ($filtr['technician']==0)
                        $return=$return->WhereNull('simmed_technician_id');
                    if ( ($filtr['technician']!=777) && ($filtr['technician']>0) )
                        $return=$return->where('simmed_technician_id',$filtr['technician']);
                    if ( ($filtr['character']!=777) && ($filtr['character']>0) )
                        $return=$return->where('simmed_technician_character_id',$filtr['character']);
                    if ( ($filtr['room']!=777) && ($filtr['room']>0) )
                        $return=$return->where('room_id',$filtr['room']);
                    if ( ($filtr['instructor']!=777) && ($filtr['instructor']>0) )
                        $return=$return->where('simmed_leader_id',$filtr['instructor']);
                    if ( ($filtr['subject']!=777) && ($filtr['subject']>0) )
                        $return=$return->where('student_subject_id',$filtr['subject']);
                
                    $extra_tab=$return
                        ->where('simmed_date','>=',$filtr['start'])
                        ->where('simmed_date','<=',$filtr['stop'])
                        ->orderBy('simmed_date')
                        ->orderBy('time')
                        ->orderBy('room_number')
                        ->orderBy('technician_name')
                        ->get();
                }          
            }


    
        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }

        $active_instructors=\App\Simmed::select('simmed_leader_id')
            ->where('simmed_date','>=',$filtr['start'])
            ->where('simmed_date','<=',$filtr['stop'])
            ->groupBy('simmed_leader_id')
            ->get();
        $instructors_list=User::role_users('instructors', 1, 1)
            ->whereIn('id',$active_instructors)
            ->get();

        $active_subjects=\App\Simmed::select('student_subject_id')
            ->where('simmed_date','>=',$filtr['start'])
            ->where('simmed_date','<=',$filtr['stop'])
            ->groupBy('student_subject_id')
            ->get();
        $subjects_list=\App\StudentSubject::select('*')
            ->whereIn('id',$active_subjects)
            ->get();


        $technicians_list=User::role_users('technicians', 1, 1)->get();
        $nulik=new User;
        $nulik->id = null;
        $nulik->name='brak wpisu';
        $nulik->firstname='Brak';
        $nulik->lasttname='Wpisu';

        $technicians_list[]=$nulik;
//        dump($technicians_list,$nulik);

        $technician_char=TechnicianCharacter::all();

        foreach ($technicians_list as $technician_one)
        {
            $tabelka=null;
            $tabelka['name']=$technician_one->name;
            $tabelka['firstname']=$technician_one->firstname;
            $tabelka['lastname']=$technician_one->lastname;
            foreach ($technician_char as $character_one)
            {
                $tabelka['current'][$character_one->character_short]['count']=0;
                $tabelka['current'][$character_one->character_short]['time']=0;
                $tabelka['current'][$character_one->character_short]['type']='';
            }

            $work_characters_month = 
            \App\WorkTime::get_worktime_characters()
                ->where('simmed_technician_id','=',$technician_one->id)
                //->orWhereNull('simmed_technician_id')
                ->where('simmed_date','>=',$filtr['start'])
                ->where('simmed_date','<=',$filtr['stop'])
                ->get();

            foreach ($work_characters_month as $row_one)
            {
                $tabelka['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
                $tabelka['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
                $tabelka['current'][$row_one->worktime_type]['time']=$row_one->worktime_minutes;
            }
            
            $ret_table[]=$tabelka;
        }



        foreach ($technician_char as $character_one)
        {
            $work_total['current'][$character_one->character_short]['count']=0;
            $work_total['current'][$character_one->character_short]['time']=0;
        }


        $work_total = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_date','>=',$filtr['start'])
            ->where('simmed_date','<=',$filtr['stop'])
            ->get();
        foreach ($work_total as $row_one)
        {
            $total['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
            $total['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
            $total['current'][$row_one->worktime_type]['time']=$row_one->worktime_minutes;
        }

        $room_list=\App\Room::where('room_XP_code','<>','')->orderBy('room_number')->get();


        return view('worktime/statistics',['tabelka'=>$ret_table, 'total' => $total, 'characters' => $technician_char, 'filtr' => $filtr, 'technicians_list' => $technicians_list, 'instructors_list' => $instructors_list, 'subjects_list' => $subjects_list, 'technician_char' => $technician_char, 'room_list' =>$room_list, 'extra_tab' => $extra_tab ]);

    }




    public function statpertech(Request $request)
    {
        $extra_tab=null;
        if (!isset($request->start))
            {
                //$filtr['start'] = date('Y-m').'-01';
                $filtr['start'] = \App\Simmed::selectRaw('min(simmed_date) as minvalue')->get()->first()->minvalue;
                $filtr['stop'] = date('Y-m-t');
                $filtr['technician'] = 777;
                $filtr['character'] = 1;
                $filtr['room'] = 777;
                $filtr['instructor'] = 777;
                $filtr['subject'] = 777;
            }
        else
            {
            $filtr['start'] = $request->start;
            $filtr['stop'] = $request->stop;
            $filtr['technician'] = $request->technician;
            $filtr['character'] = $request->character;
            $filtr['room'] = $request->room;
            $filtr['instructor'] = $request->instructor;
            $filtr['subject'] = $request->subject;
            }

        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }



        $technicians_list=User::role_users('technicians', 1, 1)->get();
        $nulik=new User;
        $nulik->id = null;
        $nulik->name='brak wpisu';
        $nulik->firstname='Brak';
        $nulik->lasttname='Wpisu';

        $technicians_count=$technicians_list->count()-1;

        $technicians_list[]=$nulik;
        $sick_total=0;

        $technician_char=TechnicianCharacter::all()->sortBy('character_short');

        foreach ($technicians_list as $technician_one)
        {
            $tabelka=null;
            $tabelka['name']=$technician_one->name;
            $tabelka['firstname']=$technician_one->firstname;
            $tabelka['lastname']=$technician_one->lastname;
            
            
            foreach ($technician_char as $character_one)
            {
                $tabelka['current'][$character_one->character_short]['count']=0;
                $tabelka['current'][$character_one->character_short]['time']=0;
                $tabelka['current'][$character_one->character_short]['type']='';
            }
            $tabelka['current']['stay']['sick_average']=0;
            
            
            $work_characters_month = 
            \App\WorkTime::get_worktime_characters()
                ->where('simmed_technician_id','=',$technician_one->id)
                //->orWhereNull('simmed_technician_id')
                ->where('simmed_date','>=',$filtr['start'])
                ->where('simmed_date','<=',$filtr['stop'])
                ->get();
            foreach ($work_characters_month as $row_one)
            {
                $tabelka['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
                $tabelka['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
                $tabelka['current'][$row_one->worktime_type]['time']=$row_one->worktime_minutes;            
            }

            $stay_days=\App\Simmed::select('simmed_date')
                ->where('simmed_date','>=',$filtr['start'])
                ->where('simmed_date','<=',$filtr['stop'])
                ->where('simmed_technician_id','=',$technician_one->id)
                ->where('simmed_technician_character_id','=',5) //stay
                ->get()
                ->toArray();


            $ill_days = 
            \App\WorkTime::select('date')
                ->where('date','>=',$filtr['start'])
                ->where('date','<=',$filtr['stop'])
                ->whereNotIn('date',$stay_days)
                ->where('user_id','=',$technician_one->id)
                ->where('work_time_types_id','=',5) //sick_time
                ->get()
                ->toArray();

            foreach ($ill_days as $day_one)
                {
                
                $sick_days=\App\Simmed::select(
                    \DB::raw('sum(TIMESTAMPDIFF(MINUTE, simmed_time_begin, simmed_time_end)) as sim_stay_minutes')
                    )
                ->where('simmed_date','=',$day_one)
                ->where('simmed_technician_character_id','=',5) //stay
                ->get()
                ->first()
                ->sim_stay_minutes;
                $tabelka['current']['stay']['sick_average']+=round($sick_days/$technicians_count);
                $tabelka['current']['stay']['time']+=round($sick_days/$technicians_count);
                $tabelka['current']['stay']['count']++;//bez tego przy zakresie dat obejmujących tyko chorobę na statystykach nie wyświetli się ilość godzin z przeliceniem na procenty
                $tabelka['current']['stay']['type']='stay';//a to jest potrzebne, bo jeśli w danym okresie nie ma symulacji tylko "chorobowe", to wyświetli się błąd (skorelowany z poprzednią linijką)
                $sick_total+=round($sick_days/$technicians_count);
                }
           
            $ret_table[]=$tabelka;
        }


        $work_total = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_date','>=',$filtr['start'])
            ->where('simmed_date','<=',$filtr['stop'])
            ->get();
        foreach ($work_total as $row_one)
        {
            $total['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
            $total['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
            $total['current'][$row_one->worktime_type]['time']=$row_one->worktime_minutes;
        }
        $total['current']['stay']['sick_time']=$sick_total;
        $total['current']['stay']['time']+=$sick_total;
        $total['technicians_count']=$technicians_count;

        return view('worktime/statpertech',['tabelka'=>$ret_table, 'total' => $total, 'characters' => $technician_char, 'filtr' => $filtr,  'extra_tab' => $extra_tab ]);

    }















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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Scenario $scenario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Scenario $scenario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
