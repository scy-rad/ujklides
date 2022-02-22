<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\RolesHasUsers;
use App\Notifications\MyResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        // to add costom reset password mail template: 
        // https://stackoverflow.com/questions/39327954/laravel-5-3-redefine-reset-email-blade-template
        $this->notify(new MyResetPassword($token));
    }


    public function roles() {
        return $this->belongsToMany(Roles::class, 'roles_has_users', 'roles_has_users_users_id', 'roles_has_users_roles_id')->withTimestamps();//->get();
    }

    public function phones() {
        return $this->hasMany(UserPhone::class);//->get();
    }

    public function title() {
        return $this->belongsTo(UserTitle::class, 'user_title_id');//->first();
    }

    public function full_name() {
        return $this->belongsTo(UserTitle::class, 'user_title_id')->first()->user_title_short.' '.$this->lastname.' '.$this->firstname;
    }

    public static function role_users($role_code, $user_status, $center_id) {
        $center_id=$center_id.'ss';
        $role_id=Roles::select('id')->where('roles_code', $role_code)->first()->id;
        $roles_users=RolesHasUsers::select('roles_has_users_users_id')->where('roles_has_users_roles_id','=',$role_id)->get();
        $users = User::whereIn('id',$roles_users)
                    ->where('user_status',$user_status)
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->orderBy('id');
        return $users;
        }

    public static function json_role_users($role_code, $user_status, $center_id) {
        $center_id=$center_id.'ss';
        $role_id=Roles::select('id')->where('roles_code', $role_code)->first()->id;
        $roles_users=RolesHasUsers::select('roles_has_users_users_id')->where('roles_has_users_roles_id','=',$role_id)->get();
        $users = User::whereIn('id',$roles_users)
                    ->where('user_status',$user_status)->orderBy('lastname')
                    ->orderBy('firstname')
                    ->orderBy('id');

        $data=[];
        foreach ($users->get() as $rowuser)
            {
                $data[] = [
                    'id' => $rowuser->id,
                    'text' => $rowuser->full_name()
                ];
            }
        return json_encode($data);
    }

    public static function find_user($fullname)
    {
        //funkcja wukorzystywana przez kontroler ManSimmed

        $firstname='';
        $lastname='';
        $title='';

        $pozostalo_do_analizy=$fullname;

        if (strpos($pozostalo_do_analizy, ' ', 0)>0)
        {
            $firstname              =   substr($pozostalo_do_analizy,strRpos($pozostalo_do_analizy, ' ', 0)+1,100);
            $pozostalo_do_analizy   =   substr($pozostalo_do_analizy,0,strRpos($pozostalo_do_analizy, ' ', 0));
            $lastname               =   $pozostalo_do_analizy;
        }
        if (strpos($pozostalo_do_analizy, ' ', 0)>0)
        {
            $lastname               =   substr($pozostalo_do_analizy,strRpos($pozostalo_do_analizy, ' ', 0)+1,100);
            $pozostalo_do_analizy   =   substr($pozostalo_do_analizy,0,strRpos($pozostalo_do_analizy, ' ', 0));
            $title                  =   $pozostalo_do_analizy;
        }

        if (($lastname)!='')//jeśli nazwa składa się conajmniej z dwóch członów - to szukaj tej osoby w bazie danych
        {
            if (UserTitle::where('user_title_short',$title)->first()!==NULL)
            {
                $user = User::where('user_title_id',UserTitle::where('user_title_short',$title)->first()->id)
                        ->where('lastname',$lastname)
                        ->where('firstname',$firstname);
                if ($user->first()!==NULL)
                {
                    return $user->first()->id;
                }
                $user = User::where('user_title_id',UserTitle::where('user_title_short',$title)->first()->id)
                        ->where('lastname',$firstname)
                        ->where('firstname',$lastname);
                if ($user->first()!==NULL)
                {
                    return $user->first()->id;
                }
            }
            else
            {
                $user = User::where('lastname',$lastname)
                        ->where('firstname',$firstname);
                if ($user->first()!==NULL)
                {
                    return $user->first()->id;
                }
                $user = User::where('lastname',$firstname)
                        ->where('firstname',$lastname)
                        ->first();
                if ($user!==NULL)
                {
                    return $user->id;
                }
            }
        }
        return 0; //jeśli nazwa składa się tylko z jednego członu - zwróć 0
    }


    public function add_roles($role_id, $center_id) {
        $rhu = New RolesHasUsers();
        $rhu->roles_has_users_users_id = $this->id;
        $rhu->roles_has_users_roles_id = $role_id;
        $rhu->roles_has_users_center_id = $center_id;
        return $rhu->save();
    }

    public function add_phone($phone_number, $phone_type, $phone_for) {
        $rhu = New UserPhone();
        $rhu->user_id = $this->id;
        $rhu->phone_number = $phone_number;
        $rhu->user_phone_type_id = $phone_type;
        $rhu->phone_for_coordinators = $phone_for[0];
        $rhu->phone_for_technicians = $phone_for[1];
        $rhu->phone_for_trainers = $phone_for[2];
        $rhu->phone_for_guests = $phone_for[3];
        $rhu->phone_for_anonymouse = $phone_for[4];
        return $rhu->save();
    }

    public function update_mail($email) {
        $this->email=$email;
        return $this->save();
    }

    public function update_title($title_id) {
        $this->user_title_id=$title_id;
        return $this->save();
    }

    public function update_status($user_status) {
        $this->user_status=$user_status;
        return $this->save();
    }

    public function update_about($user_about) {
        $this->about=$user_about;
        return $this->save();
    }


    public function update_avatar($request)
    {
        $request->validate([
            'fotka' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $avatarName = $this->id.'_avatar'.time().'.'.request()->fotka->getClientOriginalExtension();
        $request->fotka->storeAs('avatars',$avatarName);
        $this->user_fotka = $avatarName;
        return $this->save();
    }


    public function remove_roles($role_id, $center_id) {
        return RolesHasUsers::where('roles_has_users_users_id', '=', $this->id)
        ->where('roles_has_users_roles_id','=',$role_id)
        ->where('roles_has_users_center_id','=',$center_id)
        ->delete();
    }


    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('roles_name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function CenterRole($role,$center)
    {
        $role_id=Roles::select('id')->where('roles_name', $role)->first();
        $center_id=Center::select('id')->where('center_short', $center)->first();
        if (RolesHasUsers::select('*')->where('roles_has_users_users_id',$this->id)->where('roles_has_users_roles_id',$role_id['id'])->where('roles_has_users_center_id', $center_id['id'])->first())
            {
            return true;
            }
        else
            {
            return false;
            }
    }

    public function CheckUserCenterRole($user,$role,$center)
    {
        $role_id=Roles::select('id')->where('roles_name', $role)->first();
        $center_id=Center::select('id')->where('center_short', $center)->first();
        if (RolesHasUsers::select('*')->where('roles_has_users_users_id',$user)->where('roles_has_users_roles_id',$role_id['id'])->where('roles_has_users_center_id', $center_id['id'])->first())
            {
            return true;
            }
        else
            {
            return false;
            }
    }

}