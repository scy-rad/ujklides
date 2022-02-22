<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    public static function find_group($student_group_name)
    {
        $group = StudentGroup::where('student_group_name',$student_group_name);
        if ($group->first()!==NULL)
            return $group->first()->id;

        $group = StudentGroup::where('student_group_code',$student_group_name);
        if ($group->first()!==NULL)
            return $group->first()->id;

        return 0;
    }

    function name_of_direction()
    {
        if ($this->center_id>0)
            return $this->hasOne(Center::class,'id','center_id')->get()->first()->center_direct;
        else 
            return '??';
    }
}
