<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

//use App\RolesHasUsers;

class Fault extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [
    //    'name', 'email', 'password',
    //];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [
    //    'password', 'remember_token',
    //];


    public function notifier() {
        return $this->hasOne(User::class,'id','notifier_id')->get()->first();
    }




}