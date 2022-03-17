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

        $months['2022-02']='2022-02';
        $months['2022-03']='2022-03';
    
        
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

    return view('worktime/dayinfo',['user'=>$userdata, 'simmeds' => $simmeds, 'work_times' => $work_times, 'work_time_types' => $work_time_types, 'date' => $date ]);
    }


    public function save_data(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Technik'))
        return view('error',['head'=>'błąd wywołania funkcji save_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        if (!Auth::user()->hasRole('Operator Kadr') 
            && !Auth::user()->hasRole('Administrator') 
            && !(Auth::user()->id == ($request->user_id*1)) 
            )
            {
                dump('tylko właściciel może edytować wpis');        
            }
        elseif (!Auth::user()->hasRole('Operator Kadr') 
            && !Auth::user()->hasRole('Administrator') 
            && ($request->date<=date('Y-m-d',strtotime('now - 7 days')))
            )
            {
                dump('zbyt wczesna data do edycji');        
            }
            
        $TimeWork=\App\WorkTime::find($request->id);
        $TimeWork->work_time_types_id   = $request->work_time_types_id;
        $TimeWork->time_begin           = $request->modal_start;
        $TimeWork->time_end             = $request->modal_end;
        $TimeWork->description          = $request->modal_description;
        // $TimeWork->date                 = $request->date;
        // $TimeWork->user_id              = $request->user_id;
        $TimeWork->save();
        //dump($request->id,$request);



        return app('App\Http\Controllers\WorkTimeController')->day_data($request->date, $request->user_id);
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

        return view('worktime/month',['user'=>$user, 'months' => $months, 'filtr' => $filtr, 'tabelka' => $table, 'total' => $total ]);

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


        return view('worktime/statpertech',['tabelka'=>$ret_table, 'total' => $total, 'characters' => $technician_char, 'filtr' => $filtr, 'technicians_list' => $technicians_list, 'instructors_list' => $instructors_list, 'subjects_list' => $subjects_list, 'technician_char' => $technician_char, 'room_list' =>$room_list, 'extra_tab' => $extra_tab ]);

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
