<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class WorkTime extends Model
{
    //
    public $timestamps = true;

    // public function type_name() {
    //     return $this->hasOne(UserPhoneType::class,'id','user_phone_type_id');//->get()->first();
    // }
    public function type() {
        return $this->belongsTo(WorkTimeType::class, 'work_time_types_id');//->first();
    }

    public static function activity_for_scheduler($day) 
    {
        $simdays=Simmed::simmeds_join('without_free','without_deleted','without_send')
        ->where('simmed_date','=',$day);

        $workdays=WorkTime::select('*','work_times.description as simdescript')
        ->where('date','=',$day)
        ->where('work_times.status','=',1)
        ->leftjoin('work_time_types','work_times.work_time_types_id','=','work_time_types.id')
        ->get();

        $technicians=User::role_users('technicians', 1, 1)
        ->select('id', 'name as title', \DB::raw('"CSM tech" as subtitle'))
        ->orderBy('name')
        ->get();
        
        $tabela=null;
        $row_no=0;

        $tabela[0]['id']       = $row_no++;
        $tabela[0]['id_room']  = 0;
        $tabela[0]['number']   = '??';

        foreach ($technicians as $technician)
        {
            $tabela[$technician->id]['id']       = $row_no++;
            $tabela[$technician->id]['id_room']  = $technician->id;
            $tabela[$technician->id]['number']   = $technician->title;
            $technician['schedule']              = Simmed::simmeds_join('without_free','without_deleted','without_send')
                                                    ->where('simmed_date','=',$day)
                                                    ->where('simmeds.simmed_technician_id',$technician->id)->get();
        }

        foreach ($workdays as $workday)
        {
            $work_one=[];
            $work_one['id']=$workday->user_id;
            if ($workday->simdescript=='')
                $work_one['text']=$workday->long_name;
            else
                $work_one['text']=$workday->simdescript;
            $work_one['subtxt']='SD no name';
            $work_one['date']=$workday->date;
            $work_one['start']=substr($workday->time_begin,0,5);
            $work_one['end']=substr($workday->time_end,0,5);
            $work_one['room_number']='SX no room number';
            $work_one['status']=$workday->status;
            $work_one['class']=$workday->colour;
            $work_one['character']=$workday->long_name;
            $work_one['simdescript']=$workday->description;

            $tabela[$workday->user_id]['sim'][] = $work_one;
        }


        foreach ($simdays->get() as $simone)
        {
            $work_one=[];
    
            $work_one['id']=$simone->id;
            $work_one['text']=$simone->room_number.': '.$simone->text;
            $work_one['subtxt']=$simone->subtxt;
            $work_one['date']=$simone->simmed_date;
            $work_one['start']=$simone->start;
            $work_one['end']=$simone->end;
            $work_one['room_number']=$simone->room_number;
            $work_one['status']=$simone->simmed_status;    
            $work_one['class']=$simone->character_colour;
            $work_one['character']='symulacja: '.$simone->character_name;
            $work_one['simdescript']=$simone->student_subject_name;//.' ['.$workday->student_group_name.', '.$workday->subgroup_name.']';

            $tabela[$simone->technician_id*1]['sim'][] = $work_one;
        }

        $zwrocik='';
        $record_separator="";

        foreach ($tabela as $roomrow)
        {
            $zwrocik.=$record_separator;
            $record_separator=',';
            $zwrocik.='\''.$roomrow['id'].'\' : {';
            $zwrocik.='title : \''.$roomrow['number'].'\'';
            $zwrocik.=',';
            //$zwrocik.='class: \'example2\','; //class all row      
            $zwrocik.='subtitle : \'<i>CSMLek</i>\'';
            
            if (isset($roomrow['sim']))
            {
                $zwrocik.=','."\nschedule:[";
                $separator_shed="";
                foreach ($roomrow['sim'] as $simrow)
                {
                    $zwrocik.=$separator_shed;
                    $separator_shed=",";
                    $zwrocik.="{";
                    $zwrocik.='class: \''.$simrow['class'].'\',';
                    $zwrocik.='character: \''.$simrow['character'].'\',';
                    $zwrocik.='start: \''.$simrow['start'].'\',';
                    $zwrocik.='end: \''.$simrow['end'].'\',';
                    $zwrocik.='room_number: \''.$simrow['room_number'].'\',';
                    $zwrocik.='text: \''.$simrow['text'].'\',';
                    $zwrocik.='simdescript: \''.$simrow['simdescript'].'\',';
                    //$zwrocik.='subtxt: \''.$simrow['subtxt'].'\',';
                    //$zwrocik.='class: \''.$simrow['character'].'\',';
                    $zwrocik.="data: {";
                        $zwrocik.='id: '.$simrow['id'].",";
                        $zwrocik.='sdparam: \'z modelu WorkTime\''.",";
                        $zwrocik.='class: \''.$simrow['character'].'\'';
                    $zwrocik.='}';
                    $zwrocik.='}';
                }
                $zwrocik.="\n]";
            }
                $zwrocik.='}';
            
        }

        return $zwrocik;
    }

    public static function work_time_join($get_breake)
    {
        $ret = WorkTime::select(
            '*',
            \DB::raw('substring(time_begin,1,5) as time_begin'),
            \DB::raw('substring(time_end,1,5) as time_end'),
            'work_times.description as description',
            'work_time_types.description as type_description',
            'work_times.id as id'
        )
        ->where('work_times.status','=',1)
        ;
        //->where('user_id',$user_id)
        //->where('date','=',$date);
        switch ($get_breake)
        {
            case 'without_breake':
                $ret=$ret->where('code','<>','work_breake');
                break;
            case 'with_breake':
                //$ret=$ret->where('code','<>','work_breake');
                break;
            case 'only_breake':
                $ret=$ret->where('code','=','work_breake');
                break;
            default:
                dump('WorkTime model: error in calling function work_timne_join ');
        }
        
        $ret=$ret->leftjoin('work_time_types','work_time_types_id','=','work_time_types.id')
                 ->join('pl_days',\DB::raw('dayofweek(date)'),'=','pl_days.id');
        return $ret;
        }

    public static function calculate_work_time($user_id, $date)
    {
        $qA = WorkTime::select(
            \DB::raw('substring(time_begin,1,5) as time_start'),
            \DB::raw('substring(time_end,1,5) as time_end')
        )
        ->where('work_times.status','=',1)
        ->where('user_id',$user_id)
        ->where('date','=',$date)
        ->where('code','<>','work_breake')
        ->leftjoin('work_time_types','work_time_types_id','=','work_time_types.id')
        ;

        $all_day =
        DB::table('simmeds')
            ->select(
                \DB::raw('substring(simmed_time_begin,1,5) as time_start'),
                \DB::raw('substring(simmed_time_end,1,5) as time_end')
                )
            ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')

            ->where('simmed_date','=',$date)
            ->where('simmed_technician_id','=',$user_id)
            ->where('character_short','<>','prep')
            ->where('character_short','<>','ready')
            ->where('simmed_status','<>',4)
            ->union($qA)
            ->orderBy('time_start')
            ->get()
            ;

        $exist_work_types=[];
        foreach ($qA->addSelect('short_name')->addSelect('work_time_types_id')->get() as $row_two)
            $exist_work_types[$row_two->work_time_types_id]=$row_two->short_name;

        $time_table=[];
        $minutes=0;
        $all_times['end']=$all_times['start']='-';
        if ($all_day->count()>0)
        {
            $current['start']=$all_day->first()->time_start;
            $current['end']=$all_day->first()->time_end;
            $all_times['start']=$current['start'];

            foreach ($all_day as $row_one)
            {
     
                if ($row_one->time_start>$current['end'])
                    {
                        $time_table[]=$current;
                        $current['start']=$row_one->time_start;
                        $current['end']=$row_one->time_end;
                    }
                elseif ($current['end']<$row_one->time_end)
                    {
                        $current['end']=$row_one->time_end;
                    }
            }
            $time_table[]=$current;
            $all_times['end']=$current['end'];

            foreach ($time_table as $row_one)
                {
                $minutes+=round(abs(strtotime( $row_one['end']) - strtotime($row_one['start']) ) / 60,0);
                }
        }
        else
        {
            $current['start']='-';
            $current['end']='-';
            $current['work_types']=null;
            $time_table[]=$current;
        }
        return ['date' => $date, 'times' => $time_table, 'all_times' => $all_times, 'minutes' => $minutes, 'work_types' => $exist_work_types];
    }

}
