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
        \App\Simmed::get_technician_character_times()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->where('simmed_date','>=',date('Y-m-d',$begin))
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
    

        $total['work_characters'] = 
        \App\Simmed::get_technician_character_times()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->get();

        $total['month_data'] = 
        \App\WorkMonth::select('*')
            ->where('user_id','=',$filtr['user'])
            ->where('work_month','=',date('Y-m-d',$begin))
            ->get()
            ->first();

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
        \App\Simmed::get_technician_character_times()
            ->where('simmed_technician_id','=',$filtr['user'])
            ->where('simmed_date','>=',date('Y-m-d',$begin))
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
    

        $total['work_characters'] = 
        \App\Simmed::get_technician_character_times()
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
            \App\Simmed::get_technician_character_times()
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
        \App\Simmed::get_technician_character_times()
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
                $filtr['start'] = \App\Simmed::selectRaw('min(simmed_date) as minvalue')->get()->first()->minvalue;
                $filtr['stop'] = date('Y-m-t');
                $filtr['perspective']="characters";
                $filtr['transposition']='std';
            }
        else
            {
            $filtr['start'] = $request->start;
            $filtr['stop'] = $request->stop;
            $filtr['perspective']=$request->perspective;
            $filtr['transposition']=$request->transposition;
            }

        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }
        
        $colour[1]='#cfc';
        $colour[2]='#fcc';
        $colour[3]='#ccf';
        $colour[4]='#9cf';
        $colour[5]='#cf9';
        $colour[6]='#f9c';
        $colour[7]='#fcf';
        $colour[8]='#cff';
        $colour[9]='#ffc';
        $colour[10]='#fda';
        $colour[11]='#eee';
        $i=1;


        $return_table=null;

        $technicians_list=User::role_users('technicians', 1, 1)->orderBy('name')->get()->toArray();
        
        $return_table['info']['technicians_count']=count($technicians_list)-1;
        $technicians_count=count($technicians_list)-1;


        $technicians_null['id']=null;
        $technicians_null['name']='brak wpisu';
        $technicians_null['firstname']='techfirst null';
        $technicians_null['lastname']='techlast null';

        $technicians_list[]=$technicians_null;

        foreach ($technicians_list as $row_one)
        {
            $technician_row[$row_one['id']]['id']=$row_one['id'];
            $technician_row[$row_one['id']]['name']=$row_one['name'];
            $technician_row[$row_one['id']]['count']=0;
            $technician_row[$row_one['id']]['time']=0;

            $return_table['total']['technician'][$row_one['id']]['id']=$row_one['id'];
            $return_table['total']['technician'][$row_one['id']]['name']=$row_one['name'];
            $return_table['total']['technician'][$row_one['id']]['colour']=$colour[$i++];
            $return_table['total']['technician'][$row_one['id']]['count']=0;
            $return_table['total']['technician'][$row_one['id']]['time']=0;
            if ($i==11) $i=1;
        }










        switch ($filtr['perspective'])
        {
            case "characters":
            case "charactersill":
            case "charactersnoill":
                
                //tworzę kolejną część pustej tabeli wynikowej 
                //$return_table['heads'][0]=['perspective_id' => 0, 'name' =>'charakter'];
                $perspective_list=TechnicianCharacter::select('id as perspective_id', 'character_short as perspective_name')->orderBy('character_short')->get();
                foreach ($perspective_list as $row_one)
                {
                    $return_table['heads'][$row_one->perspective_id]=['perspective_id' => $row_one->perspective_id, 'name' =>$row_one->perspective_name];

                    $return_table['data'][$row_one->perspective_id]['info']['id']=$row_one->perspective_id;
                    $return_table['data'][$row_one->perspective_id]['info']['name']=$row_one->perspective_name;
                    $return_table['data'][$row_one->perspective_id]['data']=$technician_row;
                    $return_table['data'][$row_one->perspective_id]['perspective_total_count']=0;
                    $return_table['data'][$row_one->perspective_id]['perspective_total_time']=0;                    
                }


                //wypelniam tabelę wynikową informacjami o symulacjach
                if (
                    ($filtr['perspective'] == "characters") ||
                    ($filtr['perspective'] == "charactersnoill")
                    )
                    foreach ($technicians_list as $row_one)
                    {
                    $work_characters_month = 
                    \App\Simmed::get_technician_character_times()
                        ->where('simmed_technician_id','=',$row_one['id'])
                        //->WhereNotNull('simmed_technician_id')
                        ->where('simmed_date','>=',$filtr['start'])
                        ->where('simmed_date','<=',$filtr['stop'])
                        ->where('simmed_status','<>',4)                 // bez usuiętych symulacji
                        ->get();
                        foreach ($work_characters_month as $row_two)
                        {
                            $return_table['data'][$row_two->character_id]['data'][$row_one['id']]['count']=$row_two->worktime_count;
                            $return_table['data'][$row_two->character_id]['data'][$row_one['id']]['time']=$row_two->worktime_minutes;
                
                            $return_table['data'][$row_two->character_id]['perspective_total_count']+=$row_two->worktime_count;
                            $return_table['data'][$row_two->character_id]['perspective_total_time']+=$row_two->worktime_minutes;

                            $return_table['total']['technician'][$row_one['id']]['count']+=$row_two->worktime_count;
                            $return_table['total']['technician'][$row_one['id']]['time']+=$row_two->worktime_minutes;
                        }
                    }

                //uzupełniam tabele wynikową czasów symulacji średnim czasem symulacji dla osób, które były poza pracą (np. L4)
                //i jest to czas, który ma mieć zaliczoną średnią symulacji - average_for_statistic  
                if (
                    ($filtr['perspective'] == "characters") ||
                    ($filtr['perspective'] == "charactersill")
                    )
                    foreach ($technicians_list as $row_one)
                    {   
                        //tworzę wykaz dni, w których dana osoba miała czas pracy z grupy "average_for_statistic"
                        $ill_days = 
                        \App\WorkTime::select('date')
                            ->leftjoin('work_time_types','work_time_types_id','=','work_time_types.id')
                            ->where('date','>=',$filtr['start'])
                            ->where('date','<=',$filtr['stop'])
                            ->where('user_id','=',$row_one['id'])
                            ->where('average_for_statistic','=',1) //work_time_type with average_for_statistic
                            ->get()
                            ->toArray();
                        
                        if (count($ill_days)>0)
                        {
                            foreach ($perspective_list as $row_two)
                            {
                                $return_table['sick_time'][$row_one['id']][$row_two['perspective_id']]['sick_time']=0;
                                $return_table['sick_time'][$row_one['id']][$row_two['perspective_id']]['sick_count']=0;            
                            }
                            //obliczam czas, tworzę wykaz dni, w których dana osoba miała czas pracy z grupy "average_for_statistic"
                            foreach ($ill_days as $day_one)
                            {
                                
                                
                                $sick_times=\App\Simmed::select(
                                    'simmed_technician_character_id as perspective_id',
                                    \DB::raw('sum(TIMESTAMPDIFF(MINUTE, simmed_time_begin, simmed_time_end)) as sim_add_minutes')
                                    )
                                ->where('simmed_date','=',$day_one['date'])
                                ->where('simmed_status','<>',4)                 // bez usuiętych symulacji
                                ->WhereNotNull('simmed_technician_id')          // do średniej "chorobowego" nie wliczamy symulacji bez techników
                                ->groupby('perspective_id')
                                ->get()
                                ;

                                foreach ($sick_times as $row_sick)
                                {
                                    $sick_add_minutes=round($row_sick->sim_add_minutes/$return_table['info']['technicians_count'],0);
                                    if ($sick_add_minutes>0)
                                        $sick_add_count=round(1/$return_table['info']['technicians_count'],1);
                                    else
                                        $sick_add_count=0;
                                    $return_table['sick_time'][$row_one['id']][$row_sick->perspective_id]['sick_time']+=$sick_add_minutes;
                                    $return_table['sick_time'][$row_one['id']][$row_sick->perspective_id]['sick_count']+=$sick_add_count;
                                    
                                    $return_table['data'][$row_sick->perspective_id]['data'][$row_one['id']]['count']+=$sick_add_count;
                                    $return_table['data'][$row_sick->perspective_id]['data'][$row_one['id']]['time']+=$sick_add_minutes;
                                    
                                    $return_table['data'][$row_sick->perspective_id]['perspective_total_count']+=$sick_add_count;
                                    $return_table['data'][$row_sick->perspective_id]['perspective_total_time']+=$sick_add_minutes;
            
                                    $return_table['total']['technician'][$row_one['id']]['count']+=$sick_add_count;
                                    $return_table['total']['technician'][$row_one['id']]['time']+=$sick_add_minutes;
            
                                }
        

                                //dump($row_one['id'].' '.$day_one['date'].' licz: '.$sick_times->count(),$sick_times->first()->perspective_id,$sick_times->first()->sim_add_minutes);    
                            }
                        //dump($return_table['sick_time']);
                    }
                }

                break;






                case "leaders":
                case "rooms":
                case "subjects":

                    switch ($filtr['perspective'])
                        {
                        case "leaders":
                            $perspective_id     ='simmed_leader_id';
                            $perspective_name   ='concat(users.lastname," ",users.firstname)';
                            $perspective_name   ='users.name';
                            $join_table         ='users';
                            $join_simmed        ='simmeds.simmed_leader_id';
                            $join_id            ='users.id';
                            break;
                        case "rooms":
                            $perspective_id     ='room_id';
                            $perspective_name   ='rooms.room_number';
                            $join_table         ='rooms';
                            $join_simmed        ='simmeds.room_id';
                            $join_id            ='rooms.id';
                            break;
                        case "subjects":
                            $perspective_id     ='student_subject_id';
                            $perspective_name   ='student_subjects.student_subject_name';
                            $join_table         ='student_subjects';
                            $join_simmed        ='simmeds.student_subject_id';
                            $join_id            ='student_subjects.id';
                        }



                    //tworzę kolejną część pustej tabeli wynikowej 
                    //$return_table['heads'][0]=['perspective_id' => 0, 'name' =>'charakter'];
                    if ($filtr['perspective'] == "leaders")
                    {
                        $perspective_list=\App\Simmed::select($perspective_id.' as perspective_id', 
                            \DB::raw('concat(users.lastname," ",users.firstname) as perspective_name') 
                            )
                            ->leftjoin($join_table,$join_simmed,'=',$join_id)
                            ->where('simmed_date','>=',$filtr['start'])
                            ->where('simmed_date','<=',$filtr['stop'])
                            ->WhereNotNull('simmed_leader_id')
                            ->WhereNotNull('simmed_technician_id')
                            ->where('simmed_status','<>',4)                 // bez usuiętych symulacji
                            ->groupBy('perspective_id', 'perspective_name')
                            ->orderBy('perspective_name')
                            //->pluck('perspective_id');
                            ->get();
                    }
                    else
                    {
                        $perspective_list=\App\Simmed::select($perspective_id.' as perspective_id', 
                                $perspective_name.' as perspective_name') 
                                ->leftjoin($join_table,$join_simmed,'=',$join_id)
                                ->where('simmed_date','>=',$filtr['start'])
                                ->where('simmed_date','<=',$filtr['stop'])
                                ->WhereNotNull('simmed_leader_id')
                                ->WhereNotNull('simmed_technician_id')
                                ->where('simmed_status','<>',4)                 // bez usuiętych symulacji
                                ->groupBy('perspective_id', 'perspective_name')
                                ->orderBy('perspective_name')
                                //->pluck('perspective_id');
                                ->get();
                    }


                    foreach ($perspective_list as $row_one)
                    {
                        $return_table['heads'][$row_one->perspective_id]=['perspective_id' => $row_one->perspective_id, 'name' =>$row_one->perspective_name];

                        $return_table['data'][$row_one->perspective_id]['info']['id']=$row_one->perspective_id;
                        $return_table['data'][$row_one->perspective_id]['info']['name']=$row_one->perspective_name;
                        $return_table['data'][$row_one->perspective_id]['data']=$technician_row;
                        $return_table['data'][$row_one->perspective_id]['perspective_total_count']=0;
                        $return_table['data'][$row_one->perspective_id]['perspective_total_time']=0;
                    }


                    //wypelniam tabelę wynikową informacjami o symulacjach
                    foreach ($technicians_list as $row_one)
                    {
                    $work_characters_month =
                    DB::table('simmeds')
                    ->select(
                        $perspective_id.' as character_id',
                        \DB::raw('count('.$perspective_id.') as worktime_count'),
                        \DB::raw('sum(TIMESTAMPDIFF(MINUTE, simmed_time_begin, simmed_time_end)) as worktime_minutes')
                        )
                        ->where('simmed_status','<>',4)
                        ->where('simmed_technician_id','=',$row_one['id'])
                        ->WhereNotNull('simmed_leader_id')
                        ->WhereNotNull('simmed_technician_id')
                        ->where('simmed_date','>=',$filtr['start'])
                        ->where('simmed_date','<=',$filtr['stop'])
                        ->where('simmed_status','<>',4)                 // bez usuiętych symulacji
                        ->groupBy('character_id')
                        //->groupBy('worktime_type')
                        ->get();


                            foreach ($work_characters_month as $row_two)
                        {
                            $return_table['data'][$row_two->character_id]['data'][$row_one['id']]['count']=$row_two->worktime_count;
                            $return_table['data'][$row_two->character_id]['data'][$row_one['id']]['time']=$row_two->worktime_minutes;
                
                            $return_table['data'][$row_two->character_id]['perspective_total_count']+=$row_two->worktime_count;
                            $return_table['data'][$row_two->character_id]['perspective_total_time']+=$row_two->worktime_minutes;

                            $return_table['total']['technician'][$row_one['id']]['count']+=$row_two->worktime_count;
                            $return_table['total']['technician'][$row_one['id']]['time']+=$row_two->worktime_minutes;
                        }
                    }
                    //dd($return_table);
                break;


                default:
                    dd('WorkTimeController Statistics per technicians - wrong perspective...');
    
    

        }





        

        return view('worktime/statpertech',['return_table' => $return_table, 'filtr' => $filtr ]);

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
