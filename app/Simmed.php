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
    function name_of_changer()
    {
        if ($this->user_id>0)
            return $this->hasOne(User::class,'id','user_id')->get()->first()->name;
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
   

    // public static function simmeds_for_plane($sch_date){
    //     $data=[];
    //     return $data;
    // }
    

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

        return $ret_tab;
       
    }


    public static function simmeds_join($without_free,$without_deleted,$with_send) 
    {
        $return=Simmed::
        select('simmeds.id',
                    'simmed_date',
                        //\DB::raw('dayname(simmed_date) as DayOfWeek'),
                        'pl_days.pl_day as DayOfWeek',
                        \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'),
                        \DB::raw('TIMESTAMPDIFF(MINUTE, simmed_time_begin, simmed_time_end) as time_minutes'), 
                        \DB::raw('concat(substr(send_simmed_time_begin,1,5),"-",substr(send_simmed_time_end,1,5)) as send_time'), 
                        
        \DB::raw('substr(simmed_time_begin,1,5) as start'),
        \DB::raw('substr(simmed_time_end,1,5) as end'),  
        'room_id',
        'rooms.room_number',
        'leaders.id as leader_id',
        \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as text'),
        \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as leader'),
        'technicians.id as technician_id',
        'technicians.name as subtxt',
        'technicians.name as technician_name',
        'simmed_technician_character_id',
        'technician_characters.character_short',
        'technician_characters.character_name',
        'student_subject_id',
        'student_subject_name',
        'simmeds.student_group_id',
        'student_group_code',
        'student_group_name',
        'student_subgroup_id', 
        'subgroup_name',
        'simmed_alternative_title',
        'simmed_type_id',
        'simmed_status',
        'simmed_status2'
    )

        ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
        ->leftjoin('users as leaders','simmeds.simmed_leader_id','=','leaders.id')
        ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','technicians.id')
        ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
        ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
        ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
        ->leftjoin('student_groups','simmeds.student_group_id','=','student_groups.id')
        ->leftjoin('student_subgroups','simmeds.student_subgroup_id','=','student_subgroups.id')
        ->join('pl_days',\DB::raw('dayofweek(simmed_date)'),'=','pl_days.id');

        


        if ($with_send=="with_send")
        $return=$return->addSelect(
            'simmed_time_begin',
            'simmed_time_end',
            'send_simmed_date',
            'send_simmed_time_begin',
            'send_simmed_time_end',
            'send_simmed_type_id',
            'send_student_subject_id',
            'send_student_group_id',
            'send_student_subgroup_id',
            'send_room_id',
            'send_simmed_leader_id',
            'send_simmed_technician_id',
            'send_simmed_technician_character_id',
            'send_simmed_status',
            'send_simmed_status2',
            \DB::raw('concat(send_user_titles.user_title_short," ",send_leaders.lastname," ",send_leaders.firstname) as send_leader'),
            'send_technicians.name as send_technician_name',
            'send_technician_characters.character_name as send_character_name',
            'send_rooms.room_number as send_room_number'
            )
            ->leftjoin('rooms as send_rooms','simmeds.send_room_id','=','send_rooms.id')
            ->leftjoin('users as send_technicians','simmeds.send_simmed_technician_id','=','send_technicians.id')
            ->leftjoin('users as send_leaders','simmeds.send_simmed_leader_id','=','send_leaders.id')
            ->leftjoin('user_titles as send_user_titles','send_leaders.user_title_id','=','send_user_titles.id')
            ->leftjoin('technician_characters as send_technician_characters','simmeds.send_simmed_technician_character_id','=','send_technician_characters.id');
    

        if ($without_deleted=='without_deleted')
            $return=$return->where('simmed_status','<>',4);
        if ($without_free=='without_free')
            $return=$return->where('simmed_technician_character_id','<>',TechnicianCharacter::where('character_short','free')->get()->first()->id);

        return $return;
    }


    public static function get_technician_character_times()
    {
    return DB::table('simmeds')
    ->select(
        'simmed_technician_character_id as character_id',
        'character_short as worktime_type',
        \DB::raw('count(character_short) as worktime_count'),
        \DB::raw('sum(TIMESTAMPDIFF(MINUTE, simmed_time_begin, simmed_time_end)) as worktime_minutes')
        )
    ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
    ->where('simmed_status','<>',4)
    ->orderBy('worktime_type')
    ->groupBy('character_id')
    ->groupBy('worktime_type');
    }


}