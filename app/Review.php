<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function template() {
        return $this->hasOne(ReviewTemplate::class,'id','review_template_id');//->get()->first();
    }

    public function reviewer() {
        return $this->hasOne(User::class,'id','reviewer_id')->get()->first();
    }

    public function status() {
        switch ($this->rev_status){
            case '1':   return 'do zaplanowania';
            case '2':   return 'zaplanowany';
            case '100': return 'zrealizowany';
        }
    }
}
