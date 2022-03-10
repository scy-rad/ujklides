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
            $filtr['user'] = Auth::user()->id;
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


    public function day_data($date, $user)
    {
        $work_characters_month =
            \App\Simmed::simmeds_join('without_free','without_deleted')
                ->where('simmed_date','=',$date)
                ->where('simmeds.simmed_technician_id',$user)
                ->orderBy('time')
                ->orderBy('room_number')
                ->get(); 

    return view('worktime/dayinfo',['user'=>'userek', 'work_characters_month' => $work_characters_month ]);
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

        $begin = strtotime($filtr['month'].'-01');
        $end   = strtotime($filtr['month'].'-01 + 1 month');
        
        $technician_list=User::role_users('technicians', 1, 1)->get();
        $technician_char=TechnicianCharacter::all();

        foreach ($technician_list as $technician_one)
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
                $tabelka['previous'][$character_one->character_short]['count']=0;
                $tabelka['previous'][$character_one->character_short]['time']=0;
                $tabelka['previous'][$character_one->character_short]['type']='';
                $tabelka['to_date'][$character_one->character_short]['count']=0;
                $tabelka['to_date'][$character_one->character_short]['time']=0;
                $tabelka['to_date'][$character_one->character_short]['type']='';
            }

            $work_characters_month = 
            \App\WorkTime::get_worktime_characters()
                ->where('simmed_technician_id','=',$technician_one->id)
                ->where('simmed_date','>=',date('Y-m-d',$begin))
                ->where('simmed_date','<',date('Y-m-d',$end))
                ->get();

            foreach ($work_characters_month as $row_one)
            {
                $tabelka['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
                $tabelka['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
                $tabelka['current'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
            }
     
            $work_characters_month = 
            \App\WorkTime::get_worktime_characters()
                ->where('simmed_technician_id','=',$technician_one->id)
                ->where('simmed_date','<',date('Y-m-d',$begin))
                ->get();

            foreach ($work_characters_month as $row_one)
            {
                $tabelka['previous'][$row_one->worktime_type]['type']=$row_one->worktime_type;
                $tabelka['previous'][$row_one->worktime_type]['count']=$row_one->worktime_count;
                $tabelka['previous'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
            }


            $work_characters_month = 
            \App\WorkTime::get_worktime_characters()
                ->where('simmed_technician_id','=',$technician_one->id)
                ->where('simmed_date','<',date('Y-m-d',$end))
                ->get();

            foreach ($work_characters_month as $row_one)
            {
                $tabelka['to_date'][$row_one->worktime_type]['type']=$row_one->worktime_type;
                $tabelka['to_date'][$row_one->worktime_type]['count']=$row_one->worktime_count;
                $tabelka['to_date'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
                //$tabelka['to_date'][$row_one->worktime_type]['total']=$work_total->worktime_hours*60+$work_total->worktime_minutes;
            }

            
            $ret_table[]=$tabelka;
        }



        foreach ($technician_char as $character_one)
        {
            $work_total['current'][$character_one->character_short]['count']=0;
            $work_total['current'][$character_one->character_short]['time']=0;
            $work_total['previous'][$character_one->character_short]['count']=0;
            $work_total['previous'][$character_one->character_short]['time']=0;
            $work_total['to_date'][$character_one->character_short]['count']=0;
            $work_total['to_date'][$character_one->character_short]['time']=0;
        }


        $work_total = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_date','>=',date('Y-m-d',$begin))
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
        foreach ($work_total as $row_one)
        {
            $total['current'][$row_one->worktime_type]['type']=$row_one->worktime_type;
            $total['current'][$row_one->worktime_type]['count']=$row_one->worktime_count;
            $total['current'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
        }

        $work_total = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_date','<',date('Y-m-d',$begin))
            ->get();
        foreach ($work_total as $row_one)
        {
            $total['previous'][$row_one->worktime_type]['type']=$row_one->worktime_type;
            $total['previous'][$row_one->worktime_type]['count']=$row_one->worktime_count;
            $total['previous'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
        }

        $work_total = 
        \App\WorkTime::get_worktime_characters()
            ->where('simmed_date','<',date('Y-m-d',$end))
            ->get();
        foreach ($work_total as $row_one)
        {
            $total['to_date'][$row_one->worktime_type]['type']=$row_one->worktime_type;
            $total['to_date'][$row_one->worktime_type]['count']=$row_one->worktime_count;
            $total['to_date'][$row_one->worktime_type]['time']=$row_one->worktime_hours*60+$row_one->worktime_minutes;
        }


        return view('worktime/statistics',['tabelka'=>$ret_table, 'total' => $total, 'characters' => $technician_char ]);

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
