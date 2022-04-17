<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;


class LibrariesController extends Controller
{

public function list_subjects() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.subjects')->with(['subjects' => \App\StudentSubject::all()]);
}

public function save_subject(Request $request)
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji save_subject kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
      
    if ($request->id>0)
    {
        $Subject=\App\StudentSubject::find($request->id);
        $Subject->student_subject_name      = $request->modal_pl;
        $Subject->student_subject_name_en   = $request->modal_en;
        if ($request->modal_st=='on')
            $Subject->student_subject_status    = 1;
        else
            $Subject->student_subject_status    = 0;
        $Subject->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    else
    {
        $Subject=new \App\StudentSubject;
        $Subject->student_subject_name      = $request->modal_pl;
        $Subject->student_subject_name_en   = $request->modal_en;
        if ($request->modal_st=='on')
            $Subject->student_subject_status    = 1;
        else
            $Subject->student_subject_status    = 0;
        $Subject->save();
        return back()->with('success','Dodano nową pozycję.');
    }    
}

public function list_workmonths(Request $request) //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRole('Operator Kadr'))
    return view('error',['head'=>'błąd wywołania funkcji list_workmonths kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);

    $month_list = \App\WorkMonth::select('*')->get();


    $month_listA = \App\WorkMonth::selectRaw(" substring(date_add(MIN(work_month), INTERVAL -1 MONTH),1,7) AS work_month");//->get()->first();
    $month_listB = \App\WorkMonth::selectRaw(" substring(date_add(MAX(work_month), INTERVAL 1 MONTH),1,7) AS work_month");//->get()->first();
    $month_listC = \App\WorkMonth::selectRaw("substring(work_month,1,7) AS work_month");//->get();

    $month_list = $month_listA
                -> union($month_listB)
                -> union($month_listC)
                -> orderBy('work_month')
                ->get();
    if (isset($request->month_selected))
        $filtr['month_selected'] = $request->month_selected;
    else
        $filtr['month_selected'] = date('Y-m');
    
    $WorkMonths = \App\WorkMonth::select('*','work_months.id as id')
    ->where('work_month',$filtr['month_selected'].'-01')
    ->leftjoin('users','work_months.user_id','=','users.id')
    ->orderBy('name')
    ->get();

    return view('libraries.workmonths')->with(['WorkMonths' => $WorkMonths, 'month_list' => $month_list, 'filtr' => $filtr ]);
}

public function save_workmonth(Request $request)
{
    if (!Auth::user()->hasRole('Operator Kadr'))
    return view('error',['head'=>'błąd wywołania funkcji save_workmonth kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);


    if ( (isset($request->action)) && ($request->action=='generate') )
    {
        if ( (is_null($request->gen_value)) ||  (!is_numeric($request->gen_value)) ||  ($request->gen_value<0) )
            return back()->withErrors('aby wygenerować czasy pracy musisz wcześniej je podać...');
        
        $users = \App\WorkMonth::select("user_id")->where('work_month',$request->month_selected.'-01')->get()->toArray();    //->get();

        $users = \App\User::role_users('technicians', 1, 1)
        ->whereNotIn('id',$users)
        ->get();
        $reti='';
        
        if ($users->count()>0)
        {
            foreach ($users as $user_one)
            {
                $new_row=new \App\WorkMonth;
                $new_row->user_id       = $user_one->id;
                $new_row->work_month    = $request->month_selected.'-01';
                $new_row->hours_to_work = $request->gen_value;
                $reti.=$user_one->name.': '.$request->gen_value.'<br>';
                $new_row->save();
            }
            return back()->with('success','wygenerowałem co mogłem, czyli:<br>'.$reti);
        }
        else
        return back()->with('success','nie znalazłem nic do wygenerowania...');
    }
    if ($request->id>0)
    {
        if ( (is_null($request->modal_hr)) ||  (!is_numeric($request->modal_hr)) ||  ($request->modal_hr<0) )
            return back()->withErrors('aby zmienić czas pracy musisz go podać w zrozumiałej formie...');
        
        $row_edit=\App\WorkMonth::find($request->id);
        $row_edit->hours_to_work      = $request->modal_hr;
        $row_edit->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    return back()->withErrors('procedura zapisu jeszcze nie gotowa...');

}





public function params_show() //  metoda GET bez parametrów
{
    if ( (!Auth::user()->hasRole('Operator Symulacji'))
        && (!Auth::user()->hasRole('Operator Kadr'))
        && (!Auth::user()->hasRole('Administrator'))
    )
    return view('error',['head'=>'błąd wywołania funkcji params_show kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem lub Aministratorem']);


    $params = \App\Param::select('*')->orderBy('id','desc')->first();
    $technicians_list =\App\User::role_users('technicians', 1, 1)->orderBy('name')->get();
    $leaders_list=\App\User::role_users('instructors', 1, 1)->get();

    return view('libraries.params')->with(['params' => $params, 'technicians_list' => $technicians_list, 'leaders_list' => $leaders_list]);
}

public function params_save(Request $request)
{
    if ( (!Auth::user()->hasRole('Operator Symulacji'))
        && (!Auth::user()->hasRole('Operator Kadr'))
        && (!Auth::user()->hasRole('Administrator'))
    )
    return view('error',['head'=>'błąd wywołania funkcji params_save kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem lub Administratorem']);

    if ($request->id>0)
    {
        $Param=\App\Param::find($request->id);
        if (Auth::user()->hasRole('Operator Symulacji'))
        {
            if ( (is_null($request->simmed_days_edit_back)) ||  (!is_numeric($request->simmed_days_edit_back)) )
                return back()->withErrors('simmed_days_edit_back musi być liczbą... ['.$request->simmed_days_edit_back.']');


            $Param->leader_for_simmed       = $request->leader_for_simmed;
            $Param->technician_for_simmed   = $request->technician_for_simmed;
            $Param->statistics_start        = $request->statistics_start;
            $Param->simmed_days_edit_back   = $request->simmed_days_edit_back;
        }
        if (Auth::user()->hasRole('Operator Kadr'))
        {
            if ( (is_null($request->worktime_days_edit_back)) ||  (!is_numeric($request->worktime_days_edit_back)) )
                return back()->withErrors('worktime_days_edit_back musi być liczbą... ['.$request->worktime_days_edit_back.']');

                $Param->worktime_days_edit_back = $request->worktime_days_edit_back;
        }
        $Param->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
}



public function list_rooms() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji list_rooms kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.rooms')->with(['rooms' => \App\Room::select('*','rooms.id as id')->leftjoin('technician_characters','rooms.simmed_technician_character_propose_id','=','technician_characters.id')
    ->get()]);
}

public function save_room(Request $request)
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji save_room kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    dd('ta opcja jeszcze nie została zaimplementowana');
}


public function list_student_groups() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji list_student_groups kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.studentgroups')->with(['student_groups' => \App\StudentGroup::select('*','student_groups.id as id')->leftjoin('centers','student_groups.center_id','=','centers.id')
    ->get(), 'centers' => \App\Center::all() ]);}

public function save_student_group(Request $request)
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji save_student_group kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    if ($request->id>0)
    {
        $group=\App\StudentGroup::find($request->id);
        $group->student_group_name      = $request->modal_name;
        $group->student_group_code      = $request->modal_code;
        $group->center_id               = $request->modal_center;
        if ($request->modal_character=='on')
            $group->write_technician_character_default    = 1;
        else
            $group->write_technician_character_default    = 0;
        if ($request->modal_status=='on')
            $group->student_group_status    = 1;
        else
            $group->student_group_status    = 0;
        $group->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    else
    {
        $group=new \App\StudentGroup;
        $group->student_group_name      = $request->modal_name;
        $group->student_group_code      = $request->modal_code;
        $group->center_id               = $request->modal_center;
        if ($request->modal_character=='on')
            $group->write_technician_character_default    = 1;
        else
            $group->write_technician_character_default    = 0;
        if ($request->modal_status=='on')
            $group->student_group_status    = 1;
        else
            $group->student_group_status    = 0;
        $group->save();
        return back()->with('success','Dodano nową pozycję.');
    }    

}

public function list_user_titles() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji list_user_titles kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.usertitles')->with(['user_titles' => \App\UserTitle::all()]);
}

public function save_user_title(Request $request)
{
    if (!Auth::user()->hasRole('Operator Symulacji'))
    return view('error',['head'=>'błąd wywołania funkcji save_user_title kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


    if ($request->id>0)
    {
        $usertitle=\App\UserTitle::find($request->id);
        $usertitle->user_title_short   = $request->modal_short;
        $usertitle->user_title_sort    = $request->modal_sort;
        $usertitle->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    else
    {
        $usertitle=new \App\UserTitle;
        $usertitle->user_title_short   = $request->modal_short;
        $usertitle->user_title_sort    = $request->modal_sort;
        $usertitle->save();
        return back()->with('success','Dodano nową pozycję.');
    }    

}



    
}
