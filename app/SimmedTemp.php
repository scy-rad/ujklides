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
            case (9):
                return 'przywróć';
            }
        }

    public static function add_simmed_tmp(array $simmed_table_BIG)
    {
        //if (count($simmed_table_BIG)>0)
        foreach ($simmed_table_BIG as $simmed_table)
        {
        $table=new SimmedTemp;
            $table->import_row          =   $simmed_table['import_row'];
            $table->simmed_id           =   $simmed_table['id'];
            $table->simmed_date         =   $simmed_table['simmed_date'];
            $table->simmed_time_begin   =   $simmed_table['simmed_time_begin'];
			$table->simmed_time_end     =   $simmed_table['simmed_time_end'];
            $table->room_id             =   $simmed_table['room_id'];
            if ($simmed_table['student_subject_id']>0)
			    $table->student_subject_id  =   $simmed_table['student_subject_id'];
            if ($simmed_table['student_group_id']>0)
                $table->student_group_id    =   $simmed_table['student_group_id'];
            if ($simmed_table['student_subgroup_id']>0)
                $table->student_subgroup_id =   $simmed_table['student_subgroup_id'];
            if ($simmed_table['simmed_leader_id']>0)
                $table->simmed_leader_id    =   $simmed_table['simmed_leader_id'];
            $table->simmed_alternative_title=   $simmed_table['simmed_alternative_title'];
            $table->tmp_status          =   0;//$simmed_table['tmp_status'];
            $ret=$table->save();
        }
    }
    public static function check_simmed_tmp_add()
    {
        SimmedTemp::where('tmp_status', '=', '0')->where('simmed_id', '=', '0')->update(['tmp_status' => 1]);
    }

    public static function check_simmed_tmp_remove()
    {
        SimmedTemp::where('tmp_status', '=', '0')->where('simmed_id', '>', '0')->update(['tmp_status' => 3]);
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
    
    
    function check_similar($with)
    {       
        
        $check=SimmedTemp::select('*')
        ->where("simmed_id" , 0)
        ->where("simmed_merge" , 0)
        ->where("tmp_status" , 0)
        ;

        if (strpos($with,'date')>0)
            $check=$check->where("simmed_date" , $this->simmed_date);
        
        if (strpos($with,'time')>0)
            $check=$check->where("simmed_time_begin" , $this->simmed_time_begin)
                         ->where("simmed_time_end" , $this->simmed_time_end);


        if (strpos($with,'leader')>0)
            $check=$check->where("simmed_leader_id" , $this->simmed_leader_id);
        
        if (strpos($with,'subject')>0)
            $check=$check->where("student_subject_id" , $this->student_subject_id);

        if (strpos($with,'group')>0)
            $check=$check->where("student_group_id" , $this->student_group_id)
                         ->where("student_subgroup_id" , $this->student_subgroup_id);
        
        if (strpos($with,'room')>0)
            $check=$check->where("room_id" , $this->room_id);
            

            $check=$check->get();

        if ($check->count()>0)
            {
            $check->first()->simmed_merge=$this->id;
            $check->first()->tmp_status=2;
            $check->first()->save();

            $this->simmed_merge=$this->id;
            $this->tmp_status=2;
            $this->save();

            dump('znaleziono podobny wpis: '.$with.'...');
            }
    } 


    function check_deleted($with)
    {               
        $check=Simmed::select('*')
        ->where("simmed_status" , 4);

        if (strpos($with,'leader')>0)
            $check=$check->where("simmed_leader_id" , $this->simmed_leader_id);
        
        if (strpos($with,'subject')>0)
            $check=$check->where("student_subject_id" , $this->student_subject_id);

        if (strpos($with,'group')>0)
            $check=$check->where("student_group_id" , $this->student_group_id)
                         ->where("student_subgroup_id" , $this->student_subgroup_id);
        
        if (strpos($with,'room')>0)
            $check=$check->where("room_id" , $this->room_id);
            

            $check=$check->get();

        if ($check->count()>0)
            {

            $this->simmed_merge = $check->first()->id;
            $this->tmp_status=9;
            $this->save();

            dump('model SimmedTemp znaleziono usunięty wpis: '.$with.'...');
            } 
    } 



}
