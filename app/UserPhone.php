<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserPhone extends Model
{
    public function type() {
        return $this->hasOne(UserPhoneType::class,'id','user_phone_type_id');//->get()->first();
    }

    public function update_phone($phone_type,$phone_number,$phone_for) {
        //dd($this);
        $this->user_phone_type_id=$phone_type;
        $this->phone_number=$phone_number;
        $this->phone_for_coordinators=$phone_for[0];
        $this->phone_for_technicians=$phone_for[1];
        $this->phone_for_trainers=$phone_for[2];
        $this->phone_for_guests=$phone_for[3];
        $this->phone_for_anonymouse=$phone_for[4];
        return $this->save();
    }
    public function remove_phone() {
        return $this->delete();
    }
    public function phone_for_me($return_type) {
        if (   (Auth::user()->hasRole('Technik') && ($this->phone_for_technicians==1) )
            || (Auth::user()->hasRole('Technik') && ($this->phone_for_coordinators==1) )
            || (Auth::user()->hasRole('Technik') && ($this->phone_for_trainers==1) )
            || ($this->phone_for_guest==1)
            || ($this->phone_for_anonymouse==1)
            )
            switch ($return_type)
                {
                case 'html':
                    return '<span class="glyphicon '.$this->type->user_phone_type_glyphicon.'"></span> <a href="tel:'.$this->phone_number.'">'.$this->phone_number.'</a>';
                    break;
                case 'html5':
                    return '<a href="tel:'.$this->phone_number.'"><span class="glyphicon '.$this->type->user_phone_type_glyphicon.'"  style="font-size: 5rem;"></span><br>'.$this->phone_number.'</a>';
                    break;
                case 'plain':
                    return $this->phone_number;
                    break;
                } 
    }
    
}
