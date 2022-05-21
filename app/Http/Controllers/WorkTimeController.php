<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\WorkTime;
use App\User;
use App\TechnicianCharacter;
use Illuminate\Support\Facades\Mail;


class WorkTimeController extends Controller
{
    

    public function month_data(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator') && !Auth::user()->hasRole('Technik'))
        return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);


        if ( ($request->workcard=='generate') && (\App\WorkAttendance::where('date','=',$request->month.'-01')->get()->first() != null) ) 
        return back()->withErrors(['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'nie możesz generować karty czasu pracy','description'=>'Wygenerowana jest już lista obecności za '.$request->month]);

        

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
            if (Auth::user()->hasRoleCode('workers'))
                $filtr['user'] = Auth::user()->id;
            else
                $filtr['user'] = \App\User::role_users('workers', 1, 0)
                ->orderBy('name')->get()->first()->id; 
            }
        else
            $filtr['user'] = $request->technician;

        $user = user::find($filtr['user']);

        $begin = strtotime($filtr['month'].'-01');
        //$end   = strtotime(date("Y-m-t", strtotime($filtr['month'].'-01')));
        $end   = strtotime($filtr['month'].'-01 + 1 month');
        
        $total['minutes']=0;
        $total['hrminutes']=0;
        $total['hrminutes_over']=0;
        $total['hrminutes_under']=0;
        $total['hr_minutes']=0;
        $y=1;
        $ret=[];
        for($i = $begin; $i < $end; $i = $i+86400 )
        {
            $cur_date=date('Y-m-d',$i);

            $ret_row=WorkTime::calculate_work_time($filtr['user'], $cur_date);

            $ret_row['sims'] = \App\Simmed::simmeds_join('without_free','without_deleted','without_send')
                ->where('simmed_date','=',$cur_date)
                ->where('simmed_technician_id','=',$filtr['user'])
                ->orderBy('simmed_date')
                ->orderBy('time')
                ->orderBy('room_number')
                ->get()
                ->toArray()
                ;
            $ret[$cur_date]=$ret_row;
            $ret_hr=\App\WorkTimeToHr::select('*')
            ->where('date','=',$cur_date)
            ->where('user_id','=',$filtr['user'])
            ->first();

            if ( ($request->workcard=='generate') && (Auth::user()->hasRole('Operator Kadr')) )
            {
                    if (count($ret_row['work_types'])>0)  // jeśli jest zaplanowany czas pracy
                    {
                        if (is_null($ret_hr))
                            $ret_hr = new \App\WorkTimeToHr;

                        $ret_hr->user_id = $filtr['user'];
                        foreach ($ret_row['work_types'] as $key => $value)
                            $ret_hr->work_time_types_id = $key;
                        $ret_hr->date       = $cur_date;
                        $ret_hr->time_begin = $ret_row['all_times']['start'];
                        $ret_hr->time_end   = $ret_row['all_times']['end'];
                        $ret_hr->minutes   = $ret_row['minutes'];
                        //$ret_hr->description = 'zmienione lub nie albo nowe';
                        if ($ret_row['minutes']>480)    //praca powyżej 8 godzin
                            {
                                $ret_hr->over_under = 1; //over time
                                $ret_hr->o_minutes = $ret_row['minutes']-480;
                                $ret_hr->o_time_begin = date('H:i',strtotime($ret_row['all_times']['start'].' + 8 hours'));
                                $ret_hr->o_time_end   = $ret_row['all_times']['end'];
                            }
                        elseif ($ret_row['minutes']<480)    //praca poniżej 8 godzin
                            {
                                $ret_hr->over_under = 2; //under time
                                $ret_hr->o_minutes = 480-$ret_row['minutes'];

                                if (strtotime($ret_row['all_times']['start']) <= strtotime('7:30'))
                                {
                                    $ret_hr->o_time_begin   = $ret_row['all_times']['start'];
                                    $ret_hr->o_time_end     = date('H:i',strtotime($ret_row['all_times']['start'].' + 8 hours'));
                                }
                                elseif (strtotime($ret_row['all_times']['start'].' - '.$ret_hr->o_minutes.' minutes') > strtotime('7:30') )
                                {
                                    $ret_hr->o_time_begin   = date('H:i',strtotime($ret_row['all_times']['start'].' - '.$ret_hr->o_minutes.' minutes'));
                                    $ret_hr->o_time_end     = $ret_row['all_times']['end'];
                                }
                                
                                else
                                {
                                    $ret_hr->o_time_begin   = date('H:i',strtotime('7:30'));
                                    $ret_hr->o_time_end     = date('H:i',strtotime('15:30'));
                                }
                            }
                        elseif ($ret_row['minutes']==480)    //praca w wymiarze 8 godzin
                            {
                                $ret_hr->over_under = 0; //under time
                                $ret_hr->o_minutes = 0;
                                $ret_hr->o_time_begin   = null;
                                $ret_hr->o_time_end = null;
                            }
                        $ret_hr->status = 1;
                        $ret_hr->save();
                    }
            
                    elseif (!(is_null($ret_hr)) )   // jeśli nie ma zaplanowanego czasu pracy, a istnieje wpis przekazany do kadr:
                    {
                        $ret_hr->time_begin = null;
                        $ret_hr->time_end = null;
                        $ret_hr->minutes   = null;
                        $ret_hr->over_under   = 0;
                        //$ret_hr->description = 'usunięte';
                        $ret_hr->save();
                    }
                    // else - jak nie ma wpisu dla kadr i czasu pracy - to nas to nie obchodzi :)
            }

            if (!(is_null(\App\WorkTimeToHr::where('user_id',$filtr['user'])
            ->where('date',$cur_date)
            ->first()) ))
                {
                    $ret[$cur_date]['hr_wt'] = \App\WorkTimeToHr::where('user_id',$filtr['user'])
                    ->where('date',$cur_date)
                    ->first()
                    ->toArray()
                    ;
                    $ret[$cur_date]['hr_wt']['o_time_end'] = substr($ret[$cur_date]['hr_wt']['o_time_end'],0,5);
                    $ret[$cur_date]['hr_wt']['o_time_begin'] = substr($ret[$cur_date]['hr_wt']['o_time_begin'],0,5);
                    $ret[$cur_date]['hr_wt']['time_end'] = substr($ret[$cur_date]['hr_wt']['time_end'],0,5);
                    $ret[$cur_date]['hr_wt']['time_begin'] = substr($ret[$cur_date]['hr_wt']['time_begin'],0,5);
                    
                    if (is_null($ret[$cur_date]['hr_wt']['time_begin'])) $ret[$cur_date]['hr_wt']['time_begin']='-';
                    if (is_null($ret[$cur_date]['hr_wt']['time_end']))   $ret[$cur_date]['hr_wt']['time_end']  ='-';
                    $ret[$cur_date]['hr_wt']['hoursmin']=m2h($ret[$cur_date]['hr_wt']['minutes']);
                    $ret[$cur_date]['hr_wt']['o_hoursmin']=m2h($ret[$cur_date]['hr_wt']['o_minutes']);

                    if ($ret[$cur_date]['hr_wt']['over_under']==2)  //jeśli ilośc godzin pracy jest poniżej normy - to ustal wprost tekst z godzinami poniżej normy 
                    {
                        $coma='';
                        $ret[$cur_date]['hr_wt']['under_txt']='';
                        if ( date('H:i',strtotime($ret[$cur_date]['hr_wt']['o_time_begin'])) < date('H:i',strtotime($ret[$cur_date]['hr_wt']['time_begin'])) )
                            {
                            $ret[$cur_date]['hr_wt']['under_txt']='od '.date('H:i',strtotime($ret[$cur_date]['hr_wt']['o_time_begin'])).' do '.date('H:i',strtotime($ret[$cur_date]['hr_wt']['time_begin']));
                            $coma=' i ';
                            }
                        if ( date('H:i',strtotime($ret[$cur_date]['hr_wt']['o_time_end'])) > date('H:i',strtotime($ret[$cur_date]['hr_wt']['time_end'])) )
                            {
                            $ret[$cur_date]['hr_wt']['under_txt'].=$coma.'od '.date('H:i',strtotime($ret[$cur_date]['hr_wt']['time_end'])).' do '.date('H:i',strtotime($ret[$cur_date]['hr_wt']['o_time_end']));
                            }
                        //i określ wprot godziny pracy do grafiku (8h)
                            $ret[$cur_date]['hr_wt']['hr_time_begin']=$ret[$cur_date]['hr_wt']['o_time_begin'];
                            $ret[$cur_date]['hr_wt']['hr_time_end']=$ret[$cur_date]['hr_wt']['o_time_end'];
                    }
                    elseif ($ret[$cur_date]['hr_wt']['over_under']==1)  //jeśli ilośc godzin pracy jest powyżej normy
                    {
                        $ret[$cur_date]['hr_wt']['hr_time_begin']=$ret[$cur_date]['hr_wt']['time_begin'];
                        $ret[$cur_date]['hr_wt']['hr_time_end']=$ret[$cur_date]['hr_wt']['o_time_begin'];
                    }
                    else
                    {
                        if ($ret[$cur_date]['hr_wt']['time_begin'] == $ret[$cur_date]['hr_wt']['time_end'])
                        {
                            $ret[$cur_date]['hr_wt']['hr_time_begin']='x';
                            $ret[$cur_date]['hr_wt']['hr_time_end']='x';
                        }
                        else
                        {
                            $ret[$cur_date]['hr_wt']['hr_time_begin']=$ret[$cur_date]['hr_wt']['time_begin'];
                            $ret[$cur_date]['hr_wt']['hr_time_end']=$ret[$cur_date]['hr_wt']['time_end'];
                        }
                    }
                    $ret[$cur_date]['hr_wt']['hr_minutes']=8*60;
                    $ret[$cur_date]['hr_wt']['hr_hoursmin']=m2h(8*60);
                    
                }
            else 
            {
                $ret[$cur_date]['hr_wt']['time_begin']='-';
                $ret[$cur_date]['hr_wt']['time_end']='-';
                $ret[$cur_date]['hr_wt']['minutes']=null;
                $ret[$cur_date]['hr_wt']['hoursmin']=null;
                $ret[$cur_date]['hr_wt']['over_under']=0;
                $ret[$cur_date]['hr_wt']['hr_time_begin']=$ret[$cur_date]['hr_wt']['time_begin'];
                $ret[$cur_date]['hr_wt']['hr_time_end']=$ret[$cur_date]['hr_wt']['time_end'];
                $ret[$cur_date]['hr_wt']['hr_minutes']=null;
                $ret[$cur_date]['hr_wt']['hr_hoursmin']=null;
            }

            if ( ($ret[$cur_date]['hr_wt']['time_begin'] == $ret[$cur_date]['times'][0]['start']) &&
                 ($ret[$cur_date]['hr_wt']['time_end'] == $ret[$cur_date]['times'][0]['end']))
                $ret[$cur_date]['hr_diffrent']=true;
            else
                $ret[$cur_date]['hr_diffrent']=false;
            
            // dump($ret[$cur_date]['hr_wt']);

            $ret[$cur_date]['day_name']= DB::table('pl_days')->find(date('w', strtotime($cur_date))+1)->pl_day;
            $ret[$cur_date]['day_week']= date('N', strtotime($cur_date));
            $ret[$cur_date]['day_name_short']= DB::table('pl_days')->find(date('w', strtotime($cur_date))+1)->pl_day_short;
            $ret[$cur_date]['hoursmin']=m2h($ret_row['minutes']);
            if ( (count($ret_row['work_types'])>1) || (count($ret_row['times'])>1) )
                $ret[$cur_date]['warning']='yes';           //look there
            else                                            //look there
                $ret[$cur_date]['warning']='no';            //look there

            $total['minutes']+=$ret_row['minutes'];
            $total['hr_minutes']+=$ret[$cur_date]['hr_wt']['hr_minutes'];
            if ($ret[$cur_date]['hr_wt']['over_under']==1)
                $total['hrminutes_over']+=$ret[$cur_date]['hr_wt']['o_minutes'];
            if ($ret[$cur_date]['hr_wt']['over_under']==2)
                $total['hrminutes_under']+=$ret[$cur_date]['hr_wt']['o_minutes'];
        }


        $total['times'] = m2h($total['minutes']);
        $total['hrtimes'] = m2h($total['hrminutes']);
        $total['hrtimes_over'] = m2h($total['hrminutes_over']);
        $total['hrtimes_under'] = m2h($total['hrminutes_under']);
        
        $total['hr_times'] = m2h($total['hr_minutes']);
        

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

        if ($total['month_data']==null)
            return back()->withErrors(['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'nie ustalono miesięcznego czasu pracy','description'=>'Określ pracownkowi MIESIĘCZNY CZAS PRACY (Administracja)']);

        
        $total['month_name']=DB::table('pl_months')->find(date('m', $begin))->pl_month;
        $total['year']=date('Y', $begin);


        if ( date('Y-m-d',strtotime($filtr['month'].'-01')) < date('Y-m-d',strtotime($total['year'].'-03-31')) )
        {
            $total['quarter'] = 'I';
            $total['quarter_start']=$total['year'].'-01-01';
        }
        elseif ( date('Y-m-d',strtotime($filtr['month'].'-01')) < date('Y-m-d',strtotime($total['year'].'-06-30')) )
        {
            $total['quarter'] = 'II';
            $total['quarter_start']=$total['year'].'-04-01';
        }
        elseif ( date('Y-m-d',strtotime($filtr['month'].'-01')) < date('Y-m-d',strtotime($total['year'].'-09-31')) )
        {
            $total['quarter'] = 'III';
            $total['quarter_start']=$total['year'].'-07-01';
        }
        else
        {
            $total['quarter'] = 'IV';
            $total['quarter_start']=$total['year'].'-10-01';
        }

        $total['quarter_stop']=date('Y-m-t',strtotime($filtr['month'].'-01'));

        if (isset($request->csv))
        {
            $filename="czas_pracy.csv";
            $fp = fopen($filename, 'w');
            fputcsv($fp, [],';');
            fputcsv($fp, [$user->lastname,$user->firstname],';');
            fputcsv($fp, ['godziny do przepracowania: ',$total['month_data']['hours_to_work']],';');
            fputcsv($fp, ['godziny przepracowane: ',$total['times'] ],';');
    
            $to_csv[]='data';
            $to_csv[]='dz.tyg.';
            $to_csv[]='od';
            $to_csv[]='do';
            $to_csv[]='godz.';
            $to_csv[]='godz(+)';
            $to_csv[]='godz(-)';
            fputcsv($fp,$to_csv,';');
    
            $min_plus=0;
            $min_minus=0;
        
                foreach ($ret as $row_one)
                {
                    $to_csv=null;
                    $to_csv[]=$row_one['date'];
                    $to_csv[]=$row_one['day_name'];
                    $to_csv[]=$row_one['times'][0]['start'];
                    $to_csv[]=$row_one['times'][0]['end'];
                    $to_csv[]=m2h($row_one['minutes']);
                    if ( ($row_one['times'][0]['start']<>'-') && ($row_one['minutes']>480) )
                        {
                        $to_csv[]=m2h($row_one['minutes']-480);
                        $min_plus+=$row_one['minutes']-480;
                        }
                    else 
                        $to_csv[]='';
                        if ( ($row_one['times'][0]['start']<>'-') && ($row_one['minutes']<480) )
                        {
                        $to_csv[]=m2h(480-$row_one['minutes']);
                        $min_minus+=480-$row_one['minutes'];
                        }
                    else 
                        $to_csv[]='';
                    fputcsv($fp,$to_csv,';');
                }

                    $to_csv=null;
                    $to_csv[]='razem';
                    $to_csv[]='';
                    $to_csv[]='';
                    $to_csv[]='';
                    $to_csv[]=$total['times'];
                    $to_csv[]=m2h($min_plus);
                    $to_csv[]=m2h($min_minus);
                
                    fputcsv($fp,$to_csv,';');

                fputcsv($fp,['koniec'],';');
                fputcsv($fp,[''],';');
                fclose($fp);
                      
            header('Content-type: text/csv');
            header('Content-disposition:attachment; filename="'.$filename.'"');
            readfile($filename);
    
            exit;
            //        dump(serialize($alltmps));
        }

        if ($request->workcard=='get')
        {
            $quarter=\App\WorkTimeToHr::
            select('over_under',
            \DB::raw('count(*) as quarter_count'),
            \DB::raw('sum(minutes) as quarter_minutes'),
            \DB::raw('sum(o_minutes) as quarter_o_minutes')
            )
            ->where('status','<>',4)
            ->where('user_id','=',$filtr['user'])
            ->where('date','>=',$total['quarter_start'])
            ->where('date','<=',$total['quarter_stop'])
            ->groupBy('over_under')
            ->get()
            ->toArray();

            if (count($quarter)<1)
                return back()->withErrors('Zgłoś administratorowi, że pułapka blade month_cardwork 400 się uaktywniła :) ');

            $total['quarter_count']=0;
            $total['quarter_minutes']=0;
            $total['quarter_norm']=0;

            foreach ($quarter as $quarter_one)
                {
                    $total['quarter_count']+=$quarter_one['quarter_count'];
                    $total['quarter_minutes']+=$quarter_one['quarter_minutes'];
                    if ($quarter_one['over_under']==1)
                        $total['quarter_norm']-=$quarter_one['quarter_o_minutes'];
                    else
                    $total['quarter_norm']+=$quarter_one['quarter_o_minutes'];
                }
            $total['quarter_norm']+=$total['quarter_minutes'];


            return view('worktime/month_cardwork',['user'=>$user, 'months' => $months, 'filtr' => $filtr, 'tabelka' => $ret, 'total' => $total ]);
        }
        elseif ($request->workcard=='generate') //sending e-mails
        {
            $roles_OpKadr_id=\App\Roles::where('roles_code', 'hroperators')
                ->pluck('id')
                ->toArray();
            $roles_OpKadr=\App\RolesHasUsers::whereIn('roles_has_users_roles_id',$roles_OpKadr_id)
                ->pluck('roles_has_users_users_id')
                ->toArray();

            dump('In line 437 user model temporary added happy user 13 :)');

            $coordinator_OpKadr_users = User::whereIn('id',$roles_OpKadr)
                ->orWhere('id',13)
                ->orWhere('id',$user->id)
                ->where('user_status','=',1)
                ->where('simmed_notify','=',1)
                ->get();


            $mail_data_address = [
                'title'=>'[SIMinfo] wygenerowano kartę czasu pracy: '.$user->full_name(),
                'name'=>$user->full_name(),
                'user'=>$user, 
                'months' => $months, 
                'filtr' => $filtr, 
                'tabelka' => $ret, 
                'total' => $total,
                
                'email' => $user->email,
                'subject_email'=>'[SIMinfo] wygenerowano kartę czasu pracy: '.$user->full_name(),
                'from_email' => 'technicy@wcsm.pl',
                'from_name' => 'Pegasus CSM UJK'
            ];
            $ret_info='';
            foreach ($coordinator_OpKadr_users as $sent_to)
            {
                $mail_data_address['email'] = $sent_to->email;
                    $ret_info.='<li>'.$sent_to->full_name().'</li>';
                $zwrocik=Mail::send('worktime.month_cardwork',$mail_data_address,function($mail) use ($mail_data_address)
                    {
                        $mail->from($mail_data_address['from_email'],$mail_data_address['from_name']);
                        $mail->to($mail_data_address['email'],$mail_data_address['name']);
                        $mail->subject($mail_data_address['subject_email']);
                    }
                );
            }
                return back()->with('success','Wygenerowano i wysłano kartę czasu pracy.<br><ol>'.$ret_info.'</ol>');    
        }
        else
            return view('worktime/month',['user'=>$user, 'months' => $months, 'filtr' => $filtr, 'tabelka' => $ret, 'total' => $total ]);

    }


    public function day_data($date, $user_id)
    {
        if (!Auth::user()->hasRoleCode('hroperators') && !Auth::user()->hasRoleCode('administrators') && !Auth::user()->hasRoleCode('technicians'))
            return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        if (!User::find($user_id)->hasRoleCode('workers'))
            return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'niewłaściwe pytanie','description'=>'czas pracy liczony jest tylko dla pracowników']);

        if ( \App\WorkMonth::select('*')->where('work_month','=',date('Y-m-01',strtotime($date)))->where('user_id',$user_id)->get()->count() == 0 )
            return view('error',['head'=>'błąd wywołania funkcji month_data kontrolera WorkTime','title'=>'niewłaściwa data','description'=>'dla podanego miesiąca nie wygenerowano jeszcze czasu pracy']);

        $simmeds =
            \App\Simmed::simmeds_join('without_free','without_deleted','without_send')
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
            if ($request->modal_start <= $request->modal_end)
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
            elseif (Auth::user()->hasRole('Operator Kadr'))
            {
                $TimeWork=\App\WorkTime::find($request->id);
                $TimeWork->status=4;
                $TimeWork->save();
                return back()->with('success','Usunięto zapis...');
            }
            else
            {
                return back()->withErrors('Usuwać wpisy może TYLKO Operator Kadr...');
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
                //$filtr['start'] = \App\Simmed::selectRaw('min(simmed_date) as minvalue')->get()->first()->minvalue;
                $filtr['start'] = \App\Param::select('*')->orderBy('id','desc')->get()->first()->statistics_start;
                $filtr['stop'] = \App\Param::select('*')->orderBy('id','desc')->get()->first()->statistics_stop;
                $filtr['technician'] = 'ANON';
                $filtr['character'] = 'ANON';
                $filtr['room'] = 'ANON';
                $filtr['instructor'] = 'ANON';
                $filtr['subject'] = 'ANON';
            }
        else
            {
            $filtr['start'] = $request->start;
            $filtr['stop'] = $request->stop;
            if (isset($request->technician))
                $filtr['technician'] = $request->technician;
            else
                $filtr['technician'] = null;
            $filtr['character'] = $request->character;
            $filtr['room'] = $request->room;
            $filtr['instructor'] = $request->instructor;
            $filtr['subject'] = $request->subject;
            if ( ($filtr['technician'] != 'ANON')  || 
                 ($filtr['character'] != 'ANON')  || 
                 ($filtr['room'] != 'ANON') ||
                 ($filtr['instructor'] != 'ANON') ||
                 ($filtr['subject'] != 'ANON')
                 
                 )
                {
                    $return=\App\Simmed::simmeds_join('with_free','without_deleted','without_send');
                    if ($filtr['technician']===0)
                        $return=$return->WhereNull('simmed_technician_id');
                    if ( ($filtr['technician']!='ANON') && ($filtr['technician']>0) )
                        $return=$return->where('simmed_technician_id',$filtr['technician']);
                    if ( ($filtr['character']!='ANON') && ($filtr['character']>0) )
                        $return=$return->where('simmed_technician_character_id',$filtr['character']);
                    if ( ($filtr['room']!='ANON') && ($filtr['room']>0) )
                        $return=$return->where('room_id',$filtr['room']);
                    if ( ($filtr['instructor']!='ANON') && ($filtr['instructor']>0) )
                        $return=$return->where('simmed_leader_id',$filtr['instructor']);
                    if ( ($filtr['subject']!='ANON') && ($filtr['subject']>0) )
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
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();

        $active_subjects=\App\Simmed::select('student_subject_id')
            ->where('simmed_date','>=',$filtr['start'])
            ->where('simmed_date','<=',$filtr['stop'])
            ->groupBy('student_subject_id')
            ->get();
        $subjects_list=\App\StudentSubject::select('*')
            ->whereIn('id',$active_subjects)
            ->orderBy('student_subject_name')
            ->get();


        $technicians_list=User::role_users('technicians', 1, 1)->orderBy('name')->get();
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
                //$filtr['start'] = \App\Simmed::selectRaw('min(simmed_date) as minvalue')->get()->first()->minvalue;
                $filtr['start'] = \App\Param::select('*')->orderBy('id','desc')->get()->first()->statistics_start;
                $filtr['stop'] = \App\Param::select('*')->orderBy('id','desc')->get()->first()->statistics_stop;
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





    public function show_attendances(Request $request)
    {



        $attendances_tab=\App\WorkTimeToHr::select(\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m") as dateHR'), 'work_attendances.date as dateWA')
        ->leftjoin('work_attendances',\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m")'),'=',\DB::raw('DATE_FORMAT(work_attendances.date,"%Y-%m")'))
        ->distinct()->OrderBy('dateHR', "DESC")->get();

    foreach ($attendances_tab as $attendance_one)
        {

            $attendances_users_ids=\App\WorkTimeToHr::where(\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m")'),'=',$attendance_one->dateHR)
            ->distinct()
            ->pluck('user_id')
            ->toArray();

            $technicians = \App\User::whereIn('id', $attendances_users_ids)
            ->orderBy('lastname')->orderBy('firstname')->get(); 
        $ret_table[]=['list' => $attendance_one, 'users' => $technicians];
        }
        return view('worktime/attendances',['big_table' => $ret_table]);
    }


    public function edit_attendance(Request $request)
    {

        if (!Auth::user()->hasRoleCode('hroperators'))
        return view('error',['head'=>'błąd wywołania funkcji edit_attendance kontrolera WorkTime','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

        switch ($request->action)
        {
        case 'remove':
            $to_remove=\App\WorkAttendance::where('date','=',$request->dateHR)->get()->first()->delete();
            break;
        case 'add':
            $to_add=new \App\WorkAttendance;
            $to_add->date = $request->dateHR;
            $to_add->save();
            break;
        }

        $attendances_tab=\App\WorkTimeToHr::select(\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m") as dateHR'), 'work_attendances.date as dateWA')
        ->leftjoin('work_attendances',\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m")'),'=',\DB::raw('DATE_FORMAT(work_attendances.date,"%Y-%m")'))
        ->distinct()->OrderBy('dateHR', "DESC")->get();

        return back()->with('success','usunięto lub stworzono listę obecności.');    
        //return view('worktime/attendances',['attendances_tab' => $attendances_tab]);
    }

    public function print_attendance(Request $request)
    {
        if ($request->users_table==null)
            return back()->withErrors(['head'=>'Nie można stworzyć listy obecności','title'=>'...','description'=>'Nie wybrano pracowników do listy']);


        $begin = strtotime($request->dateHR.'-01');
        $end   = strtotime($request->dateHR.'-01 + 1 month');
        $head['month_name']=DB::table('pl_months')->find(date('m', $begin))->pl_month;
        $head['year']=date('Y',$begin);

        $technicians = \App\User::wherein('id',$request->users_table)
        ->orderBy('lastname')->orderBy('firstname')->get(); 

        for($i = $begin; $i < $end; $i = $i+86400 )
        {
            $tab_day[date('Y-m-d',$i)]=['AL_begin' => '','AL_end' => '', 'cell_class' => "free_day"];
            $days_tab[date('Y-m-d',$i)]=['number'=>date('Y-m-d',$i), 'day'=>date('d',$i), 'year'=>date('Y',$i), 'day_of_week' => DB::table('pl_days')->find(date('w', $i)+1)->pl_day_short];
        }


        foreach($technicians as $technician_one)
        {
            $big_tab[$technician_one->id]=$tab_day;
            $users_tab[$technician_one->id]=$technician_one;
        }

        $attendances_tab=\App\WorkTimeToHr::select(
            'date',
            'user_id',
            DB::raw("(CASE WHEN over_under='2' THEN o_time_begin ELSE time_begin END) as AL_begin"),
            DB::raw("(CASE WHEN over_under='2' THEN o_time_end WHEN over_under='1' THEN o_time_begin ELSE time_end END) as AL_end")
        )
        ->where(\DB::raw('DATE_FORMAT(work_time_to_hrs.date,"%Y-%m")'),'=',$request->dateHR)
        ->orderBy('date')
        ->orderBy('user_id')
        ->get();

        foreach ($attendances_tab as $row_one)  
        {
            if (!(is_null($row_one->AL_begin)))
            $big_tab[$row_one->user_id][$row_one->date]['cell_class']="";
            $big_tab[$row_one->user_id][$row_one->date]['AL_begin']=substr($row_one->AL_begin,0,5);
            $big_tab[$row_one->user_id][$row_one->date]['AL_end']=substr($row_one->AL_end,0,5);
        }

    return view('worktime/print_attendance_list', [ 'big_tab' => $big_tab, 'users_tab' => $users_tab, 'days_tab' => $days_tab, 'head' => $head ]);


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
