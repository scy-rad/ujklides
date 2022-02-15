<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Simmed extends Model
{

    public static function status_table()
        {
        $i=1;
        $tab[1]['id']       = 1;
        $tab[1]['name']     = 'zaimportowane';
        $tab[4]['id']       = 4;
        $tab[4]['name']     = 'odwołane';
        $tab[5]['id']       = 5;
        $tab[5]['name']     = 'dopisane'; //(nie usuwane podczas importu)
        return $tab;
        }

    public static function status_name($ids)
        {
        $statustab=Simmed::status_table();
        return $statustab[$ids]['name'];
        }

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
    function login_of_technician()
    {
    if ($this->simmed_technician_id>0)
        return $this->hasOne(User::class,'id','simmed_technician_id')->get()->first()->name;
    else
        return '- - -';
    }
    
    function name_of_student_group()
    {
    if ($this->student_group_id>0)
        return $this->hasOne(StudentGroup::class,'id','student_group_id')->get()->first()->student_group_name;
    }

    function code_of_student_group()
    {
    if ($this->student_subgroup_id>0)
        return $this->hasOne(StudentGroup::class,'id','student_group_id')->get()->first()->student_group_code.'-'.$this->hasOne(StudentSubgroup::class,'id','student_subgroup_id')->get()->first()->subgroup_name;
    elseif ($this->student_group_id>0)
        return $this->hasOne(StudentGroup::class,'id','student_group_id')->get()->first()->student_group_code;
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

    function technician_character()
    {
        return $this->hasOne(TechnicianCharacter::class,'id','simmed_technician_character_id')->get()->first();
    }

    public function scenarios() {
       // return $this->hasMany(Scenario::class);//->get();
        return $this->belongsToMany(Scenario::class, 'scenario_for_simmeds', 'simmed_id', 'scenario_id')->withTimestamps();
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
