<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    //


    public static function find_subject($student_subject_name) {
        $subject = StudentSubject::where('student_subject_name',$student_subject_name);
        if ($subject->first()!==NULL)
            return $subject->first()->id;
        return 0;
        }
}
