<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewTemplate extends Model
{
    public function next_start() {
        return 24;  //liczy za ile ma się odbyć kolejny przegląd. Przy założeniu, że każdy miesięczny przegląd jest co 31 dni, i może się odbyć 7 dni wcześniej - wychodzi 24
    }
    public function is_userable() {
        if ($this->review_type<50) //jesli typ jest poniżej 50, to zwróć TRUE - czyli jest to przegląd użytkownika.
            return TRUE;
        else
            return FALSE;
    }
    public function typ() {
        switch ($this->review_type){
            case '2':   return 'miesięczny';
            case '3':   return 'półroczny';
            case '4':   return 'roczny';
            case '51':  return 'producenta';
            default:    return 'inny';
        }
    }

}
