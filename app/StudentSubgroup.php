<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSubgroup extends Model
{
    //
    public static function find_subgroup($student_group_id,$subgroup_name) {
        $group = StudentSubgroup::where('student_group_id',$student_group_id)
                                ->where('subgroup_name',$subgroup_name);
        if ($group->first()!==NULL)
            return $group->first()->id;
        return 0;
        }
}
