<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;


class LibrariesController extends Controller
{

public function list_subjects() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRoleCode('simoperators'))
    return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.subjects')->with(['subjects' => \App\StudentSubject::orderBy('student_subject_name')->get()]);
}

public function save_subject(Request $request)
{
    if (!Auth::user()->hasRoleCode('simoperators'))
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
    if (!Auth::user()->hasRoleCode('hroperators'))
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
    if (!Auth::user()->hasRoleCode('hroperators'))
    return view('error',['head'=>'błąd wywołania funkcji save_workmonth kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);


    if ( (isset($request->action)) && ($request->action=='generate') )
    {
        if ( (is_null($request->gen_value)) ||  (!is_numeric($request->gen_value)) ||  ($request->gen_value<0) )
            return back()->withErrors('aby wygenerować czasy pracy musisz wcześniej je podać...');

            
        function dateRange( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) 
        {
            $dates = [];
            $current = strtotime( $first );
            $last = strtotime( $last );
        
            while( $current <= $last ) {
        
                $dates[] = ['data' => date( $format, $current ), 'dt' => date( 'N', $current ) ];
                $current = strtotime( $step, $current );
            }
        
            return $dates;
        }


        $users = \App\WorkMonth::select("user_id")->where('work_month',$request->month_selected.'-01')->get()->toArray();    //->get();

        $users = \App\User::role_users('workers', 1, 1)
        ->whereNotIn('id',$users)
        ->get();
        $reti='';
        
        if ($users->count()>0)
        {
            $work_time_id=\App\WorkTimeType::select('id')->where('code','work_time')->get()->first()->id;
            $time_begin = \App\Param::select('*')->orderBy('id','desc')->get()->first()->worktime_time_begin;
            $time_end   = \App\Param::select('*')->orderBy('id','desc')->get()->first()->worktime_time_end;

            foreach ($users as $user_one)
            {
                $new_row=new \App\WorkMonth;
                $new_row->user_id       = $user_one->id;
                $new_row->work_month    = $request->month_selected.'-01';
                $new_row->hours_to_work = $request->gen_value;
                $new_row->minutes_to_work = $request->gen_value*60;
                $reti.=$user_one->name.': '.$request->gen_value.'<br>';
                $new_row->save();

                foreach (dateRange( $request->month_selected.'-01', date('Y-m-t',strtotime($request->month_selected.'-01')) ) as $row_data)
                {
                if ($row_data['dt']<6)
                    {
                        $new_wt=new \App\WorkTime;
                        $new_wt->user_id = $user_one->id;
                        $new_wt->work_time_types_id=$work_time_id;
                        $new_wt->date = $row_data['data'];
                        $new_wt->time_begin = $time_begin;
                        $new_wt->time_end   = $time_end;
                        // $new_wt->description = '';
                        $new_wt->status = 1;
                        $new_wt->save();
                    }
                }
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
        $row_edit->minutes_to_work    = $request->modal_hr*60;
                
        $row_edit->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
    return back()->withErrors('procedura zapisu jeszcze nie gotowa...');

}





public function params_show() //  metoda GET bez parametrów
{
    if ( (!Auth::user()->hasRoleCode('simoperators'))
        && (!Auth::user()->hasRoleCode('hroperators'))
        && (!Auth::user()->hasRoleCode('administrators'))
    )
    return view('error',['head'=>'błąd wywołania funkcji params_show kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem lub Aministratorem']);


    $params = \App\Param::select('*')->orderBy('id','desc')->first();
    $technicians_list =\App\User::role_users('technicians', 1, 1)->orderBy('lastname')->orderBy('firstname')->get();
    $leaders_list=\App\User::role_users('instructors', 1, 1)->orderBy('lastname')->orderBy('firstname')->get();

    return view('libraries.params')->with(['params' => $params, 'technicians_list' => $technicians_list, 'leaders_list' => $leaders_list]);
}

public function params_save(Request $request)
{
    if ( (!Auth::user()->hasRoleCode('simoperators'))
    && (!Auth::user()->hasRoleCode('hroperators'))
    && (!Auth::user()->hasRoleCode('administrators'))
    )
    return view('error',['head'=>'błąd wywołania funkcji params_save kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem lub Administratorem']);

    if ($request->id>0)
    {
        $Param=\App\Param::find($request->id);
        if (Auth::user()->hasRoleCode('simoperators'))
        {
            if ( (is_null($request->simmed_days_edit_back)) ||  (!is_numeric($request->simmed_days_edit_back)) )
                return back()->withErrors('simmed_days_edit_back musi być liczbą... ['.$request->simmed_days_edit_back.']');


            $Param->leader_for_simmed       = $request->leader_for_simmed;
            $Param->technician_for_simmed   = $request->technician_for_simmed;
            $Param->statistics_start        = $request->statistics_start;
            $Param->statistics_stop        = $request->statistics_stop;
            $Param->simmed_days_edit_back   = $request->simmed_days_edit_back;
        }
        if (Auth::user()->hasRoleCode('hroperators'))
        {
            if ( (is_null($request->worktime_days_edit_back)) ||  (!is_numeric($request->worktime_days_edit_back)) )
                return back()->withErrors('worktime_days_edit_back musi być liczbą... ['.$request->worktime_days_edit_back.']');
                $Param->unit_name               = $request->unit_name;
                $Param->unit_name_wersal        = $request->unit_name_wersal;
                $Param->worktime_days_edit_back = $request->worktime_days_edit_back;
        }
        $Param->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');
    }
}



public function list_rooms() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRoleCode('simoperators'))
    return view('error',['head'=>'błąd wywołania funkcji list_rooms kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.rooms')->with(
        [
        'rooms' => \App\Room::select('*','rooms.id as id')->leftjoin('technician_characters','rooms.simmed_technician_character_propose_id','=','technician_characters.id')->get(), 
        'centers' => \App\Center::all(),
        'characters' => \App\TechnicianCharacter::all()->sortBy('character_short')
        ]);
}

public function save_room(Request $request)
{
    if (!Auth::user()->hasRoleCode('simoperators'))
    return view('error',['head'=>'błąd wywołania funkcji save_room kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    if ($request->id>0)
    {
        $room=\App\Room::find($request->id);
    }
    else
    {
        $room=new \App\Room;
    }
        $room->room_type_id     = $request->modal_type;
        $room->center_id        = $request->modal_center;
        $room->room_photo       = $request->modal_photo;
        $room->room_number	    = $request->modal_number;
        $room->room_name	    = $request->modal_name;
        $room->room_description	= $request->modal_description;
        $room->room_xp_code	    = $request->modal_xp_code;
        	
        $room->simmed_technician_character_propose_id = $request->modal_character;

        if ($request->modal_status=='on')
            $room->room_status    = 1;
        else
            $room->room_status    = 0;
        $room->save();
        return back()->with('success',' Zapis zakończył się sukcesem.');

}


public function list_student_groups() //  metoda GET bez parametrów
{
    if (!Auth::user()->hasRoleCode('simoperators'))
    return view('error',['head'=>'błąd wywołania funkcji list_student_groups kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.studentgroups')->with(['student_groups' => \App\StudentGroup::select('*','student_groups.id as id')->leftjoin('centers','student_groups.center_id','=','centers.id')->orderBy('center_id')->orderBy('student_group_code')->orderBy('student_group_name')
    ->get(), 'centers' => \App\Center::all() ]);}

public function save_student_group(Request $request)
{
    if (!Auth::user()->hasRoleCode('simoperators'))
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
    if (!Auth::user()->hasRoleCode('simoperators'))
    return view('error',['head'=>'błąd wywołania funkcji list_user_titles kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    return view('libraries.usertitles')->with(['user_titles' => \App\UserTitle::orderBy('user_title_sort')->get()]);
}

public function save_user_title(Request $request)
{
    if (!Auth::user()->hasRoleCode('simoperators'))
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





public function list_item_types()
{   // zwraca widok wszystkich typów item_types 
    if (!Auth::user()->hasRoleCode('itemoperators'))
    return view('error',['head'=>'błąd wywołania funkcji list_user_titles kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    global $max_level;                  // maksymalne zagnieżdżenie typów (poziom najmłodszego dziecka)

    $max_level = 0;                     

        function recursive_tab($id,$level)
        {   // funkcja rekurencyjna do stworzenia tablicy typów
            // $id - ID obecnie analizowanego rodzica
            // $level - obecny poziom potomków (0 dla poziomu głównego)  
            $level++;   //od razu zwiększamy poziom o 1 (CHECK IT - dlaczego od razu, a nie na koniec??)
            
            foreach (\App\ItemType::where('item_type_parent_id',$id)->orderBy('item_type_sort')->get() as $current_row)     //dla każdego item_type, którego rodzicem jest analizowane ID
            {
            if (!is_null($current_row->id))                                                                                 // jeżeli rodzic ma dzieci
                {                                                                                                           // dodaj wpis do tablicy wyjściowej
                $ret[] = ['info' => array('parent' => $current_row->item_type_parent_id, 'current' => $current_row->id, 'level' => $level, 'name' => $current_row->item_type_name), 'data' => recursive_tab($current_row->id,$level)];
                }
            }
        
        global $max_level;                                                                                                  // użyj zmiennej globalnej max_level (CHECK IT - ) 

        if ($level>$max_level)                                                                                              // jeśli obecnie analizowany poziom zagnieżdżenia jest większy niż poziom maksymalny
            $max_level=$level;                                                                                              // auktualnij poziom maksymalny do bieżącego poziomu

        if (isset($ret))                                                                                                    // jeżeli zmienna wyjściowa jest określona
            if (!is_null($ret))                                                                                             // i nie jest ona pusta
                return $ret;                                                                                                // to ją zwróć
        }

    $item_types_tab= recursive_tab(0,0);                                                                                    // wywołaj funkcję rekurencyjną zaczynając od rodziców głównych i podając poziom 0

    return view('libraries.itemtypes')->with([ 'item_types_tab' => $item_types_tab, 'max_level' => $max_level-1 ]);
}

public function ajx_item_types(Request $request)
{      // Funkcja do pobierania tablicy item_types z podziałem na poszczególne poziomy zagnieżdżenia - zwrot w JSON

    $item_types_table = \App\ItemType::select('id as id',
    'item_type_name as name', 'item_type_name' )
    ->where('item_type_parent_id', $request->item_type_id)
    ->orderBy('item_type_name')
    ->get()
    ;                                                               // pobierz tablicę typów, dla wybranego ID typu

    $current_id=$request->item_type_id;                             // zapamiętaj wybrane ID typu

    // if ($current_id>0)
    //     $parent_current = \App\ItemType::where('id', $current_id)->get()->first()->item_type_parent_id;
    // else
    //     $parent_current = 100;

        $next_id=$current_id;                                                   // zapisz wybrane ID typu do zmiennej przechowującej ID analizowanego typu CHECK IT
        $licz=100;                                                              // zmienna dla zapewnienia wyświetlenia typów w odpowiedniej kolejności  
        $table[$licz]['value'] = 0;                                             // przypisz do pierwszego wiersza tabeli wartość 0 (to będzie brak potomka-rodzica) CHECK IT

        if ( ($current_id>0)                                                    // jeżeli analizowane ID nie jest równe 0 (w przypadku nowego wpisu ) 
            // && ($parent_current>0)                                              // oraz nie jest głównym rodzicem (nie posiada rodzica) - rodzice główni są dodawani po pętli
        )
        do                                                                      // rozpocznij pętlę:
        {
        $table[$licz]['table'] = \App\ItemType::select('id as id',              // przypisz do bieżącego wiersza tabeli tabelę typów
        'id as true_id',
        'item_type_name as name')                                               // dla których rodzicem jest analizowany typ
        ->where('item_type_parent_id', $next_id)                                // CHECK IT
        // ->where('id','<>',$current_id)                                          // CHECK IT - tu chyba trzeba dać jeszcze wykluczenie $current_id, żeby wywołujący typ nie figurował na wykazie rodziców
        ->orderBy('item_type_name')
        ->get()->toArray();
        if (count($table[$licz]['table'])>0)                                        // jeśli bieżąca tabela typów nie jest pusta
            array_unshift($table[$licz]['table'], ['id' => $next_id, 'true_id' => 0, 'name' => '---' ]);   // to dodaj na jej początku wpis o id=rodzica - jak ktoś wybierze tą opcję, to system wyliczy widok dla rodzica :)
            // array_push($table[$licz]['table'], ['id' => $next_id, 'true_id' => 0, 'name' => '---' ]);   // lub można dodać to na końcu

        $licz--;                                                                // zień zmienną kolejności
        $table[$licz]['value'] = $next_id;                                      // przypisz do kolejnego wiersza tabeli wartość poprzednio analizowanego wpisu (to będzie brak potomka-rodzica) CHECK IT

        $next_id= \App\ItemType::select('item_type_parent_id')                  // wybierz do nalizy koleny typ, dla którego aktualny typ jest dzieckiem
        ->where('id', $next_id)
        ->get()->first()->item_type_parent_id;
        }
        while ($next_id>0);                                                     // i rób tą pętle dopóki kolejny wybrany typ będzie większy od 0
        
        $table[$licz]['table'] = \App\ItemType::select('id as id',              // dopisz jeszcze do ostatniego wiersza tabeli tabelę typów, które są głównymi rodzicami
        'id as true_id',
        'item_type_name as name')
        ->where('item_type_parent_id', 0)
        ->orderBy('item_type_name')
        ->get()->toArray();
        
        array_unshift($table[$licz]['table'], ['id' => 0, 'true_id' => 0, 'name' => '---' ]);   // i dopisz do tabeli typów wpis o id=0 dla braku rodzica
        
    return response()->json([                                                   // zwróć JSONa zawierającego elementy:
        'current_id'    => $current_id,                                         // ID typu, który wywołał funkcję
        'next_id'       => $next_id,                                            // ID ostatnio sprawdzanego typu - CHECK IT - chyba zawsze będzie to 0 
        'select_tables' => $table,                                              // tworzona przez funkcję tabela z danymi
        'item_types_table' => $item_types_table                                 // tabela dzieci wywoływanego typu 
    ]);
}

public function ajx_item_type_one(Request $request)
{      // Funkcja do pobierania danych wybranego item_type - zwrot w JSON

    $item_type_one = \App\ItemType::where('id', $request->id)
    ->first()
    ;   
    return response()->json([                                                   // zwróć JSONa zawierającego elementy:
        'item_type_one'    => $item_type_one                                    // dane wybranego item_type 
    ]);
}

public function save_item_type(Request $request)
{
    if (!Auth::user()->hasRoleCode('itemoperators'))
        return view('error',['head'=>'błąd wywołania funkcji save_user_title kontrolera Libraries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

    $loop=1;
    $item_type_parent=0;

    do                                                                  // sprawdź w pętli jaki jest "najniższy" wybrany rodzic 
    {
        $item_type_parent_no = 'item_type_parent'.$loop++;              // stwórz nazwę pola select (zacnij od 1)
        if (isset($request->$item_type_parent_no))                      // jeśli taka nazwa istnieje, to ją przeanalizuj
            if ($request->$item_type_parent_no > 0)                     //      i jeśli jest tam wybrana wartość większa niż 0
                $item_type_parent=$request->$item_type_parent_no;       //      to zapisz ją jako bieżącą wartość rodzica
            else                                                        //      a jeśli wybrano 0 (czyli to pole nie jest rodzicem)
                $loop=0;                                                //      zakończ wykonywanie pętli i pozostań przy ostatnio znalezionym rodzicu (lub jego braku, jeśli pierwszy select zawierał 0)
        else
            $loop=0;                                                    // to nigdy nie powinno się wydarzyć. Zawsze powinien być select z 1               
    }
    while ($loop>0);                                                    // koniec pętli

    if ($request->id>0)                                                 // jeśli w wywoływanych zmiennych jest zminna id ozncza to, że będziemy modyfikowć istniejący wpis
    {
        $itemtype=\App\ItemType::find($request->id);                    // pobierz wpis do modyfikacji

        if ($item_type_parent == $request->id)
            return back()->withErrors('Nie można być swoim własnym rodzicem... :) '.$item_type_parent.' == '.$request->id);

        $itemtype->item_type_parent_id      = $item_type_parent;
        $itemtype->item_type_master_id      = $itemtype->GetMaster($item_type_parent);
        $itemtype->item_type_name           = $request->item_type_name;
        $itemtype->item_type_description    = $request->item_type_description;
        $itemtype->item_type_sort           = $request->item_type_sort;
        $itemtype->item_type_photo          = $request->item_type_photo;
        $itemtype->item_type_code           = $request->item_type_code;
        $itemtype->item_type_status         = $request->item_type_status;
        $itemtype->save();
        // dump('save',$itemtype);
        if ($itemtype->GetMaster($request->id) != $itemtype->GetMaster($item_type_parent))  // jeśli główny rodzic edytowanego elementu jest inny niż przed edycją 
            \App\ItemType::recalculate_masters();                                           // pzelicz ponownie wszystkie wpisy głównych rodziców
        return back()->with('success',' Zapis zakończył się sukcesem: ');
    }
    else                                                                // a jeśłi nie ma id - to znaczy że jest to nowy wpis
    {
        $itemtype=new \App\ItemType;
        $itemtype->item_type_parent_id      = $item_type_parent;
        $itemtype->item_type_master_id      = $itemtype->GetMaster($request->item_type_parent);
        $itemtype->item_type_name           = $request->item_type_name;
        $itemtype->item_type_description    = $request->item_type_description;
        $itemtype->item_type_sort           = $request->item_type_sort;
        $itemtype->item_type_photo          = $request->item_type_photo;
        $itemtype->item_type_code           = $request->item_type_code;
        $itemtype->item_type_status         = $request->item_type_status;
        $itemtype->save();
        return back()->with('success','Dodano nową pozycję.');
    }

}

}
