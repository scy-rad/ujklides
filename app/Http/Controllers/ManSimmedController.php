<?php
 
namespace App\Http\Controllers;

use App\ManSimmed;
use App\Simmed;
use App\SimmedTemp;
use App\TechnicianCharacter;
use App\SimmedTempPost;
use App\SimmedTempRoom;
use App\User;
use App\UserTitle;
use App\Roles;
use App\StudentSubject;
use App\StudentGroup;
use App\StudentSubgroup;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManSimmedController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //  metoda GET bez parametrów
    {
    if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        return view('mansimmeds.index');
    }

    public function subjects() //  metoda GET bez parametrów
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        return view('mansimmeds.subjects')->with(['subjects' => StudentSubject::all()]);
    }

    public function groups() //  metoda GET bez parametrów
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        return view('mansimmeds.groups')->with(['groups' => StudentGroup::all()]);
    }


    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   F I L E               ##
    ##                                           ##
    \*###########################################*/


    public function import_file(Request $request)
    {
        // funkcja otwiera tylko widok do importowania pliku 
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        return view('mansimmeds.import_file')->with( ['max_import_number' => SimmedTemp::max('import_number')] );
    }   //end of public function import_file


    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   C H E C K             ##
    ##                                           ##
    \*###########################################*/


    public function import_check(Request $request)
    {
        // funkcja wczytuje plik do tabeli simmed_temps
        // nie sprawdza, czy dane mają odzwierceidelnie w istniejących wpisach (jak np. prowadzący, czy tematy zajęć)
        // do tego służy funkcja reread_import, którą należy uruchomić po wykonanym imporcie.
        // funkcje są rozdzielone z uwagi na długie casy wykonywania każdej z nich. 
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        function magic_date($word_date)
        {
            if (date('m')<8)
                if (substr($word_date,3,2)>9)
                    return date('Y-',strtotime("-1 year", time())).substr($word_date,3,2).'-'.substr($word_date,0,2);
                else
                    return date('Y-').substr($word_date,3,2).'-'.substr($word_date,0,2);
            else
                if (substr($word_date,3,2)>9)
                    return date('Y-').substr($word_date,3,2).'-'.substr($word_date,0,2);
                else
                    return date('Y-',strtotime("+1 year", time())).substr($word_date,3,2).'-'.substr($word_date,0,2);
        }

        $data=null;

        $request->import_data=str_replace("\r\n\r\n"|"\r\n \r\n","\r\nx\r\n",$request->import_data);

        $max_import_number=SimmedTemp::max('import_number')+1;

            $rows = explode("\n", str_replace("\r", "", $request->import_data));
            $data['info']['wrong_count']=0;

            $row_number=0;

            if ($request->import_type == "xp")
                                            ////////////////////////////////////////
            foreach ($rows as $import_row)  // początek analizy pliku z uczelniXP //
            {
                $row_number++;
                $data_write=null;

                $data_write['status']='wrong';

                $data_rows=explode("\t",$import_row);

                if (count($data_rows)==8)   //import z uczelni XP powinien zawierać 8 kolumn
                {
                    if ($data_rows[0]=='Daty zajęć')    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
                    {
                        $data_write['status']='head';
                    }
                    else          //a jeżeli nie - to analizujemy wszystkie pola
                    {
                        $new_tmp = new SimmedTemp();
                        $new_tmp['import_number']              = $max_import_number;
                        $new_tmp['import_row']                 = $import_row;
                        $new_tmp['simmed_tmp_id']              = $max_import_number;
                        $new_tmp['room_id']                    = $current_room_id;
                        $new_tmp['room_xp_txt']                = $current_room_xp_code;
                        $new_tmp['simmed_date']                = magic_date($data_rows[0]);
                        $new_tmp['simmed_time_begin']          = $data_rows[2];
                        $new_tmp['simmed_time_end']            = $data_rows[3];
                        $new_tmp['simmed_leader_txt']          = trim($data_rows[4]);
                        $new_tmp['student_subject_txt']        = trim($data_rows[5]);
                        $new_tmp['student_group_txt']          = trim(substr(str_replace("/N","/S",$data_rows[6]),2,60));
                        if (strpos($new_tmp['student_group_txt'],'/Sem.',0)>0)
                            $new_tmp['student_group_txt'] = substr($new_tmp['student_group_txt'],0,strpos($new_tmp['student_group_txt'],'/Sem.',0));
                        if (strpos($new_tmp['student_group_txt'],'/sem.',0)>0)
                            $new_tmp['student_group_txt'] = substr($new_tmp['student_group_txt'],0,strpos($new_tmp['student_group_txt'],'/sem.',0));                  
                        $new_tmp['student_subgroup_txt']       = trim($data_rows[7]);

                        $return=$new_tmp->save();
                    }
                }   //if (count($data_rows)==8)
                elseif (substr($import_row,0,8)=='Zajęcia') //chyba że jest to nagłówek tabeli sali
                {
                    $sub_data=explode(" ",$import_row);
                    $current_room_xp_code=trim($sub_data[3]);
                    $current_room_id=Room::find_xp_room($current_room_xp_code);

                        $now = new \DateTime();
                        $d = $now::createFromFormat('d-m-Y', $sub_data[8]);
                        if (!($d && $d->format('d-m-Y')))
                            $data['info']['missing_date']=1;    // to dorzuciłem do domyślnych
                            elseif ( $d->format('d-m-Y') != $sub_data[8])
                                $data['info']['missing_date']++;
                            else
                                {
                                $data['info']['from']=$d->format('Y-m-d');
                                $data['info']['missing_date']=0;
                                }

                        $d = $now::createFromFormat('d-m-Y', $sub_data[10]);
                        if (!($d && $d->format('d-m-Y')))
                            $data['info']['missing_date']=1;
                            elseif ( $d->format('d-m-Y') != $sub_data[10])
                                $data['info']['missing_date']++;
                            else
                                $data['info']['to']=$d->format('Y-m-d');
                }
                elseif (strlen($import_row)>1)
                {
                    $data['info']['wrong_count']++;
                    $data['wrong'][]=$import_row;
                }

            }
            // koniec analizy pliku z uczelniXP //

            if ($request->import_type == "xls")
                                            ////////////////////////////////////////
            foreach ($rows as $import_row)  // początek analizy pliku z  eXcela Ilony //
            {

                    dump('import z xlsa nie poprawiony wciąż');
                $row_number++;
                $data_write=null;

                $data_write['status']='wrong';

                $data_rows=explode("\t",$import_row);
                if (count($data_rows)>10)    //import z pliku XLS powinien zawierać więcej niż 9 kolumn
                {                           // początek analizy wiersza
                    if ($data_rows[0]=='data')    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
                    {
                        $data_write['status']='head';
                        dump($data_rows);
                    }
                    else          //a jeżeli nie - to analizujemy wszystkie pola
                    {
                        $new_tmp = new SimmedTemp();
                        $new_tmp['import_number']              = $max_import_number;
                        $new_tmp['import_row']                 = $import_row;
                        $new_tmp['simmed_tmp_id']              = $max_import_number;
                        $new_tmp['room_id']                    = Room::find_xls_room(trim($data_rows[3]));
                        $new_tmp['room_xls_txt']               = trim($data_rows[3]);
                        $new_tmp['simmed_date']                = substr($data_rows[0],6,4).'-'.substr($data_rows[0],3,2).'-'.substr($data_rows[0],0,2);
                        $new_tmp['simmed_time_begin']          = substr($data_rows[2],0,5);
                        $new_tmp['simmed_time_end']            = substr($data_rows[2],6,5);
                        $new_tmp['simmed_leader_txt']          = trim($data_rows[4]);
                        $new_tmp['student_subject_txt']        = trim($data_rows[5]);
                        $new_tmp['student_group_txt']          = trim($data_rows[6]);
                        $new_tmp['student_subgroup_txt']       = trim($data_rows[7]);
                        $new_tmp['simmed_alternative_title']   = trim($data_rows[10]);
                        //$new_tmp['simmed_technician_id']       = User::find_user($data_rows[8]);
                        $new_tmp['simmed_technician_character_id']   = TechnicianCharacter::where('character_short',$data_rows[9])->first()->id;
                        // dump($new_tmp);
                        // dd($data_rows[9]);
                        $return=$new_tmp->save();
                        dump('OK'.$return);
                    }
                }   // koniec analizy wiersza
                else
                {
                    dump('błędny wiersz ['.count($data_rows).']: '.$data_rows);
                }
            }
            // koniec analizy pliku z eXcela Ilony //
            /////////////////////////////////////////

        //$request->info_wrong_count=$data['info'];

        $duplicates = DB::table('simmed_temps')
        ->select('simmed_date','simmed_time_begin','simmed_time_end','room_xp_txt','simmed_leader_txt','student_subject_txt','student_group_txt','student_subgroup_txt', DB::raw('COUNT(*) as `count`'))
       ->groupBy('simmed_date','simmed_time_begin','simmed_time_end','room_xp_txt','simmed_leader_txt','student_subject_txt','student_group_txt','student_subgroup_txt')
       //->havingRaw('COUNT(*) > 1')
       ->having('count', '>', 1)
       ->orderBy('simmed_date','simmed_time_begin','simmed_time_end','room_xp_txt','simmed_leader_txt','student_subject_txt','student_group_txt','student_subgroup_txt')
       ->get();

       foreach ($duplicates as $duplicate)
            {
            $zwrot=SimmedTemp::where('simmed_date',$duplicate->simmed_date)
            ->where('simmed_time_begin',$duplicate->simmed_time_begin)
            ->where('simmed_time_end',$duplicate->simmed_time_end)
            ->where('room_xp_txt',$duplicate->room_xp_txt)
            ->where('simmed_leader_txt',$duplicate->simmed_leader_txt)
            ->where('student_subject_txt',$duplicate->student_subject_txt)
            ->where('student_group_txt',$duplicate->student_group_txt)
            ->where('student_subgroup_txt',$duplicate->student_subgroup_txt)
            ->get()
            ->first()
            ->delete();
            }

        // $data['miss']=app('App\Http\Controllers\ManSimmedController')->check_tmp_data();
        $data['step']=$request->step;
        //$data['simmeds']=SimmedTemp::all()->toArray();

        $data['max_import_number']=SimmedTemp::max('import_number');

        //return view('mansimmeds.import_check')->with($data);
        return view('mansimmeds.import_file')->with($data);
    }   //end of public function import_check


    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   R E R E A D           ##
    ##                                           ##
    \*###########################################*/


    public function import_reread(Request $request)
    {
        //funkcja, która musi być wywołana po zaimportowaniu pliku do bazy tymczasowej.
        //sprawdza, czy importowane dane mają odzwierciedlenie w już istniejących danych (np. sale, tematy itd.)
        //$data['simmeds']=SimmedTemp::all()->toArray();
        $data['miss']=app('App\Http\Controllers\ManSimmedController')->check_tmp_data();
        $data['simmeds']=SimmedTemp::whereNull('simmed_leader_id')
            ->orWhereNull('student_subject_id')
            ->orWhereNull('student_group_id')
            ->get();
            //->toArray();
        $data['step']=$request->step;

        return view('mansimmeds.import_check')->with($data);
    }   //end of public function import_REREAD


    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   C O M P L E M E N T   ##
    ##                                           ##
    \*###########################################*/


    public function import_complement(Request $request)
    {

        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        foreach ($request->request as $key=>$value)  // tworzymy tabelę liderów, która zawiera wybraną wcześniej akcję (na razie dodaj i pomiń, ale może changr też będzie)
                                                    //może kiedyś będę mógł wybrać, co się dzieje ze znalezionymi brakami - czy mają być dodane, czy wpisane jako puste.
        {
            if ($value=='dodaj')
            {
                if (substr($key,0,16)=="missing_leaders-")      //key zawiera tekst "missing_leaders-" po którym jest ID wiersza, w którym jest dany leader
                {
                    $fullname=trim(SimmedTemp::find(substr($key,16,5))->simmed_leader_txt);

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

                    if (UserTitle::where('user_title_short',$title)->count() > 0)
                    {
                        $leader = new User;
                        $leader->user_title_id=UserTitle::where('user_title_short',$title)->first()->id;
                        $leader->firstname = $firstname;
                        $leader->lastname = $lastname;
                        $leader->name = hrtime()[1];
                        $leader->email = hrtime()[1].'@ujk.edu.pl';
                        $leader->password = bcrypt('pass'.hrtime()[1]);
                        $leader->user_status = 1;
                        $leader->simmed_notify = 0;

                        $leader->save();
                        $leader->add_roles(Roles::find_by_name('Instruktor'),1);

                        SimmedTemp::where('simmed_leader_txt',$fullname)
                            ->where('simmed_leader_id',0)
                            ->update(['simmed_leader_id' => $leader->id]);
                    }
                }
                if (substr($key,0,17)=="missing_subjects-")
                {
                    $subject_name=trim(SimmedTemp::find(substr($key,17,5))->student_subject_txt);
                    $subject=new StudentSubject();
                    $subject->student_subject_name=$subject_name;
                    $subject->student_subject_status=1;
                    $subject->save();

                    SimmedTemp::where('student_subject_txt',$subject_name)
                        ->where('student_subject_id',0)
                        ->update(['student_subject_id' => $subject->id]);
                }
                if (substr($key,0,15)=="missing_groups-")
                {
                    $group_name=trim(SimmedTemp::find(substr($key,15,5))->student_group_txt);
                    $wydzial=1;
                    dump('ManSimMed ADD GROUP - trzeba zmienić wybór wydzialu');

                    if (strpos($group_name, 'P/', 0)>0) $wydzial=1;//pielęgniarstwo
                if (strpos($group_name, 'PIEL/', 0)>0) $wydzial=1;//położnictwo
                if (strpos($group_name, 'Po/', 0)>0) $wydzial=1;//położnictwo
                if (strpos($group_name, 'POŁ', 0)>0) $wydzial=1;//położnictwo
                if (strpos($group_name, 'LEK/', 0)>0) $wydzial=2;//lekarski
                if (strpos($group_name, 'RM/', 0)>0) $wydzial=3;//ratownictwo
                        
                if ($wydzial>0)
                    {
                        $group=new StudentGroup();
                        $group->student_group_name=$group_name;
                        $group->center_id=$wydzial;
                        $group->student_group_status=1;
                        $group->save();

                        SimmedTemp::where('student_group_txt',$group_name)
                            ->where('student_group_id',0)
                            ->update(['student_group_id' => $group->id]);
                    }
                }
                if (substr($key,0,18)=="missing_subgroups-")
                {
                    $subgroup_name=trim(SimmedTemp::find(substr($key,18,5))->student_subgroup_txt);
                    $group_id=SimmedTemp::find(substr($key,18,5))->student_group_id;
                    $subgroup=new StudentSubgroup();
                    $subgroup->student_group_id=$group_id;
                    $subgroup->subgroup_name=$subgroup_name;
                    $subgroup->subgroup_status=1;
                    $subgroup->save();

                        SimmedTemp::where('student_subgroup_txt',$subgroup_name)
                            ->where('student_group_id',$group_id)
                            ->where('student_subgroup_id',0)
                            ->update(['student_subgroup_id' => $subgroup->id]);
                }
            }
        }

        $data['step']='check_data';

        $data['step']=$request->step;

        $data['miss']=app('App\Http\Controllers\ManSimmedController')->check_tmp_data();

        $data['simmeds']=SimmedTemp::whereNull('simmed_leader_id')
        ->orWhereNull('student_subject_id')
        ->orWhereNull('student_group_id')
        ->get();

        return view('mansimmeds.import_check')->with($data);
        //return view('mansimmeds.import')->with($data);
    }   //end of public function import_complement


    /*###########################################*\
    ##                                           ##
    ##       C L E A R   I M P O R T   F I L E   ##
    ##                                           ##
    \*###########################################*/


    public function clear_import_tmp()
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        SimmedTemp::truncate();

        $data['step']="clear_tmp";
        $data['max_import_number']=SimmedTemp::max('import_number');
        return view('mansimmeds.import_file')->with($data);

    }   //end of public function clear_import_tmp



    /*###########################################*\
    ##                                           ##
    ##       C H E C K   T M P   D A T A         ##
    ##                                           ##
    \*###########################################*/


    function check_tmp_data()
    {
        $data=null;

        $data['no_rooms']=SimmedTemp::select('room_xp_txt')->where('room_id',0)->distinct()->get();


        //      INFO: wybierz wiersze, gdzie nie ma określonych ID instruktorów
        //
        //      LEADERS
        //
        //$no_leaders=SimmedTemp::select('simmed_leader_txt')->where('simmed_leader_id',0)->where('simmed_leader_txt','<>','')->distinct()->get();
        $no_leaders=SimmedTemp::select('simmed_leader_txt')->whereNull('simmed_leader_id')->where('simmed_leader_txt','<>','')->distinct()->get();

        $data['missing_leaders']=0;
        //INFO: przeanalizuj wszystkie wiersze bez zidentyfikowanych instruktorów
        if (count($no_leaders)>0)
        foreach ($no_leaders as $no_leader)
            {
            //INFO: Sprawdź, czy możesz już zidentyfikować tego instruktora
            $simmed_leader_id=User::find_user($no_leader->simmed_leader_txt);
            if ($simmed_leader_id==0)
                {
                //INFO: Jeśli nie - stwórz tablicę do stworzenia listy braków z akcjami do wykonania (pomiń/dodaj) - potrzebna w widoku
                //      Tablica ma indeks pierwszego wpisu z takim niezidentyfikowanym instruktorem - po to, żeby nie powielać go kilkukrotnie
                //      i zawiera jego nazwę (co jest istotne) oraz akcje pomiń lub dodaj - co można by sobie odpuścić i założyć, że zawsze jest jedna z akcji
                //      (co de fakto i tak robię), ale zostawię tak, bo kiedyś może będe chciał różnicowac akcje
                $licz=SimmedTemp::where('simmed_leader_txt',$no_leader->simmed_leader_txt)->first()->id;
                $data['no_leader_list'][$licz]['row']=$licz;
                $data['no_leader_list'][$licz]['name']=$no_leader->simmed_leader_txt;

                if (($no_leader->simmed_leader_txt=='') || !(strpos($no_leader->simmed_leader_txt,' ')))
                    $data['no_leader_list'][$licz]['action']='pomiń';
                else
                    $data['no_leader_list'][$licz]['action']='pomiń';//'dodaj';

                $data['missing_leaders']++;
                }
            else
                {
                // Jeśli tak - to wpisz jego ID do wszystkich wystąpień w bazie z tymczasowym importem.
                SimmedTemp::where('simmed_leader_txt',$no_leader->simmed_leader_txt)
                    ->whereNull('simmed_leader_id')
                    ->update(['simmed_leader_id' => $simmed_leader_id]);
                }
            } // end of foreach ($no_leaders as $no_leader)

        //
        //      SUBJECTS
        //
        $no_subjects=SimmedTemp::select('student_subject_txt')->whereNull('student_subject_id')->where('student_subject_txt','<>','')->distinct()->get();
        $data['missing_subjects']=0;
        if (count($no_subjects)>0)
        foreach ($no_subjects as $no_subject)
            {
            $student_subject_id=StudentSubject::where('student_subject_name',$no_subject->student_subject_txt)->orWhere('student_subject_name_en',$no_subject->student_subject_txt)->first();
            if ($student_subject_id===NULL)
                {
                $licz=SimmedTemp::where('student_subject_txt',$no_subject->student_subject_txt)->first()->id;
                $data['no_subject_list'][$licz]['row']=$licz;
                $data['no_subject_list'][$licz]['name']=$no_subject->student_subject_txt;

                if (substr($no_subject->student_subject_txt,1,6)=='ezerwa')       //jeśli tematem zajęć jest rezerwacja, to nie dodawaj jej do tematów zajęć
                    $data['no_subject_list'][$licz]['action']='pomiń';
                else
                    $data['no_subject_list'][$licz]['action']='pomiń';//'dodaj';
                $data['missing_subjects']++;
                }
            else
                {
                SimmedTemp::where('student_subject_txt',$no_subject->student_subject_txt)
                    ->whereNull('student_subject_id')
                    ->update(['student_subject_id' => $student_subject_id->id]);
                }
            } // end of $no_subjects as $no_subject

        //
        //      GROUPS
        //
        $no_groups=SimmedTemp::select('student_group_txt')->whereNull('student_group_id')->where('student_group_txt','<>','')->distinct()->get();
        $data['missing_groups']=0;
        if (count($no_groups)>0)
        foreach ($no_groups as $no_group)
            {
            $student_group_id=StudentGroup::where('student_group_name',$no_group->student_group_txt)->first();
            if ($student_group_id===NULL)
                {
                $licz=SimmedTemp::where('student_group_txt',$no_group->student_group_txt)->first()->id;
                $data['no_group_list'][$licz]['row']=$licz;
                $data['no_group_list'][$licz]['name']=$no_group->student_group_txt;
				$data['no_group_list'][$licz]['action']='pomiń';//'dodaj';
				$data['missing_groups']++;
                }
            else
                {
                SimmedTemp::where('student_group_txt',$no_group->student_group_txt)
                    ->whereNull('student_group_id')
                    ->update(['student_group_id' => $student_group_id->id]);
                }
            } // end of $no_groups as $no_group

        //
        //      SUBGROUPS
        //
		$no_subgroups=SimmedTemp::select('student_subgroup_txt','student_group_id')->whereNull('student_subgroup_id')->where('student_subgroup_txt','<>','')->where('student_group_id','>',0)->distinct()->get();
        $data['missing_subgroups']=0;
        if (count($no_subgroups)>0)
        foreach ($no_subgroups as $no_subgroup)
            {
            $student_subgroup_id=StudentSubgroup::where('subgroup_name',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)->first();
            if ($student_subgroup_id===NULL)
                {
                $licz=SimmedTemp::where('student_subgroup_txt',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)->first()->id;
                $data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['row']=$licz;
                $data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['name']=$no_subgroup->student_subgroup_txt;
				$data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['action']='pomiń';//'dodaj';
				$data['missing_subgroups']++;
                }
            else
                {
                SimmedTemp::where('student_subgroup_txt',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)
                    ->whereNull('student_subgroup_id')
                    ->update(['student_subgroup_id' => $student_subgroup_id->id]);
                }
            } // end of $no_subgroups as $no_subgroup


        return $data;
    }   //end of function check_tmp_data()



   /*###########################################*\
    ##                                           ##
    ##       I M P O R T   A N A L Y Z E         ##
    ##                                           ##
    \*###########################################*/


    public function impanalyze(Request $request)
    {
        // funkcja azalizuje wpisy zawarte w tabeli tymczasowej i uznaje któe z nich są nowe, które zmienione, oraz których brakuje w imporcie
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

            // 0   -   nowy import
            // 1   -   dodaj wiersz do bazy
            // 2   -   aktualizuj wpis
            // 3   -   usuń wiersz z bazy
            // 4   -   pomiń wpis
        
        dd('Funkcja impanalyze ManSimmedController - to trzeba rozbić na kilka mniejszych funkcji, bo za długo się wykonuje...');

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetmp)
        {   //sprawdż, czy dane wpisy nie są duplikatami
            $wynk=Simmed::where('simmed_date',$onetmp->simmed_date)
                        ->where('simmed_time_begin',$onetmp->simmed_time_begin)
                        ->where('simmed_time_end',$onetmp->simmed_time_end)
                        ->where('student_subject_id',$onetmp->student_subject_id)
                        ->where('student_group_id',$onetmp->student_group_id)
                        ->where('student_subgroup_id',$onetmp->student_subgroup_id)
                        ->where('room_id',$onetmp->room_id)
                        ->where('simmed_leader_id',$onetmp->simmed_leader_id)
                        ->get();
            if ($wynk->count()>0)
            {
                $onetmp->simmed_id=$wynk->first()->id;
                $onetmp->tmp_status=4;
                $onetmp->save();
                dump('ManSimmedController: znalazłem taki sam wpis');
            }
        }
        
        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
            $onetemp->check_similar('leader,subject,group,date,time');//zmiana tylko sali

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
            foreach ($alltmps as $onetemp)
            $onetemp->check_similar('room,leader,subject,group,date');//zmiana tylko czasu

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('room,subject,group,date,time');//zmiana tylko prowadzącego

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('room,leader,group,date,time');//zmiana tylko tematu

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('room,leader,subject,date,time');//zmiana tylko grupy

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('room,leader,date,time');//zmiana grupy i tematu

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('leader,subject,group');//zmiana sali i czasu

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('room,date,time');//ta sama sala i czas

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('leader,subject,group,deleted');//zmiana sali i czasu w usuniętych 

        $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
        foreach ($alltmps as $onetemp)
                $onetemp->check_similar('subject,group,deleted');//zmiana prowadzącego, sali i czasu w usuniętych 

        $min_date=SimmedTemp::all('simmed_date')->min('simmed_date');
        $max_date=SimmedTemp::all('simmed_date')->max('simmed_date');
        $IDSy=Simmed::pluck('id')->toArray();
        
        $for_delete=Simmed::where('simmed_status','<',4)->whereNotIn('id',$IDSy)->whereBetween('simmed_date',[$min_date,$max_date])->get();

        if ($for_delete->count()>0)
        {
            foreach ($for_delete as $delete_row)
                {
                    $simmed_row->tmp_status = 4;
                    add_row($simmed_row);
                }
        }

        $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();


        // $data_return=SimmedTemp::all()
        //         ->sortBy('simmed_time_begin')
        //         ->sortBy('simmed_date')
        //         ->sortBy('student_subject_id')
        //         ->sortBy('student_subgroup_id')
        //         ->sortBy('student_group_id')
        //         ->sortBy('simmed_merge')
        //         ;


        $data_return=SimmedTemp::orderByDesc('tmp_status')
            ->orderBy('simmed_merge')
            ->orderByDesc('simmed_id')

            ->orderBy('student_group_id')
            ->orderBy('student_subgroup_id')
            ->orderBy('student_subject_id')

            ->orderBy('simmed_date')
            ->orderBy('simmed_time_begin')
            ->get()
            ;

        //return view('mansimmeds.impanalyze')->with($data_return);
        return view('mansimmeds.impanalyze', compact('data_return'));
    }   // end of public function impanalyze






    public function markimport(Request $request)
    {
        //funkcja utawia status nowych danych na "dodaj"
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        SimmedTemp::where('tmp_status','=','0')->where('simmed_id', '=', '0')->update(['tmp_status' => 1]); //ustaw status pozostałych na zaimportuj

        $data_return=SimmedTemp::orderByDesc('tmp_status')
            ->orderBy('simmed_merge')
            ->orderByDesc('simmed_id')

            ->orderBy('student_group_id')
            ->orderBy('student_subgroup_id')
            ->orderBy('student_subject_id')

            ->orderBy('simmed_date')
            ->orderBy('simmed_time_begin')
            ->get()
            ;

        return view('mansimmeds.impanalyze', compact('data_return'));
    }   // end of public function markimport




    public function clearimport(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji clearimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        echo 'czyszczę...';

        SimmedTemp::truncate();

        $data_return=SimmedTemp::all();

        return view('mansimmeds.impanalyze', compact('data_return'));
    }   // end of public function clearimport





    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   A P P E N D           ##
    ##                                           ##
    \*###########################################*/


    public function import_append(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
    
        function move_simmed($data_one)
        {
            if ($data_one->simmed_id>0)
                {
                $new_row=SiMmed::find($data_one->simmed_id);
                dump('JEST',$new_row);
                }
            else
                {
                $new_row=new Simmed();
                dump('NOWA',$data_one);
                }
            $new_row->simmed_date						= $data_one->simmed_date;
            $new_row->simmed_time_begin				    = $data_one->simmed_time_begin;
            $new_row->simmed_time_end					= $data_one->simmed_time_end;
            $new_row->simmed_type_id					= $data_one->simmed_type_id;
            $new_row->student_subject_id	        	= $data_one->student_subject_id;
            $new_row->student_group_id    			    = $data_one->student_group_id;
            $new_row->student_subgroup_id				= $data_one->student_subgroup_id;
            $new_row->room_id     					    = $data_one->room_id;
            $new_row->simmed_leader_id	    		    = $data_one->simmed_leader_id;
            $new_row->simmed_technician_id    		    = $data_one->simmed_technician_id;
            $new_row->simmed_technician_character_id    = $data_one->simmed_technician_character_id;
            $new_row->simmed_alternative_title		    = $data_one->simmed_alternative_title;
            if ($data_one->simmed_alternative_title!='')
                $new_row->simmed_alternative_title=$data_one->simmed_alternative_title;
            else
            {
                $alt_txt='';
                if (is_null($data_one->simmed_leader_id))
                    $alt_txt.=$data_one->simmed_leader_txt.', ';
                if (is_null($data_one->student_subject_id))
                    $alt_txt.=$data_one->student_subject_txt.', ';
                if (is_null($data_one->student_group_id))
                    $alt_txt.=$data_one->student_group_txt.' ';
                $new_row->simmed_alternative_title=trim($alt_txt);
            }
            $new_row['simmed_status']					= 1;
            $new_row['simmed_status2']					= 1;
            
            $ret=$new_row->save();
            $data_one->delete();
        }

        $data=null;
        $data['step']=$request->step;

        $data_all=SimmedTemp::where('tmp_status',1)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                {
                    move_simmed($data_one);
                    dump('add newX',$data_one);
                }

        $data_all=SimmedTemp::where('tmp_status',2)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
            {
                move_simmed($data_one);
                dump('updateX',$data_one);
            }

        $data_all=SimmedTemp::where('tmp_status',3)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('remove',$data_one);

        $data_all=SimmedTemp::where('tmp_status',9)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('back',$data_one);

        SimmedTemp::where('tmp_status',4)->delete(); //usuń pominięte

        


        return view('mansimmeds.import')->with($data);
    }   //end of public function import_append









    public function doimport(Request $request)
    {
        //import danych już po przejrzeniu i ustaleniu, które z nich mają zostać dodane/nadpisane/usunięte??/itp
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji doimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        echo 'importuję...';
        dd('tu');

        foreach (SimmedTemp::where('tmp_status',1)->get() as $doImport)
        {
            dd('status 1');
            //dd('1',$doImport);
            $zmEQ = new Simmed();
			$zmEQ->simmed_date=$doImport->simmed_date;
			$zmEQ->simmed_time_begin=$doImport->simmed_time_begin;
			$zmEQ->simmed_time_end=$doImport->simmed_time_end;
			$zmEQ->student_subject_id=$doImport->student_subject_id;
			$zmEQ->student_group_id=$doImport->student_group_id;
            $zmEQ->student_subgroup_id=$doImport->student_subgroup_id;
			$zmEQ->room_id=$doImport->room_id;
            $zmEQ->simmed_leader_id=$doImport->simmed_leader_id;
            //$zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            if ($doImport->simmed_alternative_title!='')
                $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            else
            {
                $alt_txt='';
                if ($doImport->simmed_leader_id==NULL)
                    $alt_txt.=$doImport->simmed_leader_txt.' ';
                if ($doImport->student_subject_id==NULL)
                    $alt_txt.=$doImport->student_subject_txt.' ';
                if ($doImport->student_group_id==NULL)
                    $alt_txt.=$doImport->student_group_txt.' ';
                $zmEQ->simmed_alternative_title=$all_txt;
                dd($all_txt);
            }
			$return=$zmEQ->save();

            if ($return==1)
                SimmedTemp::find($doImport->id)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',2)->where('simmed_id','=',0)->get() as $doImport)
        {
            dd('status 2');
            $id_to_change=SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->where('simmed_id','>',0)->get()->first()->simmed_id;
            //dd($doImport,$id_to_change);
            $zmEQ = Simmed::where('id',$id_to_change)->first();
			$zmEQ->simmed_date=$doImport->simmed_date;
			$zmEQ->simmed_time_begin=$doImport->simmed_time_begin;
			$zmEQ->simmed_time_end=$doImport->simmed_time_end;
			$zmEQ->student_subject_id=$doImport->student_subject_id;
			$zmEQ->student_group_id=$doImport->student_group_id;
            $zmEQ->student_subgroup_id=$doImport->student_subgroup_id;
			$zmEQ->room_id=$doImport->room_id;
            $zmEQ->simmed_leader_id=$doImport->simmed_leader_id;
            //$zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            if ($doImport->simmed_alternative_title!='')
                $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            else
            {
                $alt_txt='';
                if ($doImport->simmed_leader_id==NULL)
                    $alt_txt.=$doImport->simmed_leader_txt.' ';
                if ($doImport->student_subject_id==NULL)
                    $alt_txt.=$doImport->student_subject_txt.' ';
                if ($doImport->student_group_id==NULL)
                    $alt_txt.=$doImport->student_group_txt.' ';
                $zmEQ->simmed_alternative_title=$all_txt;
                dd($all_txt);
            }
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',9)->get() as $doImport)
        {
            dd('status 9');
            $zmEQ = Simmed::where('id',$doImport->simmed_merge)->first();
			$zmEQ->simmed_date=$doImport->simmed_date;
			$zmEQ->simmed_time_begin=$doImport->simmed_time_begin;
			$zmEQ->simmed_time_end=$doImport->simmed_time_end;
			$zmEQ->student_subject_id=$doImport->student_subject_id;
			$zmEQ->student_group_id=$doImport->student_group_id;
            $zmEQ->student_subgroup_id=$doImport->student_subgroup_id;
			$zmEQ->room_id=$doImport->room_id;
            $zmEQ->simmed_leader_id=$doImport->simmed_leader_id;
            //$zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            if ($doImport->simmed_alternative_title!='')
                $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            else
            {
                $alt_txt='';
                if ($doImport->simmed_leader_id==NULL)
                    $alt_txt.=$doImport->simmed_leader_txt.' ';
                if ($doImport->student_subject_id==NULL)
                    $alt_txt.=$doImport->student_subject_txt.' ';
                if ($doImport->student_group_id==NULL)
                    $alt_txt.=$doImport->student_group_txt.' ';
                $zmEQ->simmed_alternative_title=$all_txt;
                dd($all_txt);
            }
            $zmEQ->simmed_status=1;
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',3)->where('simmed_id','>',0)->get() as $doImport)
        {
            dd('status 3');
            $zmEQ = Simmed::where('id',$doImport->simmed_id)->first();
            $zmEQ->simmed_status=4; //zmiana statutu na 4 (czyli usunięty)
            $return=$zmEQ->save();
            //$return=$zmEQ->delete();

            if ($return==1)
                $doImport->delete();
        }

        // if (SimmedTemp::all()->count()==0)
        //     SimmedTempRoom::where('import_status',0)->update(['import_status'=>'1']);

        $data['step']='review_analyze';
        $data['import_data']=SimmedTemp::all();

        return view('mansimmeds.impanalyze')->with($data);
    }   // end of public function doimport




















































 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo 'app \ Http \ Controllers \ ManSimmedController \ create';
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
        echo 'app \ Http \ Controllers \ ManSimmedController \ store';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function show(ManItem $manItem)  //  metoda GET z parametrem
    {
        //
        echo 'app \ Http \ Controllers \ ManSimmedController \ show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManSimmedController \ edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManSimmedController \ update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManSimmedController \ destroy';
    }



    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function generate_csv()
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
  
        $filename="simmeds.csv";
        $fp = fopen($filename, 'w');

        $to_csv[]='data';
        $to_csv[]='dz.tyg.';
        $to_csv[]='godz.';
        $to_csv[]='sala';
        $to_csv[]='instr';
        $to_csv[]='przedmiot';
        $to_csv[]='grupa';
        $to_csv[]='podgr.';
        $to_csv[]='technik';
        $to_csv[]='char.';
        $to_csv[]='uwagi';
        fputcsv($fp,$to_csv,';');

        // $subtech=DB::table
        // ->fromSub(function ($query) {
        //     $query->select('name')->from('users');
        // }, 'technician_users');
    
        $alldata=DB::table('simmeds')
          ->select('simmed_date', \DB::raw('dayname(simmed_date) as DayOfWeek'),  \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'), 'room_number', 
          \DB::raw('concat(user_titles.user_title_short," ",users.lastname," ",users.firstname) as leader'), 
          'student_subject_name', 'student_group_name', 'subgroup_name',
          'technicians.name as technician', 
           'character_short',
           'simmed_alternative_title'
           )
          ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
          ->leftjoin('users','simmeds.simmed_leader_id','=','users.id')
          ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','users.id')
          ->leftjoin('user_titles','users.user_title_id','=','user_titles.id')
          ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
          ->leftjoin('student_groups','simmeds.student_group_id','=','student_groups.id')
          ->leftjoin('student_subgroups','simmeds.student_subgroup_id','=','student_subgroups.id')
          ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
            ->get();

            foreach ($alldata as $row_one)
            {
                $to_csv=null;
            $to_csv[]=$row_one->simmed_date;
            $to_csv[]=$row_one->DayOfWeek;
            $to_csv[]=$row_one->time;
            $to_csv[]=$row_one->room_number;
            $to_csv[]=$row_one->leader;
            $to_csv[]=$row_one->student_subject_name;
            $to_csv[]=$row_one->student_group_name;
            $to_csv[]=$row_one->subgroup_name;
            $to_csv[]=$row_one->technician;
            $to_csv[]=$row_one->character_short;
            $to_csv[]=$row_one->simmed_alternative_title;
        
            fputcsv($fp,$to_csv,';');
            }
            fputcsv($fp,['koniec'],';');
            fputcsv($fp,[''],';');
            fclose($fp);
                  
        header('Content-type: text/csv');
        header('Content-disposition:attachment; filename="'.$filename.'"');
        readfile($filename);


            dd();

//        dump(serialize($alltmps));
    }
    
    

}
