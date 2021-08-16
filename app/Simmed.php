<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Simmed extends Model
{
    function leader()
    {
        return $this->hasOne(User::class,'id','simmed_leader_id')->get()->first();
    }
    
    function technician()
    {
        return $this->hasOne(User::class,'id','simmed_technician_id')->get()->first();
    }
    
    function student_group()
    {
        return $this->hasOne(StudentGroup::class,'id','student_group_id')->get()->first();
    }

    function student_subgroup()
    {
        return $this->hasOne(StudentSubgroup::class,'id','student_subgroup_id')->get()->first();
    }
    
    function student_subject()
    {
        return $this->hasOne(StudentSubject::class,'id','student_subject_id')->get()->first();
    }

    function name_of_leader()
    {
    if ($this->simmed_leader_id>0)
        return $this->hasOne(User::class,'id','simmed_leader_id')->get()->first()->full_name();
    }
    
    function name_of_technician()
    {
    if ($this->simmed_technician_id>0)
        return $this->hasOne(User::class,'id','simmed_technician_id')->get()->first()->full_name();
    else
        return '- - -';
    }
    
    function name_of_student_group()
    {
    if ($this->student_group_id>0)
        return $this->hasOne(StudentGroup::class,'id','student_group_id')->get()->first()->student_group_name;
    }

    function name_of_student_subgroup()
    {
    if ($this->student_subgroup_id>0)
        return $this->hasOne(StudentSubgroup::class,'id','student_subgroup_id')->get()->first()->subgroup_name;
    }
    
    function name_of_student_subject()
    {
    if ($this->student_subject_id>0)
        return $this->hasOne(StudentSubject::class,'id','student_subject_id')->get()->first()->student_subject_name;
    }
    
    function room()
    {
        return $this->hasOne(Room::class,'id','room_id')->get()->first();
    }

    public function scenarios() {
       // return $this->hasMany(Scenario::class);//->get();
        return $this->belongsToMany(Scenario::class, 'scenario_for_simmeds', 'simmed_id', 'scenario_id')->withTimestamps();
    }
   

    public static function simmeds_for_scheduler($day) {
        $simday=Simmed::where('simmed_date','=',$day)->where('simmed_status','<',4)->get();
//        $simrooms=Room::where('room_type_id','=',11) // 11 - sala ćwiczeniowa CSM Pielęgniarstwo
//                ->orWhere('room_type_id','=',21) // 11 - sala ćwiczeniowa CSM Pielęgniarstwo + Lekarski
//                ->orderBy('room_number')->get();
        $simrooms=Room::where('room_xp_code','<>','')
                ->orderBy('room_number')->get();
        $Par=Param::first();

        $tabela=null;
        $row_no=1;
        foreach ($simrooms as $simroom)
            {
            $tabela[$simroom->id]['id']=$row_no++;
            $tabela[$simroom->id]['id_room']=$simroom->id;
            $tabela[$simroom->id]['number']=$simroom->room_number;
            }
            
        foreach ($simday as $simone)
            {
            //dump($simone->leader()->title->user_title_short);
            $tabela[$simone->room_id]['sim'][$simone->id]['id']=$simone->id;
            $tabela[$simone->room_id]['sim'][$simone->id]['leader']=$simone->simmed_leader_id;
            $tabela[$simone->room_id]['sim'][$simone->id]['leader_name']=$simone->name_of_leader();
            $tabela[$simone->room_id]['sim'][$simone->id]['technician']=$simone->simmed_technician_id;
            $tabela[$simone->room_id]['sim'][$simone->id]['technician_name']=$simone->name_of_technician();
            //if ($simone->simmed_technician_id!=null) 
            //    $tabela[$simone->room_id]['sim'][$simone->id]['technician_name']=$simone->technician()->lastname.' '.$simone->technician()->firstname.', '.$simone->technician()->title->user_title_short;
            //else
            //    $tabela[$simone->room_id]['sim'][$simone->id]['technician_name']='?BRAK?';
            $tabela[$simone->room_id]['sim'][$simone->id]['date']=$simone->simmed_date;
            $tabela[$simone->room_id]['sim'][$simone->id]['begin']=substr($simone->simmed_time_begin,0,5);
            $tabela[$simone->room_id]['sim'][$simone->id]['end']=substr($simone->simmed_time_end,0,5);
            $tabela[$simone->room_id]['sim'][$simone->id]['date_sent']=$simone->simmed_date_sent;
            $tabela[$simone->room_id]['sim'][$simone->id]['begin_sent']=substr($simone->simmed_time_begin_sent,0,5);
            $tabela[$simone->room_id]['sim'][$simone->id]['end_sent']=substr($simone->simmed_time_end_sent,0,5);
            
            $tabela[$simone->room_id]['sim'][$simone->id]['status']=$simone->simmed_status;
            
            }
            
        $zwrocik='';
        $separator_room="";
        foreach ($tabela as $roomrow)
            {
            $zwrocik.=$separator_room;
            $separator_room=','."\n";
            $zwrocik.='\''.$roomrow['id'].'\' : {'."\n";
            $zwrocik.='title : \''.$roomrow['number'].'\'';
            $zwrocik.=','."\n";
            $zwrocik.='subtitle : \'<i>CSMLek</i>\'';
            
            if (isset($roomrow['sim']))
                {
                $zwrocik.=','."\nschedule:[";
                $separator_shed="\n";
                foreach ($roomrow['sim'] as $simrow)
                    {
                    $zwrocik.=$separator_shed;
                    $separator_shed=",\n";
                    $zwrocik.="{\n";
                    $zwrocik.='start: \''.$simrow['begin'].'\','."\n";
                    $zwrocik.='end: \''.$simrow['end'].'\','."\n";
                    $zwrocik.='text: \''.$simrow['leader_name'].'\','."\n";
                    $zwrocik.='subtxt: \''.$simrow['technician_name'].'\','."\n";
                    $zwrocik.='data: {'."\n";
                    $zwrocik.='  id: '.$simrow['id'].",\n";
                    $zwrocik.='  sdparam: \'z modelu Simmed\''.",\n";
                    $zwrocik.='start_sent: \''.$simrow['begin_sent'].'\','."\n";
                    $zwrocik.='end_sent: \''.$simrow['end_sent'].'\','."\n";
                    if (!(    ($simrow['begin']==$simrow['begin_sent']) 
                        &&  ($simrow['end']==$simrow['end_sent'])
                        ))
                       $zwrocik.='  class: \'sc_bar_move\''."\n";
                    /*
                    if ($simrow['leader']==$Par->leader_for_simmed)
                        $zwrocik.='  class: \'sc_bar_no_leader\''."\n";
                    elseif ($simrow['technician']==$Par->technician_for_simmed)
                        $zwrocik.='  class: \'sc_bar_no_technician\''."\n";
                    else
                        $zwrocik.='  class: \'sc_bar_team\''."\n";
                    */
                    //$zwrocik.='  class2: \'nic\''."\n";
                    $zwrocik.='}'."\n";
                    $zwrocik.='}';
                    }
                $zwrocik.="\n]\n";
                }
                $zwrocik.='}';
            
            }


    return $zwrocik;
    }


    public static function simmeds_for_plane($sch_date){
/*
        function validateDate($date, $format = 'Y-m-d H:i:s')
                {
                    $now = new \DateTime();
                    $d = $now::createFromFormat($format, $date);
                    return $d && $d->format($format) == $date;
                }
        if (!(validateDate($sch_date, 'Y-m-d')))
            $sch_date=date('Y-m-d'); 

        $before=date('N', strtotime($sch_date))-1;
        $start_date=date('Y-m-d', strtotime($sch_date. ' - '.$before.' day'));
        $end_date=date('Y-m-d', strtotime($sch_date. ' + 35 day'));
        

        $simday = Simmed::where('simmed_date','>=',$start_date)
                        ->where('simmed_date','<=',$end_date)
                        ->orderBy('simmed_date')
                        ->orderBy('simmed_time_begin')
                        ->get();
*/                        
        $data=[];
  /*      foreach ($simday as $simrow)
            {
                $data[] = [
                    'id' => $simrow->id,
                    'simmed_date' => $simrow->simmed_date,
                    'simmed_time_begin' => $simrow->simmed_time_begin,
                    'simmed_time_end' => $simrow->simmed_time_end,
                    'room_id' => $simrow->room_id,
                    'room_number' => $simrow->room()->room_number.' '.$simrow->room()->room_name,
                    'simmed_leader_id' => $simrow->simmed_leader_id,
                    'leader_name' => $simrow->name_of_leader(),
                    'simmed_technician_id' => $simrow->simmed_technician_id,
                    'technician_name' => $simrow->name_of_technician(),
                    'student_subject_id' => $simrow->student_subject_id,
                    'subject' => $simrow->name_of_student_subject(),
                    'group' => $simrow->name_of_student_group(),
                    'simmed_status' => $simrow->simmed_status,
                    'simmed_status2' => $simrow->simmed_status2
                ];
            }
*/
        //return json_encode($data);
        return $data;

    }
    

    public static function simmeds_for_timetable($what_type,$what_no,$date_from,$date_to) {
        $simday = Simmed::where('simmed_date','>=',$date_from)
                        ->where('simmed_date','<=',$date_to)
                        ->where('simmed_status','<',4)
                        ->orderBy('simmed_date')
                        ->orderBy('simmed_time_begin');
        switch ($what_type)
            {
            case 'room':
                $simday = $simday->where('room_id','=',$what_no)->get();
                break;
            case 'instructors':
                $simday = $simday->where('simmed_leader_id','=',$what_no)->get();
                break;
            case 'technicians':
                $simday = $simday->where('simmed_technician_id','=',$what_no)->get();
                break;
            case 'all':
                $simday = $simday->get();
                break;
            }
        $ret_tab='[';
        $ret_coma='';
        foreach ($simday as $simone)
            {
                $ret_tab.=$ret_coma."\n";
                $ret_tab.="{\n";
                $ret_tab.='"start": "'.$simone['simmed_date'].'T'.$simone['simmed_time_begin'].'",'."\n";
                $ret_tab.='"end": "'.$simone['simmed_date'].'T'.$simone['simmed_time_end'].'",'."\n";
                $ret_tab.='"id": "'.$simone['id'].'",'."\n";
                $ret_tab.='"text": "'.$simone->name_of_leader().'<br>'.$simone->name_of_technician().'<br>'.$simone->room()->room_number.'",'."\n";
                $ret_tab.='"leader": "'.$simone->name_of_leader().'",'."\n";
                $ret_tab.='"technician": "'.$simone->name_of_technician().'",'."\n";
                $ret_tab.='"room_no": "'.$simone->room()->room_number.'",'."\n";
                $ret_tab.='"subject": "'.$simone->name_of_student_subject().'"'."\n";
                $ret_tab.='}';
                $ret_coma=',';
            }
        $ret_tab.="\n]";
                        
        //dump($ret_tab);
        return $ret_tab;
/*
        class Event {}
$events = array();


foreach($result as $row) {
  $e = new Event();
  $e->id = $row['id'];
  $e->text = $row['name'];
  $e->start = $row['start'];
  $e->end = $row['end'];
  $events[] = $e;
}

echo json_encode($events);


*/
       
    }
/*
    [
        {
          "start": "2021-02-21T10:30:00",
          "end": "2021-02-21T13:30:00",
          "id": "225eb40f-5f78-b53b-0447-a885c8e92233",
          "text": "Calendar Event 1"
        },
        {
          "start": "2021-02-22T12:30:00",
          "end": "2021-02-22T15:00:00",
          "id": "1f67def5-e1dd-57fc-2d39-eb7a5f8e789a",
          "text": "Calendar Event 2"
        },
        {
          "start": "2021-02-23T10:30:00",
          "end": "2021-02-23T16:00:00",
          "id": "aba78fd9-09d0-642e-612d-0e7e002c29f5",
          "text": "Calendar Event 3"
        }
      ]
   */






}
