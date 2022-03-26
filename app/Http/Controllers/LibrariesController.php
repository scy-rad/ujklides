<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;


class LibrariesController extends Controller
{

public function save_subject(Request $request)
{
    if (!Auth::user()->hasRole('Operator Symulacji') && !Auth::user()->hasRole('Administrator'))
    return view('error',['head'=>'błąd wywołania funkcji save_subject kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji lub Administratorem']);
      
    if ($request->id>0)
    {
        $Subject=\App\StudentSubject::find($request->id);
        $Subject->student_subject_name      = $request->modal_pl;
        $Subject->student_subject_name_en   = $request->modal_en;
        $Subject->student_subject_status    = $request->modal_st*1;
        $Subject->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    else
    {
        $Subject=new \App\StudentSubject;
        $Subject->student_subject_name      = $request->modal_pl;
        $Subject->student_subject_name_en   = $request->modal_en;
        $Subject->student_subject_status    = $request->modal_st*1;
        $Subject->save();
        return back()->with('success','Dodano nową pozycję.');
    }    
}


    
}
