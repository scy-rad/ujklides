<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        $simdays=Simmed::
        select('simmeds.id',
        \DB::raw('substr(simmed_time_begin,1,5) as start'),
        \DB::raw('substr(simmed_time_end,1,5) as end'),  
        'room_number',
        \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as text'),
        'technicians.id as technician_id',
        'technicians.name as subtxt',
        'character_short',
        'student_subject_id',
        'student_subject_name'//, 'student_group_name', 'subgroup_name'
        )

        ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
        ->leftjoin('users as leaders','simmeds.simmed_leader_id','=','leaders.id')
        ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','technicians.id')
        ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
        ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
        ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
          
            

        ->where('simmed_date','=',$day)
        ->where('simmed_status','<',4)
//        ->where('simmed_technician_character_id','=',TechnicianCharacter::where('character_short','stay')->get()->first()->id)

        //->get()
        ;
        $workdays=WorkTime::select('*','work_times.description as simdescript')
        ->where('date','=',$day)
        //->where('simmed_status','<',4)
        //->where('simmed_technician_character_id','=',TechnicianCharacter::where('character_short','stay')->get()->first()->id)
        ->leftjoin('work_time_types','work_times.work_time_types_id','=','work_time_types.id')
        ->get();

        //dump($workdays);

        // $simrooms=Room::where('room_xp_code','<>','')
        //         ->orderBy('room_number')->get();
        $technicians=User::role_users('technicians', 1, 1)
        ->select('id', 'name as title', \DB::raw('"CSM tech" as subtitle'))
        ->get();
        
        $tabela=null;
        $row_no=1;
        
        foreach ($technicians as $technician)
        {
            $tabela[$technician->id]['id']       = $row_no++;
            $tabela[$technician->id]['id_room']  = $technician->id;
            $tabela[$technician->id]['number']   = $technician->title;
            $simdaysClone                        = clone $simdays; 
            $technician['schedule']              = $simdaysClone->where('simmeds.simmed_technician_id',$technician->id)->get();
            //dump($simdaysClone->where('simmeds.simmed_technician_id',$technician->id)->get());
        }

        foreach ($workdays as $workday)
        {
            $work_one=[];
            $work_one['id']=$workday->id;
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
            ;
            
        
            $tabela[$workday->user_id]['sim'][] = $work_one;
 //           dump($work_one);
        }


        foreach ($simdays->get() as $simone)
        {
            $work_one=[];
            //dump($simone->leader()->title->user_title_short);
           $work_one['id']=$simone->id;
           $work_one['text']=$simone->room_number.': '.$simone->text;
           $work_one['subtxt']=$simone->subtxt;
           $work_one['date']=$simone->simmed_date;
           $work_one['start']=$simone->start;
           $work_one['end']=$simone->end;
           $work_one['room_number']=$simone->room_number;
           $work_one['status']=$simone->simmed_status;    
           $work_one['class']=$simone->character_short;
           $work_one['character']='symulacja: '.$simone->character_name;
           $work_one['simdescript']=$simone->student_subject_name;//.' ['.$workday->student_group_name.', '.$workday->subgroup_name.']';

        
           $tabela[$simone->technician_id]['sim'][] = $work_one;
        }


//dump($tabela);

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
//        dump($zwrocik);
        // dd('stay there');

        return $zwrocik;
    }



    public static function old_activity_for_scheduler($day) 
    {
        $simdays=Simmed::
        select('simmeds.id',
        \DB::raw('substr(simmed_time_begin,1,5) as start'),
        \DB::raw('substr(simmed_time_end,1,5) as end'),  
        'room_number',
        \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as text'),
        'technicians.id as technician_id',
        'technicians.name as subtxt'
        )

        ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
        ->leftjoin('users as leaders','simmeds.simmed_leader_id','=','leaders.id')
        ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','technicians.id')
        ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
        ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
            

        ->where('simmed_date','=',$day)
        ->where('simmed_status','<',4);
//        ->where('simmed_technician_character_id','=',TechnicianCharacter::where('character_short','stay')->get()->first()->id);

        //->get();

        $workdays=WorkTime::select('date','time_begin','time_end')
        ->where('date','=',$day);
//        ->get();

        //dump($workdays);

        // $simrooms=Room::where('room_xp_code','<>','')
        //         ->orderBy('room_number')->get();
        $technicians=User::role_users('technicians', 1, 1)
        ->select('id', 'name as title', \DB::raw('"CSM tech" as subtitle'))
        ->get();

        
        $wynikJSON=null;
        $zwrocik='';
        $record_separator="";
        
        foreach ($technicians as $technician)
        {
            $tabela[$technician->id]['id']       = $technician->id;
            $tabela[$technician->id]['id_room']  = $technician->id;
            $tabela[$technician->id]['number']   = $technician->title;

            $wynikJSON.=$record_separator;
            $record_separator=',';
            $wynikJSON.='\''.$technician->id.'\' : {';
            $wynikJSON.='title : \''.$technician->title.'\'';
            $wynikJSON.=',';
            $wynikJSON.='subtitle : \'<i>CSMson</i>\'';

            $simdaysClone                        = clone $simdays;
            $workdaysClone                       = clone $workdays;
            
            $wynikJSON.=','."\nschedule:[";
            $count=1;
            foreach ($simdaysClone->where('simmeds.simmed_technician_id',$technician->id)->get() as $RowOne)
            {
                $xdata['id']=$count++;
                $xdata['sdparam']='z modelu Simmed';
                $xdata['class']='sc_bar_move';
                $RowOne->data=$xdata;
                $wynikJSON.=$RowOne->toJson();    
            }
            foreach ($workdaysClone->where('user_id',$technician->id)->get() as $RowTwo)
            {
                $xdata['id']=$count++;
                $xdata['sdparam']='z modelu Simmed';
                $xdata['class']='sc_bar_move';
                $RowTwo->data=$xdata;
                $wynikJSON.=$RowTwo->toJson();    
            }

            $wynikJSON.=']}';
        }

        return($wynikJSON);
      }

}
