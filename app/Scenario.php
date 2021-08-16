<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Scenario extends Model
{
    function author()
    {
        return $this->hasOne(User::class,'id','scenario_author_id')->get()->first();
    }

    public function simmeds() {
        // return $this->hasMany(Scenario::class);//->get();
         return $this->belongsToMany(Simmed::class, 'scenario_for_simmeds', 'scenario_id', 'simmed_id')->withTimestamps();
     }

     public function subjects() {
        // return $this->hasMany(Scenario::class);//->get();
         return $this->belongsToMany(StudentSubject::class, 'scenario_for_subjects', 'scenario_id', 'student_subject_id')->withTimestamps();
     }
     
     public function pliks() {
        return $this->belongsToMany(Plik::class, 'plik_for_scenarios', 'scenario_id', 'plik_id')->withTimestamps();//->get();
    }

}
