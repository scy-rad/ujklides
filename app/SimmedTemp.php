<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class SimmedTemp extends Model
{

    /*
    tmp_status:
        0   -   nowy import
        1   -   dodaj wiersz do bazy
        2   -   aktualizuj wpis
        3   -   usuń wiersz z bazy
        4   -   pomiń wpis
    */

    public static function status_name(Int $int)
        {
        //SimmedTemp::status_name()
        switch ($int){
            case (0):
                return '??';
            case (1):
                return 'dodaj';
            case (2):
                return 'aktual.';
            case (3):
                return 'usuń';
            case (4):
                return 'pomiń';
            case (11):
                return 'nie dodawaj';
            case (12):
                return 'jako nowe';
            case (13):
                return 'nie usuwaj';
            case (9):
                return 'przywróć';
            }
        }

    public static function add_row($simmed_row)
    {
        $table=new SimmedTemp;
        $table->import_number                   = SimmedTemp::max('import_number');
        $table->import_row                      = 'No import - row for delete';
        $table->simmed_id                       = $simmed_row->id;
        $table->simmed_tmp_id                   = $simmed_row->id;
        $table->simmed_date                     = $simmed_row->simmed_date;
        $table->simmed_time_begin               = $simmed_row->simmed_time_begin;
        $table->simmed_time_end                 = $simmed_row->simmed_time_end;
        $table->simmed_type_id                  = $simmed_row->simmed_type_id;
        $table->simmed_alternative_title        = $simmed_row->simmed_alternative_title;

        $table->student_subject_id              = $simmed_row->student_subject_id;
        $table->student_group_id                = $simmed_row->student_group_id;
        $table->student_subgroup_id             = $simmed_row->student_subgroup_id;
        $table->room_id                         = $simmed_row->room_id;
        $table->simmed_leader_id                = $simmed_row->simmed_leader_id;
        $table->simmed_technician_id            = $simmed_row->simmed_technician_id;
        $table->simmed_technician_character_id  = $simmed_row->simmed_technician_character_id;

        if ($table->student_subject_id>0) 
            $table->student_subject_txt = $simmed_row->student_subject()->student_subject_name;
        if ($table->student_group_id>0)
            $table->student_group_txt = $simmed_row->student_group()->student_group_name;
        if ($table->student_subgroup_id>0)
            $table->student_subgroup_txt = $simmed_row->student_subgroup()->subgroup_name;
        $table->room_xp_txt = $simmed_row->room()->room_xp_code;
        $table->room_xls_txt = $simmed_row->room()->room_number;
        if ($table->simmed_leader_id>0)
            $table->simmed_leader_txt = $simmed_row->leader()->full_name();

        // $table->simmed_merge
        $table->tmp_status                      = $simmed_row->tmp_status;
        return $table->save();
    }


    function leader()
    {
        return $this->hasOne(User::class,'id','simmed_leader_id')->get()->first();
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
    
    function room()
    {
        return $this->hasOne(Room::class,'id','room_id')->get()->first();
    }
    
    
    function check_similar($date_between,$with)
    {   
        $with='xyz,'.$with;
        $doit=false;    // żeby w ogóle szukać podobnych wpisów, musi być podany lider, temat albo grupa (przynajmniej jeden z nich)
        $check=Simmed::select('*')
        ->whereNotIn("id" , DB::table('simmed_temps')->pluck('simmed_id'))
        ->whereBetween('simmed_date',$date_between)
        ;
        if ($with=='room,date,time')
            $doit=true;                 //a jak sprawdzamy zmianę zajęć w tym samym miejscu - to już może być puste :)                 

        if (strpos($with,'date')>0)
            $check=$check->where("simmed_date" , $this->simmed_date);
        
        if (strpos($with,'time')>0)
            $check=$check->where("simmed_time_begin" , $this->simmed_time_begin)
                         ->where("simmed_time_end" , $this->simmed_time_end);

        if (strpos($with,'leader')>0)
            {
            $check=$check->where("simmed_leader_id" , $this->simmed_leader_id);
            if ($this->simmed_leader_id>0)
                $doit=true;
            }
        
        if (strpos($with,'subject')>0)
            {
            $check=$check->where("student_subject_id" , $this->student_subject_id);
            if ($this->student_subject_id>0)
                $doit=true;
            }

        if (strpos($with,'group')>0)
            {
            $check=$check->where("student_group_id" , $this->student_group_id)
                         ->where("student_subgroup_id" , $this->student_subgroup_id);
            if ($this->student_group_id>0)
                $doit=true;
            }
        
        if (strpos($with,'room')>0)
            $check=$check->where("room_id" , $this->room_id);

        if (strpos($with,'deleted')>0)
            $check=$check->where("simmed_status" , 4);
        else
            $check=$check->where("simmed_status" , '<>', 4);
                
            //dump($with,$check->toSql());
            $check=$check->get();
                
        if (($check->count()>0) && $doit)
            {
            if ($check->first()->simmed_date == $this->simmed_date)
                {
                $this->simmed_technician_id=$check->first()->simmed_technician_id;
                $this->simmed_technician_character_id=$check->first()->simmed_technician_character_id;
                }
            //$this->simmed_alternative_title='['.$this->room_id.'-'.$check->first()->room_id.'] '.$with.': '.$this->simmed_alternative_title;
            $this->simmed_merge=$this->id;
            $this->simmed_id=$check->first()->id;
                      
            if (strpos($with,'deleted')>0)
                $this->tmp_status=9;    //reactivate inactive
            else
                $this->tmp_status=2;    //modify exist
            $this->save();
            //dump('szukam: '.$with,': '.$this->tmp_status.' leader:'.$this->simmed_leader_txt.', subject:'.$this->student_subject_txt.', group:'.$this->student_group_txt.', sala:'.$this->room_id);
            }
    }
    
    function check_similar_date_time_leader_subject_group()
    {       

        $check=Simmed::select('*')
        ->whereNotIn("id" , DB::table('simmed_temps')->pluck('simmed_id'))
        ->where("simmed_date" , $this->simmed_date)
        ->where("simmed_time_begin" , $this->simmed_time_begin)
        ->where("simmed_time_end" , $this->simmed_time_end)
        ->where("simmed_leader_id" , $this->simmed_leader_id)
        ->where("student_subject_id" , $this->student_subject_id)
        ->where("student_group_id" , $this->student_group_id)
        ->where("student_subgroup_id" , $this->student_subgroup_id)
        ->get();
                
        if ($check->count()>0)
            {
            $this->simmed_merge=$this->id;
            $this->simmed_id=$check->first()->id;
            $this->tmp_status=2;    //modify exist
            $this->save();
            //dump('SimmedTemp check_similar: znaleziono podobny wpis: '.$with.'...');
            }
    }

}
