<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    
}
