<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Roles;
use App\RolesHasUsers;
use App\UserPhone;
use App\Libraries;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function users(String $type)
    {

    if ($type=='everybody')
        {
            $users = User::orderBy('lastname', 'ASC');
            if (!(Auth::user()->hasRole('Administrator')))
                $users = $users->where('user_status','=',1);
            //$users = $users->paginate(6);
        }
    else
        {
        $role_id=Roles::select('id')->where('roles_code', $type)->first()->id;
        $roles_users=RolesHasUsers::select('roles_has_users_users_id')->where('roles_has_users_roles_id','=',$role_id)->get();
        $users = User::whereIn('id',$roles_users);
        if (!(Auth::user()->hasRole('Administrator')))
            $users = $users->where('user_status','=',1);
        $users = $users->orderBy('lastname', 'ASC');
        //$users = $users->->paginate(6);
        }
        $users=$users->get();
        return view('users/users',compact('users'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('users/userprofile',compact('user',$user));
    }

    public function userprofile(Int $id_user)
    {
    //if (!Auth::user()->hasRole('Operator Kadr'))
    //    return view('error',['head'=>'błąd wywołania funkcji create kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);
        $user = User::where('id',$id_user)->first();
        return view('users/userprofile',compact('user',$user));

    }

    public function add_role(Request $request)
    {
    if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator'))
        return view('error',['head'=>'błąd wywołania funkcji add_role kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

        $user = User::where('id',$request->user_id)->first();
        $user->add_roles($request->roles_id, 1);

        return back()
            ->with('success','Dodanie roli powiodło się.');
    }

    public function remove_role(Request $request)
    {
    if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator'))
        return view('error',['head'=>'błąd wywołania funkcji remove_role kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

        $user = User::where('id',$request->user_id)->first();
        if ($user->remove_roles($request->roles_id, 1))
            return back()->with('success','Usuwanie roli powiodło się.');
        else
            return back()->withErrors(['name.required', 'Usuwanie roli nie powiodło się']);
    }

    public function change_email(Request $request)
    {
    if (!(Auth::user()->hasRole('Operator Kadr') || Auth::user()->hasRole('Administrator') || (User::find($request->user_id)->id==Auth::user()->id) ))
        return view('error',['head'=>'błąd wywołania funkcji change_email kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

            $user = User::where('id',$request->user_id)->first();
            if ($user->update_mail($request->email))
                return back()->with('success','Edycja maila powiodła się.');
            else
                return back()->withErrors('zmiana Maila niestety nie powiodła się...');
    }

    public function change_phone(Request $request)
    {
    if (!(Auth::user()->hasRole('Operator Kadr') || Auth::user()->hasRole('Administrator') || (User::find($request->user_id)->id==Auth::user()->id) ))
        return view('error',['head'=>'błąd wywołania funkcji change_phone kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

        function onof($what){
                if (($what=="on") || ($what=="1")) return 1;
                    else return 0;
            }
        if ($request->id_phone==0)
            {
            $user = User::where('id',$request->user_id)->first();
            $ret=$user->add_phone($request->phone_number,$request->user_phone_type_id,[onof($request->phone_for_coordinators), onof($request->phone_for_technicians), onof($request->phone_for_trainers), onof($request->phone_for_guests), onof($request->phone_for_anonymouse)]);
            $comment='Dodawanie';
            }
        else
            {
            $UserPhone = UserPhone::where('id',$request->id_phone)->first();

            if ($request->phone_number=='')
                {
                $ret=$UserPhone->remove_phone();
                $comment='Usuwanie';
                }
            else
                {
                $ret=$UserPhone->update_phone($request->user_phone_type_id,$request->phone_number,[onof($request->phone_for_coordinators), onof($request->phone_for_technicians), onof($request->phone_for_trainers), onof($request->phone_for_guests), onof($request->phone_for_anonymouse)]);
                $comment='Edytowanie';
                }
            }

            if ($ret)
                return back()->with('success',$comment.' numeru telefonu zakończone sukcesem.');
            else
                return back()->withErrors($comment.' numeru telefonu zakończone niepowodzeniem...');
    }

    public function change_password(Request $request)
    {
    if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator'))
        return view('error',['head'=>'błąd wywołania funkcji change_password kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr lub Administratorem']);

            $user = User::where('id',$request->user_id)->first();
            
            if ($request->password==$request->passwordre)
                if (strlen($request->password)>=8)
                    {
                    $user->password = bcrypt($request->password);
                    if ($user->save())
                        return back()->with('success','Zmiana hasła powiodła się.');
                    else
                        return back()->withErrors('Zmiana hasła niestety nie powiodła się...');
                    }
                else
                    return back()->withErrors('Hasło jest zbyt krókie (minimum 8 znaków)...');
            else
                return back()->withErrors('Hasła różnią się od siebie...');
        }


    public function change_status(Request $request)
    {
    if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator'))
        return view('error',['head'=>'błąd wywołania funkcji change_status kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

            $user = User::where('id',$request->user_id)->first();
            if ($user->update_status($request->user_status))
                return back()->with('success','Zmiana statusu powiodła się.');
            else
                return back()->withErrors('Zmiana statusu niestety nie powiodła się...');
        }

    public function change_about(Request $request)
    {
    if (!(Auth::user()->hasRole('Operator Kadr') || Auth::user()->hasRole('Administrator') || (User::find($request->user_id)->id==Auth::user()->id) ))
        return view('error',['head'=>'błąd wywołania funkcji change_about kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

            $user = User::where('id',$request->user_id)->first();

            if ($user->update_about($request->about))
                return back()->with('success','Zmiana informacji powiodła się.');
            else
                return back()->withErrors('Zmiana informacji niestety nie powiodła się...');
    }

    public function update_personal(Request $request)
    {
    if (!Auth::user()->hasRole('Operator Kadr') && !Auth::user()->hasRole('Administrator'))
        return view('error',['head'=>'błąd wywołania funkcji update_personal kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);
            $user = User::where('id',$request->user_id)->first();
            if ($user->update_personal($request))
                return back()->with('success','Zmiana danych osobowych powiodła się.');
            else
                return back()->withErrors('Zmiana danych osobowych niestety nie powiodła się...');
    }

    public function update_avatar(Request $request)
    {
    if (!(Auth::user()->hasRole('Operator Kadr') || Auth::user()->hasRole('Administrator') || (User::find($request->user_id)->id==Auth::user()->id) ))
        return view('error',['head'=>'błąd wywołania funkcji update_avatar kontrolera userprofile','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

        $user = Auth::user();
        if ( ($user->hasRole('Administrator')) || ($user->hasRole('Koordynator')) || ($user->hasRole('Operator Symulacji')) )
            $user = User::where('id',$request->user_id)->first();
        if ($user->update_avatar($request))
            return back()->with('success','Zmiana zdjęcia zakończona powodzeniem.');
        else
            return back()->withErrors(['update_avatar', 'Niestety - zmiana zdjęcia nie powiodła się']);
    }


    public function ajax_update_notify(Request $request)
    {
        $status = DB::table('users')
        ->where('id', $request->user_id)
        //->update(['inventory_name' => request('inventory_name')]);
        ->update(['simmed_notify' => $request->simmed_notify]);

        return json_encode(array('statusCode'=>$request->user_id, 'SQLcode'=> $status));

    }

}