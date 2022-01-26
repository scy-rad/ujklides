<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SimmedArc extends Model
{
    //
    public $timestamps = false;

    function name_of_changer()
    {
        if ($this->user_id>0)
            return $this->hasOne(User::class,'id','user_id')->get()->first()->name;
        else
            return '- - -';
    }

    function room_number()
    {
        return Room::find($this->room_id)->room_number;
    }
    function leader()
    {
        if ($this->simmed_leader_id>0)
            return User::find($this->simmed_leader_id)->full_name();
        else
            return '- - -';
    }
    function technician()
    {
        if ($this->simmed_technician_id>0)
            return User::find($this->simmed_technician_id)->name;
        else
            return '- - -';
    }
    function subject()
    {
        if ($this->student_subject_id>0)
            return StudentSubject::find($this->student_subject_id)->student_subject_name;
        else
            return '- - -';
    }

    function character()
    {
        return TechnicianCharacter::find($this->simmed_technician_character_id)->character_short;
    }

    function status()
    {
        //
        $cos=Simmed::status_table();
        return Simmed::status_name($this->simmed_status);
        //return TechnicianCharacter::find($this->simmed_technician_character_id)->character_short;
        return 'test';
    }

    public static function change_code_table()
    {
    $i=1;
    $tab[1]['id']       = 1;
    $tab[1]['name']     = 'nowy wpis import';
    $tab[2]['id']       = 2;
    $tab[2]['name']     = 'edycja importem';
    $tab[4]['id']       = 4;
    $tab[4]['name']     = 'usunięcie importem';
    $tab[20]['id']       = 20;
    $tab[20]['name']     = 'edycja';
    return $tab;
    }

    function change_code()
    {
    $statustab=SimmedArc::change_code_table();
    return $statustab[$this->change_code]['name'];
    }

    


    function datas()
    {
        $ret =$this->simmed_date.'; ';
        $ret.=substr($this->simmed_time_begin,0,5).'-';
        $ret.=substr($this->simmed_time_end,0,5).'; ';
        $ret.=$this->simmed_alternative_title.'; ';
        $ret.=$this->subject().'; ';
        // $ret.=$this->student_group_id.'; ';
        // $ret.=$this->student_subgroup_id.'; ';
        $ret.=$this->room_number().'; ';
        $ret.=$this->leader().'; ';
        $ret.=$this->technician().'; ';
        $ret.=$this->character().'; ';
        $ret.=$this->status().'; ';
        $ret.=$this->updated_at.'; ';
        // $ret.=$this->simmed_id.'; ';
        // $ret.=$this->user_id.'; ';
        //$ret.=$this->change_code();

        return $ret;
    }

 
}
