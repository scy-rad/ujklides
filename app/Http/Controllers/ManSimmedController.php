<?php
 
namespace App\Http\Controllers;

use App\ManSimmed;
use App\Simmed;
use App\SimmedArc;
use App\SimmedTemp;
use App\TechnicianCharacter;
use App\SimmedTempPost;
use App\SimmedTempRoom;
use App\User;
use App\UserTitle;
use App\Roles;
use App\RolesHasUsers;
use App\StudentSubject;
use App\StudentGroup;
use App\StudentSubgroup;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


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

        app('debugbar')->disable();

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
                    if (($data_rows[0]=='Daty zajęć') || ($data_rows[0]==''))    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
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
                        $new_tmp['simmed_technician_character_id']=0;
                        $new_tmp['simmed_technician_character_propose_id']=$character_propose;

                       $return=$new_tmp->save();
                    }
                }   //if (count($data_rows)==8)
                elseif (substr($import_row,0,8)=='Zajęcia') //chyba że jest to nagłówek tabeli sali
                {
                    $sub_data=explode(" ",$import_row);
                    $current_room_xp_code=trim($sub_data[3]);
                    $current_room_id=Room::find_xp_room($current_room_xp_code);
                    if ($current_room_id>0)
                        $character_propose=Room::find($current_room_id)->simmed_technician_character_propose_id;
                    else
                        $character_propose=0;

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

                    //dump('import z xlsa nie poprawiony wciąż');
                $row_number++;
                $data_write=null;

                $data_write['status']='wrong';

                $data_rows=explode("\t",$import_row);
                if (count($data_rows)>10)    //import z pliku XLS powinien zawierać więcej niż 9 kolumn
                {                           // początek analizy wiersza
                    if ($data_rows[0]=='data')    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
                    {
                        $data_write['status']='head';
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
                        
                        if (User::where('name',$data_rows[8])->count()>0)
                            $new_tmp['simmed_technician_id']       = User::where('name',$data_rows[8])->first()->id;
                        if ((TechnicianCharacter::where('character_short',$data_rows[9]))->get()->count()>0)
                            $new_tmp['simmed_technician_character_id']   = TechnicianCharacter::where('character_short',$data_rows[9])->first()->id;
                        // dump($data_rows);
                        
                        $return=$new_tmp->save();
                        //dump('OK'.$return);
                    }
                }   // koniec analizy wiersza
                else
                {
                    dump('błędny wiersz ['.count($data_rows).']: ',$data_rows);
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
        $data['simmeds']=SimmedTemp::select('*')
            ->leftjoin('rooms','simmed_temps.room_id','=','rooms.id')
            ->whereNull('simmed_leader_id')
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
        app('debugbar')->disable();

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
            $student_subject_id=StudentSubject::where('student_subject_name',$no_subject->student_subject_txt)
            ->orWhere('student_subject_name_en',$no_subject->student_subject_txt)
            ->first();
            if ($student_subject_id===NULL)
                {
                $licz=SimmedTemp::where('student_subject_txt',$no_subject->student_subject_txt)->first()->id;
                $data['no_subject_list'][$licz]['row']=$licz;
                $data['no_subject_list'][$licz]['name']=$no_subject->student_subject_txt;

                if (substr($no_subject->student_subject_txt,1,6)=='ezerwa')       //jeśli tematem zajęć jest rezerwacja, to nie dodawaj jej do tematów zajęć
                    {
                    $data['no_subject_list'][$licz]['action']='pomiń';
                    }
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
            $student_group_id=StudentGroup::where('student_group_name',$no_group->student_group_txt)
                    ->orWhere('student_group_code',$no_group->student_group_txt)
                    ->first();
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
            $student_subgroup_one=StudentSubgroup::where('subgroup_name',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)->first();
            if ($student_subgroup_one===NULL)
            {
                $licz=SimmedTemp::where('student_subgroup_txt',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)->first()->id;
                $data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['row']=$licz;
                $data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['name']=$no_subgroup->student_subgroup_txt;
				$data['no_subgroup_list'][$no_subgroup->student_group_id][$licz]['action']='pomiń';//'dodaj';
				$data['missing_subgroups']++;
            }
            else
            {
                if ($student_subgroup_one->write_technician_character==0)   //jeśli grupa ma nie mieć proponowanego charakteru technika, to usuń wartość proponowaną
                {
                    SimmedTemp::where('student_subgroup_txt',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)
                        ->whereNull('student_subgroup_id')
                        ->update([
                            'student_subgroup_id' => $student_subgroup_one->id,
                            'simmed_technician_character_propose_id' => 0
                        ]);
                }
                else
                {
                    SimmedTemp::where('student_subgroup_txt',$no_subgroup->student_subgroup_txt)->where('student_group_id',$no_subgroup->student_group_id)
                        ->whereNull('student_subgroup_id')
                        ->update(['student_subgroup_id' => $student_subgroup_one->id]);
                }
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
        app('debugbar')->disable();

        // funkcja azalizuje wpisy zawarte w tabeli tymczasowej i uznaje które z nich są nowe, które zmienione, oraz których brakuje w imporcie
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

            // 0   -   nowy import
            // 1   -   dodaj wiersz do bazy
            // 2   -   aktualizuj wpis
            // 3   -   usuń wiersz z bazy
            // 4   -   pomiń wpis
        $start = microtime();
        $start = explode(' ', $start);
        $data_return = null;
        $step['step_code']=intval($request->step_code);
        $step['currrent_status_list']=[];
        $date_anal[]=SimmedTemp::all('simmed_date')->min('simmed_date');
        $date_anal[]=SimmedTemp::all('simmed_date')->max('simmed_date');
        // $date_anal[]=SimmedTemp::orderBy('simmed_date')->first()->simmed_date;
        // $date_anal[]=SimmedTemp::orderBy('simmed_date','DESC')->first()->simmed_date;
        
        switch ($step['step_code'])
        {
            case '0':
                $step['step_code']=1;
                break;
            case '1':
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
                                ->where('simmed_leader_id',$onetmp->simmed_leader_id);
                    if (SimmedTemp::whereNotNull('simmed_technician_id')->count()>0 )
                        {
                        $wynk=$wynk->where('simmed_technician_id',$onetmp->simmed_technician_id)
                                    ->where('simmed_technician_character_id',$onetmp->simmed_technician_character_id);
                        }
                    $wynk=$wynk->get();
                    if ($wynk->count()>0)
                    {
                        $onetmp->simmed_id=$wynk->first()->id;
                        $onetmp->tmp_status=4;
                        $onetmp->save();
                        //dump('ManSimmedController: znalazłem taki sam wpis');
                    }
                }
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']++;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '2':   
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                    $onetemp->check_similar($date_anal,'leader,subject,group,date,time');//zmiana tylko sali
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']++;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '3':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                    $onetemp->check_similar($date_anal,'room,leader,subject,group,date');//zmiana tylko czasu
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']++;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '4':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'room,subject,group,date,time');//zmiana tylko prowadzącego
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=5;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '5':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'room,leader,group,date,time');//zmiana tylko tematu
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=6;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '6':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'room,leader,subject,date,time');//zmiana tylko grupy
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=7;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
                break; //SD dodane, bo mimo wszystko coś się przepełniało...
            case '7':
                // if (SimmedTemp::whereNotNull('simmed_technician_id')->count()>0 )
                //     dump('ilość: ',SimmedTemp::whereNotNull('simmed_technician_id')->count());
                // else
                //     {
                //     dump('ilość 72: ',SimmedTemp::whereNotNull('simmed_technician_id')->count());
                    $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                    foreach ($alltmps as $onetemp)
                            $onetemp->check_similar($date_anal,'room,leader,subject,group,date,time');//zmiana tylko technika
                    // }
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=8;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '8':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'room,leader,date,time');//zmiana grupy i tematu
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=9;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '9':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'leader,subject,group');//zmiana sali i czasu
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=10;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '10':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'room,date,time');//ta sama sala i czas
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=11;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '11':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'leader,subject,group,deleted');//zmiana sali i czasu w usuniętych 
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=12;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '12':
                $alltmps=SimmedTemp::where('simmed_id','=',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar($date_anal,'subject,group,deleted');//zmiana prowadzącego, sali i czasu w usuniętych 
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=13;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '13':
                $Characters_to_propose=SimmedTemp::where('simmed_technician_character_id',0)
                    ->where('simmed_technician_character_propose_id','>',0)
                    ->where('simmed_id',0)
                    ->update(['simmed_technician_character_id' => DB::raw("`simmed_technician_character_propose_id`")]);

                $Character_free_code=TechnicianCharacter::where('character_short','=','free')
                    ->get()->first()->id;

                $Characters_to_free=SimmedTemp::where('simmed_technician_character_id',0)
                    ->where('simmed_technician_character_propose_id','=',0)
                    ->where('simmed_id',0)
                    ->update(['simmed_technician_character_id' => $Character_free_code]);

                // $Character_look_code=TechnicianCharacter::where('character_short','=','look')
                //     ->get()->first()->id;

                // $Characters_to_look=SimmedTemp::where('simmed_technician_character_id',0)
                //     ->update(['simmed_technician_character_id' => $Character_look_code]);

                
                // ->orWhere('simmed_time_begin', '!=', DB::raw("`send_simmed_time_begin`"))

                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=14;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '14':
                $IDSy=SimmedTemp::pluck('simmed_id')->toArray();
                $for_delete=Simmed::where('simmed_status','<',4)->whereNotIn('id',$IDSy)->whereBetween('simmed_date',$date_anal)->get();

                if ($for_delete->count()>0)
                {
                    foreach ($for_delete as $fordelete_row)
                        {
                            $fordelete_row->tmp_status = 3;
                            SimmedTemp::add_row($fordelete_row);
                        }
                }
                $koniec = microtime();
                $koniec = explode(' ', $koniec);
                $step['step_code']=15;
                if (intval(($koniec[0]+$koniec[1])-($start[0]+$start[1]))>40)
                    break;
            case '100':
                //$currrent_status_list=SimmedTemp::select('tmp_status')->distinct()->get();
                $step['currrent_status_list']=DB::table('simmed_temps')
                    ->select('tmp_status', DB::raw('count(*) as total'))
                    ->groupBy('tmp_status')
                    ->get();

                $data_return=DB::table('simmed_temps')
                    ->select('simmed_trap','simmed_date', \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'), 'room_number', 
                    \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as leader'), 
                    'student_subject_name', 'student_group_name', 'subgroup_name',
                    'technicians.name as technician_name', 
                    'character_short',
                    'simmed_alternative_title',
                    'tmp_status',
                    'simmed_merge',
                    'simmed_id',
                    'room_id',
                    'leaders.id as leader_id',
                    'simmed_technician_id',
                    'simmed_technician_character_id',
                        'student_subject_id',
                        'simmed_temps.student_group_id',
                        'student_subgroup_id',
                    'simmed_temps.id',
                    'simmed_time_begin',
                    'simmed_time_end'
                    )
                    ->leftjoin('rooms','simmed_temps.room_id','=','rooms.id')
                    ->leftjoin('users as leaders','simmed_temps.simmed_leader_id','=','leaders.id')
                    ->leftjoin('users as technicians','simmed_temps.simmed_technician_id','=','technicians.id')
                    ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
                    ->leftjoin('student_subjects','simmed_temps.student_subject_id','=','student_subjects.id')
                    ->leftjoin('student_groups','simmed_temps.student_group_id','=','student_groups.id')
                    ->leftjoin('student_subgroups','simmed_temps.student_subgroup_id','=','student_subgroups.id')
                    ->leftjoin('technician_characters','simmed_temps.simmed_technician_character_id','=','technician_characters.id')
            
                    ->orderByDesc('tmp_status')
                    ->orderBy('simmed_merge')
                    ->orderByDesc('simmed_id')
                    ->orderBy('student_group_name')
                    ->orderBy('subgroup_name')
                    ->orderBy('student_subject_name')
                    ->orderBy('simmed_date')
                    ->orderBy('time')
                    
                    ->where('tmp_status',-1)
                    ;

                    foreach ($step['currrent_status_list'] as $tmp_row) //ta pętla sprawdza, jakie checkboxy zostały zaznaczone przed wywołaniem funkcji
                        {
                        $code='code'.$tmp_row->tmp_status;
                        if (isset($request->$code))
                            {
                            $tmp_row->check=' checked="checked"';       // i przygotowuje zmienną do łatwej obsługi zaznaczenia aktywnych checkboxów
                            $data_return=$data_return->OrWhere('tmp_status',$request->$code);
                            }
                        else
                            $tmp_row->check='';
                        }
                    $data_return=$data_return->orWhere('tmp_status',-2);    //nie wiem czemu musi yć ten wpis, bo bez niego nie dziala ostatni warunek orWhere ... :()
                    

                    //dump($step['currrent_status_list']);                    // ->limit(100)
                    // ->offset(0)
                    $data_return=$data_return->where('room_id',0);
                    $data_return=$data_return->get();

                $step['step_code']=101;
    
        }   //endof switch
                        
        $koniec = microtime();
        $koniec = explode(' ', $koniec);
        $roznica = ($koniec[0]+$koniec[1])-($start[0]+$start[1]);
        dump('czas wykonywania do kroku '.$request->step_code.': '.$roznica);
        dump((intval(($koniec[0]+$koniec[1])-($start[0]+$start[1])))>40);
 
        return view('mansimmeds.impanalyze', compact('data_return'),$step);
    }   // end of public function impanalyze




    public function ajaxchangestatus(Request $request) 
    {
        $status = DB::table('simmed_temps')
       ->where('id', $request->id)
       ->update(['tmp_status' => $request->status_id]);
    return json_encode(array('result'=>true, 'id'=> $request->id, 'status' => $request->status_id, 'tescik' =>'ajaxchangestatus', 'statusx'=> $status));
    }


    public function markimport(Request $request)
    {
        //funkcja utawia status nowych danych na "dodaj"
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        SimmedTemp::where('tmp_status','=','0')->where('simmed_id', '=', '0')->update(['tmp_status' => 1]); //ustaw status pozostałych na zaimportuj

            $data_return=DB::table('simmed_temps')
            ->select('simmed_trap','simmed_date', \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'), 'room_number', 
            \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as leader'), 
            'student_subject_name', 'student_group_name', 'subgroup_name',
            'technicians.name as technician_name', 
            'character_short',
            'simmed_alternative_title',
            'tmp_status',
            'simmed_merge',
            'simmed_id',
            'room_id',
            'leaders.id as leader_id',
            'simmed_technician_id',
            'simmed_technician_character_id',
                'student_subject_id',
                'simmed_temps.student_group_id',
                'student_subgroup_id',
            'simmed_temps.id',
            'simmed_time_begin',
            'simmed_time_end'
            )
            ->leftjoin('rooms','simmed_temps.room_id','=','rooms.id')
            ->leftjoin('users as leaders','simmed_temps.simmed_leader_id','=','leaders.id')
            ->leftjoin('users as technicians','simmed_temps.simmed_technician_id','=','technicians.id')
            ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
            ->leftjoin('student_subjects','simmed_temps.student_subject_id','=','student_subjects.id')
            ->leftjoin('student_groups','simmed_temps.student_group_id','=','student_groups.id')
            ->leftjoin('student_subgroups','simmed_temps.student_subgroup_id','=','student_subgroups.id')
            ->leftjoin('technician_characters','simmed_temps.simmed_technician_character_id','=','technician_characters.id')
    
            ->orderByDesc('tmp_status')
            ->orderBy('simmed_merge')
            ->orderByDesc('simmed_id')

            ->orderBy('simmed_date')
            ->orderBy('simmed_time_begin')
            
            ->orderBy('student_group_id')
            ->orderBy('student_subgroup_id')
            ->orderBy('student_subject_id')

            ->get()
            ;

        $step['step_code']='101';
        return view('mansimmeds.impanalyze', compact('data_return'),$step);
    }   // end of public function markimport




    public function clearimport(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji clearimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        echo 'czyszczę...';

        SimmedTemp::truncate();

        $data_return=[];
        $step['step_code']='101';
        return view('mansimmeds.impanalyze', compact('data_return'),$step);
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
                $edited_row=SiMmed::find($data_one->simmed_id);

                $arc_row=new SimmedArc();
                $arc_row->simmed_date						= $edited_row->simmed_date;
                $arc_row->simmed_time_begin				    = $edited_row->simmed_time_begin;
                $arc_row->simmed_time_end					= $edited_row->simmed_time_end;
                $arc_row->simmed_type_id					= $edited_row->simmed_type_id;
                $arc_row->student_subject_id	        	= $edited_row->student_subject_id;
                $arc_row->student_group_id    			    = $edited_row->student_group_id;
                $arc_row->student_subgroup_id				= $edited_row->student_subgroup_id;
                $arc_row->room_id     					    = $edited_row->room_id;
                $arc_row->simmed_leader_id	    		    = $edited_row->simmed_leader_id;
                $arc_row->simmed_technician_id    		    = $edited_row->simmed_technician_id;
                $arc_row->simmed_technician_character_id    = $edited_row->simmed_technician_character_id;
                $arc_row->simmed_alternative_title		    = $edited_row->simmed_alternative_title;
                $arc_row->simmed_status 					= $edited_row->simmed_status;
                $arc_row->simmed_status2					= $edited_row->simmed_status2;
                $arc_row->created_at    					= $edited_row->created_at;
                $arc_row->updated_at    					= $edited_row->updated_at;
                $arc_row->change_code                       = $data_one->tmp_status;
                $arc_row->simmed_id                         = $edited_row->id;
                $arc_row->user_id                           = $edited_row->user_id;
                $arc_row->save();
                //dump('JEST + ARCHIWUM',$data_one,$arc_row);
            }
            else
            {
                $edited_row=new Simmed();
                $edited_row->simmed_status=1;
                //dump('Tu nie będzie ARCHIWUM',$data_one);
            }
            
            if ($data_one->simmed_status == 0)
            {
                $data_one->simmed_status = $edited_row->simmed_status;
            }


            if ( ($edited_row->simmed_technician_id*1) != ($data_one->simmed_technician_id*1) )
            {
                $history_table = new \App\SimmedArcTechnician();
                $history_table->simmed_id = $data_one->id;
                $history_table->technician_id = $data_one->simmed_technician_id*1;
                $history_table->user_id = Auth::user()->id;
                $history_table->save();
            }

            $edited_row->simmed_date						= $data_one->simmed_date;
            $edited_row->simmed_time_begin				    = $data_one->simmed_time_begin;
            $edited_row->simmed_time_end					= $data_one->simmed_time_end;
            $edited_row->simmed_type_id					    = $data_one->simmed_type_id;
            $edited_row->student_subject_id	        	    = $data_one->student_subject_id;
            $edited_row->student_group_id    			    = $data_one->student_group_id;
            $edited_row->student_subgroup_id				= $data_one->student_subgroup_id;
            $edited_row->room_id     					    = $data_one->room_id;
            $edited_row->simmed_leader_id	    		    = $data_one->simmed_leader_id;
            // if ($data_one->simmed_technician_id==0) 
            //     $edited_row->simmed_technician_id    		= NULL;
            // else
                $edited_row->simmed_technician_id    		    = $data_one->simmed_technician_id;

            //       $edited_row->simmed_alternative_title = 'próba nulla';

            if ($data_one->simmed_technician_character_id==0)
                $edited_row->simmed_technician_character_id    = NULL;
            else
                $edited_row->simmed_technician_character_id    = $data_one->simmed_technician_character_id;

            
            $edited_row->simmed_alternative_title		    = $data_one->simmed_alternative_title;
            if ($data_one->simmed_alternative_title!='')
                $edited_row->simmed_alternative_title=$data_one->simmed_alternative_title;
            else
            {
                $alt_txt='';
                if (strpos($data_one->import_row,'ezerwac',0)>0)
                $alt_txt='[Rezerwacja sali]';

                if (is_null($data_one->simmed_leader_id))
                    $alt_txt.=$data_one->simmed_leader_txt.', ';
                if (is_null($data_one->student_subject_id))
                    $alt_txt.=$data_one->student_subject_txt.', ';
                if (is_null($data_one->student_group_id))
                    $alt_txt.=$data_one->student_group_txt.' ';
                $edited_row->simmed_alternative_title=trim($alt_txt);
            }
            $edited_row->simmed_status = $data_one->simmed_status;
            $edited_row['simmed_status2']					= 1;
            
            $edited_row->user_id                            = Auth::user()->id;

            $ret=$edited_row->save();
            $data_one->delete();
        }

        $data=null;
        $data['step']=$request->step;

        $data_all=SimmedTemp::where('tmp_status',1)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                {
                    move_simmed($data_one);
                }
                
        $data_all=SimmedTemp::where('tmp_status',2)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
            {
                move_simmed($data_one);
                //dump('updateX',$data_one);
            }

        $data_all=SimmedTemp::where('tmp_status',3)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
            {
                $data_one->simmed_status=4;
                move_simmed($data_one);
                //dump($data_one);
//                 dd('import_append removeX',$data_one);
            }

        $data_all=SimmedTemp::where('tmp_status',9)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('import_append backX',$data_one);

        SimmedTemp::where('tmp_status',4)->delete(); //usuń pominięte


        $data_return=[];
        $step['step_code']='101';
        return view('mansimmeds.impanalyze', compact('data_return'),$step);
        //return view('mansimmeds.import')->with($data);
    }   //end of public function import_append









    public function doimport(Request $request)
    {
        //import danych już po przejrzeniu i ustaleniu, które z nich mają zostać dodane/nadpisane/usunięte??/itp
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji doimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        echo 'importuję...';
        dd('funkcja doimport początek');

        foreach (SimmedTemp::where('tmp_status',1)->get() as $doImport)
        {
            dd('funkcja doimport status 1 + bez ARCHIWUM');

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
                dd('funkcja doimport ',$all_txt);
            }
			$return=$zmEQ->save();

            if ($return==1)
                SimmedTemp::find($doImport->id)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',2)->where('simmed_id','=',0)->get() as $doImport)
        {
            dd('funkcja doimport status 2 + ARCHIWUM');
            $id_to_change=SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->where('simmed_id','>',0)->get()->first()->simmed_id;
            
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
                dd('funkcja doimport ',$all_txt);
            }
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',9)->get() as $doImport)
        {
            dd('funkcja doimport status 9 + ARCHIWUM');
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
                dd('funkcja doimport ',$all_txt);
            }
            $zmEQ->simmed_status=1;
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
        }

        foreach (SimmedTemp::where('tmp_status',3)->where('simmed_id','>',0)->get() as $doImport)
        {
            dd('funkcja doimport status 3 + ARCHIWUM');
            $zmEQ = Simmed::where('id',$doImport->simmed_id)->first();
            $zmEQ->simmed_status=4; //zmiana statutu na 4 (czyli usunięty)
            $return=$zmEQ->save();
            //$return=$zmEQ->delete();

            if ($return==1)
                $doImport->delete();
        }

        // if (SimmedTemp::all()->count()==0)
        //     SimmedTempRoom::where('import_status',0)->update(['import_status'=>'1']);

        $data['step_code']='anything';
        $data['import_data']=SimmedTemp::all();
        return view('mansimmeds.impanalyze')->with($data);
    }   // end of public function doimport






public function sendMail(Request $request)
    {
    // choose users for mail sending
    // if choice is monthinfo - mail is for technicians and coordinators
    // if choice is threedaysinfo - mail is for technicians only
    

    $roles_technicians_id=Roles::where('roles_code', 'technicians')
        ->first()->id;
    $roles_technicians=RolesHasUsers::where('roles_has_users_roles_id',$roles_technicians_id)
        ->pluck('roles_has_users_users_id')
        ->toArray();
    $technician_users = User::whereIn('id',$roles_technicians)
            ->where('user_status','=',1)
            ->where('simmed_notify','=',1)
            ->get();

    $roles_coordinators_id=Roles::where('roles_code', 'coordinators')
        ->first()->id;
    $roles_coordinators=RolesHasUsers::where('roles_has_users_roles_id',$roles_coordinators_id)
        ->pluck('roles_has_users_users_id')
        ->toArray();
    $coordinator_users = User::whereIn('id',$roles_coordinators)
            ->where('user_status','=',1)
            ->where('simmed_notify','=',1)
            ->get();

    //prepare SQL question
    
    $user_simmeds_prepare=DB::table('simmeds')
        ->select('simmed_date',
            \DB::raw('dayname(simmed_date) as DayOfWeek'),
            \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'), 
            \DB::raw('concat(substr(send_simmed_time_begin,1,5),"-",substr(send_simmed_time_end,1,5)) as send_time'), 
            'rooms.room_number', 
            \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as leader'),
            \DB::raw('concat(send_user_titles.user_title_short," ",send_leaders.lastname," ",send_leaders.firstname) as send_leader'),  
            'student_subject_name', 'student_group_name', 'subgroup_name',
            'technicians.name as technician_name', 
            'technician_characters.character_short',
            'technician_characters.character_name',
            'simmed_alternative_title',
            'room_id',
            'leaders.id as leader_id',
            'simmed_technician_id',
            'simmed_technician_character_id',
                'student_subject_id',
                'simmeds.student_group_id',
                'student_subgroup_id',
                'student_group_code',
            'simmed_time_begin',
            'simmed_time_end',
            'simmed_type_id',
            'simmed_leader_id',
            'simmed_status',
            'simmed_status2',

            'send_simmed_date',
            'send_simmed_time_begin',
            'send_simmed_time_end',
            'send_simmed_type_id',
            'send_student_subject_id',
            'send_student_group_id',
            'send_student_subgroup_id',
            'send_room_id',
            'send_simmed_leader_id',
            'send_simmed_technician_id',
            'send_simmed_technician_character_id',
            'send_simmed_status',
            'send_simmed_status2',
           
            'send_technicians.name as send_technician_name',
            'send_technician_characters.character_name as send_character_name',
            'send_rooms.room_number as send_room_number',
           
            )
        ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
        ->leftjoin('users as leaders','simmeds.simmed_leader_id','=','leaders.id')
        ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','technicians.id')
        ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
        ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
        ->leftjoin('student_groups','simmeds.student_group_id','=','student_groups.id')
        ->leftjoin('student_subgroups','simmeds.student_subgroup_id','=','student_subgroups.id')
        ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')

        ->leftjoin('rooms as send_rooms','simmeds.send_room_id','=','send_rooms.id')
        ->leftjoin('users as send_technicians','simmeds.send_simmed_technician_id','=','send_technicians.id')
        ->leftjoin('users as send_leaders','simmeds.send_simmed_leader_id','=','send_leaders.id')
        ->leftjoin('user_titles as send_user_titles','send_leaders.user_title_id','=','send_user_titles.id')
        ->leftjoin('technician_characters as send_technician_characters','simmeds.send_simmed_technician_character_id','=','send_technician_characters.id')
        

        ->orderBy('simmed_date')
        ->orderBy('time')
        ->orderBy('room_number');
    


        // calculate start and end date for simmeds to mail sending
        // creating mail subject, body etc.
        $msgBody='';
        $BigTable[1]['table']=null;
        $BigTable[2]['table']=null;

        // first switch for prepare data for email
        switch ($request->mailtype)
        {
            case 'monthinfo':
                if (date('d')<15)
                    $date_between[]=date('Y-m-01');
                else
                    $date_between[]=date('Y-m-01', strtotime(date('Y-m-01')."+1 month"));
        
                $date_between[]=date('Y-m-t', strtotime($date_between[0]));
        
                //if choice is monthinfo, do SQL query now, and don't do it in loop
                $BigTable[1]['head']='Lista symulacji w okresie: '.$date_between[0].' - '.$date_between[1];
                $BigTable[1]['table']=$user_simmeds_prepare
                    ->whereBetween('simmed_date',$date_between)
                    ->get();

                $msgBodyPrep='<h2>Oto lista wszystkich symulacji w okresie od '.$date_between[0].' do '.$date_between[1].'</h2>';
                $msgTitle['subject']='[SIMinfo] Informacja o miesięcznych symulacjach';
                $mail_data_address['subject_email']='[SIMinfo] terminy symulacji: '.$date_between[0].' - '.$date_between[1];

                break; 


            case 'threedaysinfo':
                $date_between[]=date('Y-m-d', strtotime('+1 days'));
                if (date('N',strtotime($date_between[0]))==7) 
                    $addDays=3;
                elseif (date('N',strtotime($date_between[0]))>3) 
                    $addDays=4;
                else
                    $addDays=2;

                $date_between[]=date('Y-m-d', strtotime($date_between[0]."+$addDays days"));

                $msgBodyPrep='<h2>Oto lista Twoich symulacji na najbliższe dni (od '.$date_between[0].' do '.$date_between[1].')</h2>';
                $msgBodyPrep.='<italic>(informacja ...)</italic>';
                $msgTitle['subject']='[SIMinfo] Informacja o bieżących symulacjach';
                $mail_data_address['subject_email']='[SIMinfo] najbliższe symulacje: '.$date_between[0];

                break; 


            case 'simchanges':

                $date_between[]='2020-01-01';
                $date_between[]='2030-01-01';

                $msgBodyPrep='<h2>Oto lista zmian w symulacjach </h2>';
                $msgBodyPrep.='<italic>(informacja o zmianach...)</italic>';
                $msgTitle['subject']='[SIMinfo] informacje o zmianach w symulacjach';
                $mail_data_address['subject_email']='[SIMinfo] zmiany w symulacjach: '.date('Y-m-d H:i');

                $user_simmeds_prepare_changes=clone $user_simmeds_prepare;
                $user_simmeds_prepare_changes
                    ->where(function ($query) {
                        $query->where('simmed_date', '!=', DB::raw("`send_simmed_date`"))
                        ->orWhere('simmed_time_begin', '!=', DB::raw("`send_simmed_time_begin`"))
                        ->orWhere('simmed_time_end', '!=', DB::raw("`send_simmed_time_end`"))
                        ->orWhere('simmed_type_id', '!=', DB::raw("`send_simmed_type_id`"))
                        ->orWhere('student_subject_id', '!=', DB::raw("`send_student_subject_id`"))
                      //  ->orWhere('student_group_id', '!=', DB::raw("`send_student_group_id`"))
                        ->orWhere('student_subgroup_id', '!=', DB::raw("`send_student_subgroup_id`"))
                        ->orWhere('room_id', '!=', DB::raw("`send_room_id`"))
                        ->orWhere('simmed_leader_id', '!=', DB::raw("`send_simmed_leader_id`"))
                        ->orWhere('simmed_technician_id', '!=', DB::raw("`send_simmed_technician_id`"))
                        ->orWhere('simmed_technician_character_id', '!=', DB::raw("`send_simmed_technician_character_id`"))
                        ->orWhere('simmed_status', '!=', DB::raw("`send_simmed_status`"))
                        ->orWhere('simmed_status2', '!=', DB::raw("`send_simmed_status2`"))
                        ;
                    });

                $coordinators_data=clone $user_simmeds_prepare_changes;
                
                $coordinators_data=$coordinators_data
                    ->whereBetween('simmed_date',$date_between)
                    ->get();

    

        }



        
        function mail_send_now($user, $msgTitle, $msgBody, $BigTable)
        {

            $ret['user']=$user->firstname.' '.$user->lastname;


            if (
                (!is_array($BigTable))
                || (count($BigTable)==0)
                ) 
            {
                $ret['code']=100;
                return $ret; //100 - nic do wysłania
            }

            if ( (is_null($BigTable[1]['table'])) && (is_null($BigTable[2]['table'])) )
            {
                $ret['code']=100;
                return $ret; //100 - nic do wysłania
            }
            
            // if ($user->id!=1)
            // { 
            //     $ret['code']=128;
            //     return $ret; //128 - blokada administratora
            // }

            //http://127.0.0.1:8000/send-mail
            $mail_data = [
                'title'=>$msgTitle['subject'],
                'name'=>$user->full_name(),
                'msgBody'=>$msgBody,
                'BigTable'=>$BigTable
            ];

            $mail_data_address['email']=$user->email;
            //$mail_data_address['email']='sebastian@scyzoryk.info';
            $mail_data_address['name']=$user->firstname.' '.$user->lastname;
            $mail_data_address['from_email']='technicy@wcsm.pl';
            $mail_data_address['from_name']='Pegasus CSM UJK';
            $mail_data_address['subject_email']=$msgTitle['subject'];

            // echo '<hr>'.$msgBody;
            // dump($mail_data_address);
            // dump($user->id.': '.$user->name,$BigTable);

            $zwrocik=Mail::send('mansimmeds.mailsimmed',$mail_data,function($mail) use ($mail_data_address)
                    {
                        $mail->from($mail_data_address['from_email'],$mail_data_address['from_name']);
                        $mail->to($mail_data_address['email'],$mail_data_address['name']);
                        $mail->subject($mail_data_address['subject_email']);
                    }
                );
            $ret['code']=1;

            // echo'<h3>No Mail Sending to '.$mail_data_address['email'].'</h3>';
            // echo $msgBody;
            // echo '<br>';
            // print_r($mail_data_address);
            // dump($mail_data);
     // dd('mail_send_one');
            //$data['message_body'].='<li><strong>'.$user->firstname.' '.$user->lastname.'</strong> '.$user->email.'</li>';

            // dump($zwrocik);
            return $ret;
        }

        // second switch for email sending

        $zwrot=[];

        switch ($request->mailtype)
        {
            case 'monthinfo':
                foreach ($technician_users as $user)
                {
                    $msgBody='Ogólna informacja dla techników i koordynatorów (technik: <strong>'.$user->full_name().'</strong>)<hr><br>'.$msgBodyPrep;
                    
                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);
                }
                foreach ($coordinator_users as $user)
                {
                    $msgBody='Ogólna informacja dla techników i koordynatorów (koordynator: <strong>'.$user->full_name().'</strong>)<hr><br>'.$msgBodyPrep;
     
                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);
                }
                break;

            case 'threedaysinfo':
                foreach ($technician_users as $user)
                {
                    $msgBody='Codzienna informacja dla technika: <strong>'.$user->full_name().'</strong><hr><br>'.$msgBodyPrep;
                    
                    $user_simmeds=clone $user_simmeds_prepare;
                    $user_simmeds=$user_simmeds
                        ->where('simmed_technician_id',$user->id)
                        ->whereBetween('simmed_date',$date_between)
                        ->get();

                    if ($user_simmeds->count()>0)
                        {
                        $BigTable[1]['head']='wykaz symulacji technika: <strong>'.$user->full_name().'</strong>';
                        $BigTable[1]['table']=$user_simmeds;
                        }
                    else
                        $BigTable[1]['table']=null;


                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);
                }
                break;

            case 'simchanges':

                $look_characters=TechnicianCharacter::where('character_short','<>','free')
                    ->pluck('id')
                    ->toArray();

                $tmp_table=clone $user_simmeds_prepare;
                $tmp_table=$tmp_table
                        ->where(function ($query) use ($look_characters) {
                            $query->whereNull('simmed_technician_id')
                                ->whereIn('simmed_technician_character_id',$look_characters);
                            })                    
                        ->whereBetween('simmed_date',$date_between)
                        ->get();

                if ($tmp_table->count()>0)
                {
                    $BigTable[2]['head']='Symulacje, które nia mają przypisanego technika, a powinny...:';
                    $BigTable[2]['table']=$tmp_table;
                }


                foreach ($technician_users as $user)
                {
                    $msgBody='<h1>Informacja o zmianach w symulacjach i o symulacjach do których należy przypisać technika </h1><hr><br>'.$msgBodyPrep;
     
                    $tmp_table=clone $user_simmeds_prepare_changes;
                    $tmp_table=$tmp_table
                        ->where(function ($query) use ($user) {
                            $query->where('simmed_technician_id', $user->id)
                                ->orWhere('send_simmed_technician_id', $user->id);
                            })
                        ->whereBetween('simmed_date',$date_between)
                        ->get();
                    
                    if ($tmp_table->count()>0)
                    {
                        $BigTable[1]['head']='zmiany w symulacjach przypisanych do technika: <strong>'.$user->full_name().'</strong>';
                        $BigTable[1]['table']=$tmp_table;
                    }
                    else
                        $BigTable[1]['table']=null;
     
                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);

                }


                $BigTable=null;
                $BigTable[1]['table']=null;
                $BigTable[2]['table']=null;

                $tmp_table=clone $user_simmeds_prepare_changes;
                $tmp_table=$tmp_table
                    ->where('send_simmed_date', '<>', '2022-01-01')
                    ->whereBetween('simmed_date',$date_between)
                    ->get();
                if ($tmp_table->count()>0)
                    {
                        $BigTable[1]['head']='zmiany w symulacjach';
                        $BigTable[1]['table']=$tmp_table;
                    }

                $tmp_table=clone $user_simmeds_prepare_changes;
                $tmp_table=$tmp_table
                    ->where('send_simmed_date', '=', '2022-01-01')
                    ->whereBetween('simmed_date',$date_between)
                    ->get();
                if ($tmp_table->count()>0)
                    {
                        $BigTable[2]['head']='nowe symulacje';
                        $BigTable[2]['table']=$tmp_table;
                    }
    
    
                foreach ($coordinator_users as $user)
                {
                    $msgBody='Informacja o zmianach w symulacjach dla koordynatora: <strong>'.$user->full_name().'</strong><br><hr><br>'.$msgBodyPrep;
     
                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);
                }


                foreach ($technician_users as $user)
                {
                    $msgBody='<h1 style="background:yellow">A taką informację otrzymują koordynatorzy:<h1> <br><hr><br>'.$msgBodyPrep;
                    $msgTitle['subject']='[SIMinfo] informacje dla koordynatora o zmianach w symulacjach';
                    $zwrot[]=mail_send_now($user, $msgTitle, $msgBody, $BigTable);
                }

                break;
        }


        $data['message_body']='wysłano maile do <ul>';
        foreach ($zwrot as $zwrot_one)
        {
            switch ($zwrot_one['code'])
            {
                case 1:
                    $data['message_body'].='<li>wysłano <strong>'.$zwrot_one['user'].'</strong> </li>';
                    break;
                case 100:
                    $data['message_body'].='<li>nie wysłano <strong>'.$zwrot_one['user'].'</strong> (brak danych) </li>';
                    break;
                default:
                    $data['message_body'].='<li>nie wysłano <strong>'.$zwrot_one['user'].'</strong> (przyczyna nieznana) </li>';
                }   
        }
        $data['message_body'].='</ul>';
        
    
    //dump('dopisałem poniżej X żeby nie aktualizował zmienionych danych wysyłki');
    //jeszcze jedna pętla, żeby zaktualizować info o wysłanych danych

    if ($request->mailtype=='simchanges')
    {
            $update_simmeds=Simmed::where(function ($query) {
                    $query->Where('simmed_date', '!=', DB::raw("`send_simmed_date`"))
                    ->orWhere('simmed_time_begin', '!=', DB::raw("`send_simmed_time_begin`"))
                    ->orWhere('simmed_time_end', '!=', DB::raw("`send_simmed_time_end`"))
                    ->orWhere('simmed_type_id', '!=', DB::raw("`send_simmed_type_id`"))
                    ->orWhere('student_subject_id', '!=', DB::raw("`send_student_subject_id`"))
                    ->orWhere('student_group_id', '!=', DB::raw("`send_student_group_id`"))
                    ->orWhere('student_subgroup_id', '!=', DB::raw("`send_student_subgroup_id`"))
                    ->orWhere('room_id', '!=', DB::raw("`send_room_id`"))
                    ->orWhere('simmed_leader_id', '!=', DB::raw("`send_simmed_leader_id`"))
                    ->orWhere('simmed_technician_id', '!=', DB::raw("`send_simmed_technician_id`"))
                    ->orWhere('simmed_technician_character_id', '!=', DB::raw("`send_simmed_technician_character_id`"))
                    ->orWhere('simmed_status', '!=', DB::raw("`send_simmed_status`"))
                    ->orWhere('simmed_status2', '!=', DB::raw("`send_simmed_status2`"));
                })
                ->whereBetween('simmed_date',$date_between)
                ->update([
                    "send_simmed_date" => DB::raw("`simmed_date`"),
                    "send_simmed_time_begin" => DB::raw("`simmed_time_begin`"),
                    "send_simmed_time_end" => DB::raw("`simmed_time_end`"),
                    "send_simmed_type_id" => DB::raw("`simmed_type_id`"),
                    "send_student_subject_id" => DB::raw("ifNull(`student_subject_id`,0)"),
                    "send_student_group_id" => DB::raw("ifNull(`student_group_id`,0)"),
                    "send_student_subgroup_id" => DB::raw("ifNull(`student_subgroup_id`,0)"),
                    "send_room_id" => DB::raw("`room_id`"),
                    "send_simmed_leader_id" => DB::raw("ifNull(`simmed_leader_id`,0)"),
                    "send_simmed_technician_id" => DB::raw("ifNull(`simmed_technician_id`,0)"),
                    "send_simmed_technician_character_id" => DB::raw("ifNull(`simmed_technician_character_id`,0)"),
                    "send_simmed_status" => DB::raw("`simmed_status`"),
                    "send_simmed_status2" => DB::raw("`simmed_status2`"),
                ]);
    }
    

    
    $data['message_show']=TRUE;
    return view('mansimmeds.index')->with($data);
//    return 'Wysłano maila';
}


public function changes(Request $request)
    {
    //prepare SQL question
    
    $user_simmeds_prepare=DB::table('simmeds')
        ->select('simmed_date',
            \DB::raw('dayname(simmed_date) as DayOfWeek'),
            \DB::raw('concat(substr(simmed_time_begin,1,5),"-",substr(simmed_time_end,1,5)) as time'), 
            \DB::raw('concat(substr(send_simmed_time_begin,1,5),"-",substr(send_simmed_time_end,1,5)) as send_time'), 
            'rooms.room_number', 
            \DB::raw('concat(user_titles.user_title_short," ",leaders.lastname," ",leaders.firstname) as leader'),
            \DB::raw('concat(send_user_titles.user_title_short," ",send_leaders.lastname," ",send_leaders.firstname) as send_leader'),  
            'student_subject_name', 'student_group_name', 'subgroup_name',
            'technicians.name as technician_name', 
            'technician_characters.character_short',
            'technician_characters.character_name',
            'simmed_alternative_title',
            'room_id',
            'leaders.id as leader_id',
            'simmed_technician_id',
            'simmed_technician_character_id',
                'student_subject_id',
                'simmeds.student_group_id',
                'student_subgroup_id',
                'student_group_code',
            'simmed_time_begin',
            'simmed_time_end',
            'simmed_type_id',
            'simmed_leader_id',
            'simmed_status',
            'simmed_status2',

            'send_simmed_date',
            'send_simmed_time_begin',
            'send_simmed_time_end',
            'send_simmed_type_id',
            'send_student_subject_id',
            'send_student_group_id',
            'send_student_subgroup_id',
            'send_room_id',
            'send_simmed_leader_id',
            'send_simmed_technician_id',
            'send_simmed_technician_character_id',
            'send_simmed_status',
            'send_simmed_status2',
           
            'send_technicians.name as send_technician_name',
            'send_technician_characters.character_name as send_character_name',
            'send_rooms.room_number as send_room_number',
           
            )
        ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
        ->leftjoin('users as leaders','simmeds.simmed_leader_id','=','leaders.id')
        ->leftjoin('users as technicians','simmeds.simmed_technician_id','=','technicians.id')
        ->leftjoin('user_titles','leaders.user_title_id','=','user_titles.id')
        ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
        ->leftjoin('student_groups','simmeds.student_group_id','=','student_groups.id')
        ->leftjoin('student_subgroups','simmeds.student_subgroup_id','=','student_subgroups.id')
        ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')

        ->leftjoin('rooms as send_rooms','simmeds.send_room_id','=','send_rooms.id')
        ->leftjoin('users as send_technicians','simmeds.send_simmed_technician_id','=','send_technicians.id')
        ->leftjoin('users as send_leaders','simmeds.send_simmed_leader_id','=','send_leaders.id')
        ->leftjoin('user_titles as send_user_titles','send_leaders.user_title_id','=','send_user_titles.id')
        ->leftjoin('technician_characters as send_technician_characters','simmeds.send_simmed_technician_character_id','=','send_technician_characters.id')
        

        ->orderBy('simmed_date')
        ->orderBy('time')
        ->orderBy('room_number');
    

        $msgBody='';
        $BigTable[1]['table']=null;
        $BigTable[2]['table']=null;


                $msgBodyPrep='<h2>Oto lista zmian w symulacjach </h2>';
                $msgBodyPrep.='<italic>(informacja o zmianach...)</italic>';
                $msgTitle['subject']='[SIMinfo] informacje o zmianach w symulacjach';
                $mail_data_address['subject_email']='[SIMinfo] zmiany w symulacjach: '.date('Y-m-d H:i');

                $simmeds_changes=clone $user_simmeds_prepare;
                $simmeds_changes=$simmeds_changes
                    ->where(function ($query) {
                        $query->where('simmed_date', '!=', DB::raw("`send_simmed_date`"))
                        ->orWhere('simmed_time_begin', '!=', DB::raw("`send_simmed_time_begin`"))
                        ->orWhere('simmed_time_end', '!=', DB::raw("`send_simmed_time_end`"))
                        ->orWhere('simmed_type_id', '!=', DB::raw("`send_simmed_type_id`"))
                        ->orWhere('student_subject_id', '!=', DB::raw("`send_student_subject_id`"))
                      //  ->orWhere('student_group_id', '!=', DB::raw("`send_student_group_id`"))
                        ->orWhere('student_subgroup_id', '!=', DB::raw("`send_student_subgroup_id`"))
                        ->orWhere('room_id', '!=', DB::raw("`send_room_id`"))
                        ->orWhere('simmed_leader_id', '!=', DB::raw("`send_simmed_leader_id`"))
                        ->orWhere('simmed_technician_id', '!=', DB::raw("`send_simmed_technician_id`"))
                        ->orWhere('simmed_technician_character_id', '!=', DB::raw("`send_simmed_technician_character_id`"))
                        ->orWhere('simmed_status', '!=', DB::raw("`send_simmed_status`"))
                        ->orWhere('simmed_status2', '!=', DB::raw("`send_simmed_status2`"))
                        ;
                    })
                    ->get();

                if ($simmeds_changes->count()>0)
                    {
                        $BigTable[1]['head']='zmiany w symulacjach';
                        $BigTable[1]['table']=$simmeds_changes;
                    }



                $look_characters=TechnicianCharacter::where('character_short','<>','free')
                    ->pluck('id')
                    ->toArray();

                $tmp_table=clone $user_simmeds_prepare;
                $tmp_table=$tmp_table
                        ->where(function ($query) use ($look_characters) {
                            $query->whereNull('simmed_technician_id')
                                ->whereIn('simmed_technician_character_id',$look_characters);
                            })                    
                        ->get();

                if ($tmp_table->count()>0)
                {
                    $BigTable[2]['head']='Symulacje, które nia mają przypisanego technika, a powinny...:';
                    $BigTable[2]['table']=$tmp_table;
                }



                $mail_data = [
                    'title'=>$msgTitle['subject'],
                    'msgBody'=>$msgBody,
                    'BigTable'=>$BigTable
                ];
    
//                $zwrocik=Mail::send('mansimmeds.mailsimmed',$mail_data,function($mail) use ($mail_data_address));
    



                    $msgBody='Informacja o zmianach w symulacjach dla koordynatora: <strong> ... </strong><br><hr><br>'.$msgBodyPrep;
    

//        dd($mail_data);

    
    $data['message_show']=TRUE;
    return view('mansimmeds.mailsimmedhtml')->with($mail_data);
//    return 'Wysłano maila';
}













































 
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
          \DB::raw('concat(user_titles.user_title_short," ",alias_leader.lastname," ",alias_leader.firstname) as leader'), 
          'student_subject_name', 'student_group_name', 'subgroup_name',
          'alias_technicians.name as technician_name', 
          //'simmed_technician_id',
          'character_short',
          'simmed_alternative_title'
           )
          ->leftjoin('rooms','simmeds.room_id','=','rooms.id')
          ->leftjoin('users as alias_leader','simmeds.simmed_leader_id','=','alias_leader.id')
          ->leftjoin('users as alias_technicians','simmeds.simmed_technician_id','=','alias_technicians.id')
          ->leftjoin('user_titles','alias_leader.user_title_id','=','user_titles.id')
          ->leftjoin('student_subjects','simmeds.student_subject_id','=','student_subjects.id')
          ->leftjoin('student_groups','simmeds.student_group_id','=','student_groups.id')
          ->leftjoin('student_subgroups','simmeds.student_subgroup_id','=','student_subgroups.id')
          ->leftjoin('technician_characters','simmeds.simmed_technician_character_id','=','technician_characters.id')
          ->orderBy('simmed_date')
          ->orderBy('time')
          ->orderBy('room_number')
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
            $to_csv[]=$row_one->technician_name;
            $to_csv[]=$row_one->character_short;
            $to_csv[]=$row_one->simmed_alternative_title;
            //$to_csv[]=$row_one->simmed_technician_id;
        
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
