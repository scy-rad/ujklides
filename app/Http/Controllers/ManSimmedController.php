<?php
 
namespace App\Http\Controllers;

use App\ManSimmed;
use App\Simmed;
use App\SimmedTemp;
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
                        $new_tmp['simmed_id']                  = $max_import_number;
                        $new_tmp['room_id']                    = $current_room_id;
                        $new_tmp['room_xp_txt']                = $current_room_xp_code;
                        $new_tmp['simmed_date']                = magic_date($data_rows[0]);
                        $new_tmp['simmed_time_begin']          = $data_rows[2];
                        $new_tmp['simmed_time_end']            = $data_rows[3];
                        $new_tmp['simmed_leader_txt']          = trim($data_rows[4]);
                        $new_tmp['student_subject_txt']        = trim($data_rows[6]);
                        $new_tmp['student_group_txt']          = trim(substr(str_replace("/N","/S",$data_rows[7]),2,20));
                        $new_tmp['student_subgroup_txt']       = trim($data_rows[5]);

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

                    dd('import z xlsa nie poprawiony wciąż');
                $row_number++;
                $data_write=null;

                $data_write['status']='wrong';

                $data_rows=explode("\t",$import_row);
                if (count($data_rows)>9)    //import z pliku XLS powinien zawierać więcej niż 9 kolumn
                {                           // początek analizy wiersza
                    if ($data_rows[0]=='Data')    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
                    {
                        $data_write['status']='head';
                    }
                    else          //a jeżeli nie - to analizujemy wszystkie pola
                    {
                        $new_tmp = new SimmedTemp();
                        $new_tmp['import_number']              = $max_import_number;
                        $new_tmp['import_row']                 = $import_row;
                        $new_tmp['simmed_id']                  = $max_import_number;
                        $new_tmp['room_id']                    = Room::find_xls_room(trim($data_rows[9]));
                        $new_tmp['room_xls_txt']               = trim($data_rows[9]);
                        $new_tmp['simmed_date']                = substr($data_rows[0],6,4).'-'.substr($data_rows[0],3,2).'-'.substr($data_rows[0],0,2);
                        $new_tmp['simmed_time_begin']          = substr($data_rows[2],0,5);
                        $new_tmp['simmed_time_end']            = substr($data_rows[2],6,5);
                        $new_tmp['simmed_leader_txt']          = trim($data_rows[4]);
                        $new_tmp['student_subject_txt']        = trim($data_rows[7]);
                        $new_tmp['student_group_txt']          = trim(substr(str_replace("//N//","//S//",trim($data_rows[8])),2,20));
                        $new_tmp['student_subgroup_txt']       = substr(trim(str_replace(',','',$data_rows[5])),-2);

                        $return=$new_tmp->save();
                    }
                }   // koniec analizy wiersza
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

        //$data['miss']=app('App\Http\Controllers\ManSimmedController')->check_tmp_data();
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
                    $group_name=trim(SimmedTemp::find(substr($key,17,5))->student_group_txt);
                    $wydzial=0;
                if (strpos($group_name, 'P/', 0)>0) $wydzial=1;//pielęgniarstwo
                if (strpos($group_name, 'Po/', 0)>0) $wydzial=1;//położnictwo
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
                    $group_id=SimmedTemp::find(substr($key,18,5))->student_subgroup_id;
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
    ##       I M P O R T   A P P E N D           ##
    ##                                           ##
    \*###########################################*/


    public function import_append(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        $data=null;
        $data['step']=$request->step;

        $data_all=SimmedTemp::where('tmp_status',1)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                {
                    $new_row=new Simmed;
                    $new_row['simmed_date']						= $data_one['simmed_date'];
                    $new_row['simmed_time_begin']				= $data_one['simmed_time_begin'];
                    $new_row['simmed_time_end']					= $data_one['simmed_time_end'];
                    $new_row['simmed_type_id']					= $data_one['simmed_type_id'];
                    $new_row['simmed_alternative_title']		= $data_one['simmed_alternative_title'];
                    $new_row['student_subject_id']	        	= $data_one['student_subject_id'];
                    $new_row['student_group_id']    			= $data_one['student_group_id'];
                    $new_row['student_subgroup_id']				= $data_one['student_subgroup_id'];
                    $new_row['room_id']     					= $data_one['room_id'];
                    $new_row['simmed_leader_id']	    		= $data_one['simmed_leader_id'];
                    $new_row['simmed_technician_id']    		= $data_one['simmed_technician_id'];
                    $new_row['simmed_technician_character_id']	= $data_one['simmed_technician_character_id'];
                    $new_row['simmed_status']					= 1;
                    $new_row['simmed_status2']					= 1;
                    $ret=$new_row->save();
                    // dump($ret);
                    // dump($new_row->id);
                    // dump(SimmedTemp::find($new_row->id));
                    // dump('add',$data_one);
                    $data_one->delete();
                }

        $data_all=SimmedTemp::where('tmp_status',2)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('update',$data_one);

        $data_all=SimmedTemp::where('tmp_status',3)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('remove',$data_one);

        $data_all=SimmedTemp::where('tmp_status',9)->get();
        if (count($data_all)>0)
            foreach ($data_all as $data_one)
                dd('back',$data_one);


        return view('mansimmeds.import')->with($data);
    }   //end of public function import_append


    /*###########################################*\
    ##                                           ##
    ##       I M P O R T   A N A L Y Z E         ##
    ##                                           ##
    \*###########################################*/


    public function impanalyze(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
/*
        $blankTemps=SimmedTemp::whereNull('simmed_leader_id')->get();
        if ($blankTemps->count()>0)
            foreach ($blankTemps as $blankTemp)
            {
                $blankTemp->simmed_alternative_title=trim($blankTemp->simmed_leader_txt);
                $blankTemp->save();
            }
        $blankTemps=SimmedTemp::whereNull('student_subject_id')->get();
        if ($blankTemps->count()>0)
            foreach ($blankTemps as $blankTemp)
            {
                $blankTemp->simmed_alternative_title=trim($blankTemp->simmed_alternative_title.' '.$blankTemp->student_subject_txt);
                $blankTemp->save();
            }
        $blankTemps=SimmedTemp::whereNull('student_group_id')->get();
        if ($blankTemps->count()>0)
            foreach ($blankTemps as $blankTemp)
            {
                $blankTemp->simmed_alternative_title=trim($blankTemp->simmed_alternative_title.' '.$blankTemp->student_group_txt);
                $blankTemp->save();
            }
*/

        $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();

        if ($alltmps->count()>0)    //jeżeli znalazłeś wpisy do usunięcia i nie mają jeszcze określonego statusu
            {
            dump('ManSimmedControler sprawdzam, czy nowe wpisy nie są zmianami w grafiku');
                /*
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,leader,subject,group,date,time');//zmiana niczego
                */

                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('leader,subject,group,date,time');//zmiana tylko sali
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,leader,subject,group,date');//zmiana tylko czasu
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,subject,group,date,time');//zmiana tylko prowadzącego
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,leader,group,date,time');//zmiana tylko tematu
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,leader,subject,date,time');//zmiana tylko grupy
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,leader,date,time');//zmiana grupy i tematu
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('leader,subject,group');//zmiana sali i czasu

                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_similar('room,date,time');//ta sama sala i czas


                //SimmedTemp::check_simmed_tmp_remove(); 
                // Jednak chyba nie chcę, aby pozostałe wpisy były automatycznie zaznaczone jako do usunięcia
                // Lepiej będzie zdecydowac o tym ręcznie.



                echo 'Analiza wpisów do usunięcia<br>';
                $data['step']='to_delete_analyze';
                $data['step']='review_analyze';
                $data['import_data']=$alltmps;
            }
        // potem: jeżeli są jakieś wpisy o statusie 0
        if (SimmedTemp::where('tmp_status','=',0)->get()->count()>0) //i są jakiekolwie wpisy o statusie 0
            {
                dump('ManSimmedControler sprawdzam, czy nowe wpisy nie są przywróceniem zajęc usuniętych z grafiku');
                //sprawdź, czy nie ma tych wpisów w usuniętych rezerwacjach

                $alltmps=SimmedTemp::where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_deleted('leader,subject,group,room');//szukanie w usuniętych wpisach - inny czas
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_deleted('leader,subject,group');//szukanie w usuniętych wpisach - inny czas i sala
                $alltmps=SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get();
                foreach ($alltmps as $onetemp)
                        $onetemp->check_deleted('subject,group');//szukanie w usuniętych wpisach - inny czas, sala i prowadzący




                //to ustaw status pozostałych na zaimportuj
                echo 'ustawianie statusu nowych wpisów do zaimportowania';
                //SimmedTemp::check_simmed_tmp_add();
                // masowe zaznacznie plików do importu powinno być gdzieindziej - a nie z automatu 
                $data['step']='review_analyze';
                $data['import_data']=SimmedTemp::all()
                ->sortBy('simmed_time_begin')
                ->sortBy('simmed_date')
                ->sortBy('student_subject_id')
                ->sortBy('student_subgroup_id')
                ->sortBy('student_group_id')
                ->sortBy('simmed_merge')
                ;
            }
        else
            {
                echo 'Brak wpisów lub analiza została już dokonana';
                $data['step']='review_analyze';
                $data['import_data']=SimmedTemp::all()
                ->sortBy('simmed_time_begin')
                ->sortBy('simmed_date')
                ->sortBy('student_subject_id')
                ->sortBy('student_subgroup_id')
                ->sortBy('student_group_id')
                ->sortBy('simmed_merge')
                ;
            }


        $data['import_data']=SimmedTemp::orderByDesc('tmp_status')
            ->orderBy('simmed_merge')
            ->orderByDesc('simmed_id')

            ->orderBy('student_group_id')
            ->orderBy('student_subgroup_id')
            ->orderBy('student_subject_id')

            ->orderBy('simmed_date')
            ->orderBy('simmed_time_begin')
            ->get()
            ;

        $data['step']='review_analyze';


        return view('mansimmeds.impanalyze')->with($data);
    }


    public function markimport(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        if (SimmedTemp::where('simmed_id','>',0)->where('tmp_status','=',0)->get()->count()>0)    //jeżeli znalazłeś wpisy do usunięcia i nie mają jeszcze określonego statusu
            SimmedTemp::check_simmed_tmp_remove(); // to ustaw status na "do usunięcia"
        if (SimmedTemp::where('tmp_status','=',0)->get()->count()>0) //a jeśli pozostały jakiekolwiek wpisy o statusie 0
            SimmedTemp::check_simmed_tmp_add(); //to ustaw status pozostałych na zaimportuj


        $data['import_data']=SimmedTemp::orderByDesc('tmp_status')
            ->orderBy('simmed_merge')
            ->orderByDesc('simmed_id')

            ->orderBy('student_group_id')
            ->orderBy('student_subgroup_id')
            ->orderBy('student_subject_id')

            ->orderBy('simmed_date')
            ->orderBy('simmed_time_begin')
            ->get()
            ;

        $data['step']='review_analyze';


        return view('mansimmeds.impanalyze')->with($data);
    }


    public function clearimport(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji clearimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        echo 'czyszczę...';


        SimmedTemp::truncate();



        if (SimmedTemp::all()->count()==0)
            SimmedTempRoom::where('import_status',0)->delete();



    $data['step']='review_analyze';
    $data['import_data']=SimmedTemp::all();

    return view('mansimmeds.impanalyze')->with($data);
    }



    public function doimport(Request $request)
    {
        //import danych już po przejrzeniu i ustaleniu, które z nich mają zostać dodane/nadpisane/usunięte??/itp
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji doimport kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);


        echo 'importuję...';

        foreach (SimmedTemp::where('tmp_status',1)->get() as $doImport)
            {
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
            $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
			$return=$zmEQ->save();

            if ($return==1)
                SimmedTemp::find($doImport->id)->delete();
            }

        foreach (SimmedTemp::where('tmp_status',2)->where('simmed_id','=',0)->get() as $doImport)
            {
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
            $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
            }

        foreach (SimmedTemp::where('tmp_status',9)->get() as $doImport)
            {
            $zmEQ = Simmed::where('id',$doImport->simmed_merge)->first();
			$zmEQ->simmed_date=$doImport->simmed_date;
			$zmEQ->simmed_time_begin=$doImport->simmed_time_begin;
			$zmEQ->simmed_time_end=$doImport->simmed_time_end;
			$zmEQ->student_subject_id=$doImport->student_subject_id;
			$zmEQ->student_group_id=$doImport->student_group_id;
            $zmEQ->student_subgroup_id=$doImport->student_subgroup_id;
			$zmEQ->room_id=$doImport->room_id;
            $zmEQ->simmed_leader_id=$doImport->simmed_leader_id;
            $zmEQ->simmed_alternative_title=$doImport->simmed_alternative_title;
            $zmEQ->simmed_status=1;
			$return=$zmEQ->save();

            if ($return==1)
                //SimmedTemp::find('simmed_merge',$doImport->simmed_merge)->delete();
                SimmedTemp::where('simmed_merge',$doImport->simmed_merge)->delete();
            }

        foreach (SimmedTemp::where('tmp_status',3)->where('simmed_id','>',0)->get() as $doImport)
            {
            $zmEQ = Simmed::where('id',$doImport->simmed_id)->first();
            $zmEQ->simmed_status=4; //zmiana statutu na 4 (czyli usunięty)
            $return=$zmEQ->save();
            //$return=$zmEQ->delete();

            if ($return==1)
                $doImport->delete();
            }

            if (SimmedTemp::all()->count()==0)
                SimmedTempRoom::where('import_status',0)->update(['import_status'=>'1']);

            $data['step']='review_analyze';
            $data['import_data']=SimmedTemp::all();

        return view('mansimmeds.impanalyze')->with($data);
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



    public function import(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        dd($request);
        $data=null;
        $data['step']=$request->step;

        $data['import_data_id']=$request->import_data_id;
        if ($request->import_data_id >0)
            $data['import_data']=SimmedTempPost::find($request->import_data_id)->post_data;
        elseif ($request->step == 'check_data')
            {
                echo 'wyczyść tablicę tymczasową import<br>i dodaj całe dane do tablicy tymczasowej';

                $request->import_data=str_replace("\r\n\r\n"|"\r\n \r\n","\r\nx\r\n",$request->import_data);

                $zmEQ = new SimmedTempPost();
                $zmEQ->post_data=$request->import_data;
                $return=$zmEQ->save();

                $data['import_data_id']=$zmEQ->id;
                $data['import_data']=SimmedTempPost::find($zmEQ->id)->post_data;

            }


        switch ($request->step){
        case 'add_data':
            //pierwszy krok dodawania danych nie wymaga pobrania żadnych danych
            // tu wyświetla się tylko formatka do wklejenia danych do zaimportowania
            break;


        case 'complement_data':
            //krok trzeci - automatyczne uzupełnienie braków (po kroku trzecim ponownie wykona się krok drugi)
            //ten krok może być pominięty i zaimportją się dane bez wypełnionych brakujących pól

            //dump($request->request);

            if ($request->missing_leaders!=null)
                {      //jeżeli są jacyć nieznalezieni instruktorzy
                $tab_to_do=[];

                foreach ($request->request as $key=>$value)  // tworzymy tabelę liderów, która zawiera wybraną wcześniej akcję (na razie dodaj i pomiń, ale może changr też będzie)
                                                            //może kiedyś będę mógł wybrać, co się dzieje ze znalezionymi brakami - czy mają być dodane, czy wpisane jako puste.
                    {
                    if (substr($key,0,16)=="missing_leaders-")
                        {
                        $tab_to_do[substr($key,16,16)]['action'] = $value;
                        $tab_to_do[substr($key,16,16)]['tab'] = 'leaders';
                        }
                    }

                $leadersi = explode(",", $request->missing_leaders);//stwórz tabelę, gdzie pola są poprzedzielane przecinkami (i będą to wiersze id|nazwa)
                foreach ($leadersi as $leaders_array)
                    {   //przerabiamy wpisy o instruktorach na tablicę | tytuł | imię | nazwisko |

                    $one_leaderX = explode(":", $leaders_array);    //stwórz tabelę, gzie ID wiersza będzie osobno i nazwa będzie osobno

                    $tab_to_do[$one_leaderX[0]]['fullname']=trim($one_leaderX[1]);
                    $tab_to_do[$one_leaderX[0]]['firstname']='';
                    $tab_to_do[$one_leaderX[0]]['lastname']='';
                    $tab_to_do[$one_leaderX[0]]['title']='';

                    $pozostalo_do_analizy=trim($one_leaderX[1]);

                    if (strpos($pozostalo_do_analizy, ' ', 0)>0)
                        {
                        $tab_to_do[$one_leaderX[0]]['firstname']    =   substr($pozostalo_do_analizy,strRpos($pozostalo_do_analizy, ' ', 0)+1,100);
                        $pozostalo_do_analizy=substr($pozostalo_do_analizy,0,strRpos($pozostalo_do_analizy, ' ', 0));
                        $tab_to_do[$one_leaderX[0]]['lastname']=$pozostalo_do_analizy;
                        }
                    if (strpos($pozostalo_do_analizy, ' ', 0)>0)
                        {
                        $tab_to_do[$one_leaderX[0]]['lastname']    =   substr($pozostalo_do_analizy,strRpos($pozostalo_do_analizy, ' ', 0)+1,100);
                        $pozostalo_do_analizy=substr($pozostalo_do_analizy,0,strRpos($pozostalo_do_analizy, ' ', 0));
                        $tab_to_do[$one_leaderX[0]]['title']=$pozostalo_do_analizy;
                        }
                    }   //przerabiamy wpisy o instruktorach na tablicę | tytuł | imię | nazwisko |


                    foreach ($tab_to_do as $row_to_do)
                    {   //analizujemy całą tablicę | tytuł | imię | nazwisko | akcja | i wykonujemy na tych danych akcję
                    if ($row_to_do['action']=='dodaj')
                        {
                        if (UserTitle::where('user_title_short',$row_to_do['title'])->count() > 0)
                            {
                            $leader = new User;
                            $leader->user_title_id=UserTitle::where('user_title_short',$row_to_do['title'])->first()->id;
                            $leader->firstname = $row_to_do['firstname'];
                            $leader->lastname = $row_to_do['lastname'];
                            $leader->name = hrtime()[1];
                            $leader->email = hrtime()[1].'@ujk.edu.pl';
                            $leader->password = bcrypt('pass'.hrtime()[1]);
                            $leader->user_status = 1;
                            $leader->simmed_notify = 0;

                            $leader->save();
                            $leader->add_roles(Roles::find_by_name('Instruktor'),1);
                            }
                        }
                    }   //koniec analizy tablicy i wykonywania akcji

                }      //jeżeli są jacyć nieznalezieni instruktorzy

                if ($request->missing_subjects!=null)
                {
                $tab_to_do=[];

                foreach ($request->request as $key=>$value)  // tworzymy tabelę nie znalezionych tematów i ustawiamy akcję na dodaj lub pomiń
                                                            //może kiedyś będę mógł wybrać, co się dzieje ze znalezionymi brakami - czy mają być dodane, czy wpisane jako puste.
                    {
                    if (substr($key,0,17)=="missing_subjects-")
                        {
                        $tab_to_do[substr($key,17,16)]['action'] = $value;
                        $tab_to_do[substr($key,17,16)]['tab'] = 'subjects';
                        }
                    }

                $subjecty = explode(",", $request->missing_subjects);

                foreach ($subjecty as $one_subject)
                    {
                    $subtab = explode("|", $one_subject);

                    if ($tab_to_do[$subtab[0]]['action'] == 'dodaj' )
                        {
                        $subject=new StudentSubject();
                        $subject->student_subject_name=$subtab[1];
                        $subject->student_subject_status=1;
                        $subject->save();
                        }
                    }
                }

                if ($request->missing_groups!=null)
                {
                $tab_to_do=[];
                foreach ($request->request as $key=>$value)  // tworzymy tabelę nie znalezionych grup i ustawiamy akcję na dodaj lub pomiń
                                                            //może kiedyś będę mógł wybrać, co się dzieje ze znalezionymi brakami - czy mają być dodane, czy wpisane jako puste.
                    {
                    if (substr($key,0,15)=="missing_groups-")
                        {
                        $tab_to_do[substr($key,15,14)]['action'] = $value;
                        $tab_to_do[substr($key,15,14)]['tab'] = 'groups';
                        }
                    }
                $groupsy = explode(",", $request->missing_groups);

                foreach ($groupsy as $one_group)
                    {
                    $grptab = explode("|", $one_group);

                    if ($tab_to_do[$grptab[0]]['action'] == 'dodaj' )
                        {
                        $wydzial=0;
                        if (strpos($grptab[1], 'P/', 0)>0) $wydzial=1;//pielęgniarstwo
                        if (strpos($grptab[1], 'Po/', 0)>0) $wydzial=1;//pielęgniarstwo
                        if (strpos($grptab[1], 'LEK/', 0)>0) $wydzial=2;//lekarski
                        if (strpos($grptab[1], 'RM/', 0)>0) $wydzial=3;//ratownictwo
                        if ($wydzial>0)
                            {
                            $group=new StudentGroup();
                            $group->student_group_name=$grptab[1];
                            $group->center_id=$wydzial;
                            $group->student_group_status=1;
                            $group->save();
                            }
                        }
                    }
                }
                if ($request->missing_subgroups!=null)
                {
                $tab_to_do=[];
                foreach ($request->request as $key=>$value)  // tworzymy tabelę nie znalezionych podgrup i ustawiamy akcję na dodaj lub pomiń
                                                            //może kiedyś będę mógł wybrać, co się dzieje ze znalezionymi brakami - czy mają być dodane, czy wpisane jako puste.
                    {
                    if (substr($key,0,18)=="missing_subgroups-")
                        {
                        $tab_to_do[substr($key,18,17)]['action'] = $value;
                        $tab_to_do[substr($key,18,17)]['tab'] = 'subgroups';
                        }
                    }
                $subgroupsy = explode(",", $request->missing_subgroups);
                foreach ($subgroupsy as $one_subgroup)
                    {
                    $subtab = explode("|", $one_subgroup);

                    if ($tab_to_do[$subtab[0]]['action'] == 'dodaj' )
                        {
                        $group=new StudentSubgroup();
                        $group->student_group_id=$subtab[1];
                        $group->subgroup_name=$subtab[2];
                        $group->subgroup_status=1;
                        $group->save();
                        }
                    }
                }


            $data['step']='check_data';

        case 'check_data':
            //krok drugi - sprawdzenie danych wklejnych z uczelni XP

        case 'check_exist':
            //krok czwarty - dodanie danych do tabeli tymczasowej

            $rows = explode("\n", str_replace("\r", "", $data['import_data']));
            $data['info']['wrong_count']=0;
            $data['info']['room_id']=0;
            $data['info']['room_id_tab']=[];
            $data['info']['missing_room_name']='';


            $data['info']['missing_date']=1;
            $data['info']['missing_room']=0;

            $data['info']['missing_leaders']=0;
            $data['info']['missing_subjects']=0;
            $data['info']['missing_groups']=0;
            $data['info']['missing_subgroups']=0;
            $data['simmeds']=NULL;
            $row_number=0;
            //dump('ManSimmedControler 268 data');
            //dump($data);

            $pokoj_znaleziony=false;

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
                    elseif ($pokoj_znaleziony)          //a jeżeli nie - to aanalizujemy wszystkie pola
                        {
                        $data_write['status']='ok';
                        $data_write['row_number']=$row_number;
                        $data_write['import_row']=$import_row;
                        $data_write['room_id']=$data['info']['room_id'];
                        $data_write['room_xp_code']=$data['info']['room_xp_code'];
                        $data_write['simmed_date']=date('Y-').substr($data_rows[0],3,2).'-'.substr($data_rows[0],0,2);
                            $data_write['simmed_alternative_title']=substr($data_rows[0],5,200).' ';
                        $data_write['simmed_time_begin']=$data_rows[2];
                        $data_write['simmed_time_end']=$data_rows[3];

                        $data_write['simmed_leader_id']=User::find_user($data_rows[4]);

                        if ($data_write['simmed_leader_id']==0)
                            $data_write['simmed_alternative_title'].=$data_rows[4].' ';
                        $data_write['simmed_leader']=$data_rows[4];

                        $data_write['student_subject_id']=StudentSubject::find_subject($data_rows[6]);
                        $data_write['student_subject']=$data_rows[6];
                        if ($data_write['student_subject_id']==0)
                            $data_write['simmed_alternative_title'].=$data_rows[6].' ';

                        $data_write['student_group_id']=StudentGroup::find_group($data_rows[7]);
                        $data_write['student_group']=$data_rows[7];
                        if ($data_write['student_group_id']==0)
                            $data_write['simmed_alternative_title'].=$data_rows[7].' ';

                        $data_write['student_subgroup_id']=StudentSubgroup::find_subgroup($data_write['student_group_id'],$data_rows[5]);
                        $data_write['student_subgroup']=$data_rows[5];

                        if ($data_write['simmed_leader_id']==0)
                            if ($data_write['simmed_leader']!="")
                                {
                                $data['no_leader_list'][$data_rows[4]]['row']=$row_number;
                                $data['no_leader_list'][$data_rows[4]]['name']=$data_write['simmed_leader'];
                                if (($data['no_leader_list'][$data_rows[4]]['name']=='') || !(strpos($data['no_leader_list'][$data_rows[4]]['name'],' ')))
                                    $data['no_leader_list'][$data_rows[4]]['action']='pomiń';
                                else
                                    $data['no_leader_list'][$data_rows[4]]['action']='pomiń';//'dodaj';
                                $data_write['simmed_leader']='';
                                $data['info']['missing_leaders']++;
                                }

                        if (($data_write['student_subject_id']==0) && ($data_write['student_subject']!=''))
                            {
                            $data['no_subject_list'][$data_write['student_subject']]['row']=$row_number;
                            $data['no_subject_list'][$data_write['student_subject']]['name']=$data_write['student_subject'];
                            if (substr($data_write['student_subject'],1,6)=='ezerwa')       //jeśli tematem zajęć jest rezerwacja, to nie dodawaj jej do tematów zajęć
                                $data['no_subject_list'][$data_write['student_subject']]['action']='pomiń';
                            else
                                $data['no_subject_list'][$data_write['student_subject']]['action']='pomiń';//'dodaj';
                            $data['info']['missing_subjects']++;
                            }

                        if (($data_write['student_group_id']==0) && ($data_write['student_group']!=''))
                            {
                            $data['no_group_list'][$data_write['student_group']]['row']=$row_number;
                            $data['no_group_list'][$data_write['student_group']]['name']=$data_write['student_group'];
                            $data['no_group_list'][$data_write['student_group']]['action']='pomiń';//'dodaj';
                            $data['info']['missing_groups']++;
                            }
                        if ( ($data_write['student_subgroup_id']==0) && ($data_write['student_subgroup']!='') )
                            {
                            if ($data_write['student_group_id']>0)
                                {
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['row']=$row_number;
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['group_id']=$data_write['student_group_id'];
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['name']=$data_write['student_subgroup'];
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['action']='pomiń';//'dodaj';
                                }
                            $data['info']['missing_subgroups']++;
                            }

                        $data['simmeds'][]=$data_write;
                        }   //elseif ($pokoj_znaleziony) 
                    }   //if (count($data_rows)==8)
                elseif (substr($import_row,0,8)=='Zajęcia') //chyba że jest to nagłówek tabeli sali
                    {
                        $sub_data=explode(" ",$import_row);
                        $data['info']['room_xp_code']=trim($sub_data[3]);
                        $data['info']['room_id']=Room::find_xp_room($data['info']['room_xp_code']);

                        //$data['info']['from']=$sub_data[8];
                        //$data['info']['to']=$sub_data[10];

                        if ($data['info']['room_id']==0)
                            {
                            //dump('ManSimmedControler 379 data');
                            //dump($sub_data[3]);
                            $data['info']['missing_room']++;
                            $data['info']['missing_room_name'].=$sub_data[3].', ';
                            $pokoj_znaleziony=false;
                            }
                        else
                            {
                            $pokoj_znaleziony=true;
                            $data['info']['room_id_tab'][]=$data['info']['room_id'];
                            }

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
                    //dump('ManSimmedControler błędny wiersz:'.$import_row);
                    $data['info']['wrong_count']++;
                    $data['wrong'][]=$import_row;
                    }
                }   // koniec analizy pliku z uczelniXP //
                    //////////////////////////////////////



            if ($request->import_type == "xls")
                                            ////////////////////////////////////////
            foreach ($rows as $import_row)  // początek analizy pliku z  eXcela Ilony //
                {

                $row_number++;
                $data_write=null;

                $data_write['status']='wrong';

                $data_rows=explode("\t",$import_row);
                if (count($data_rows)>9)   //import z uczelni XP powinien zawierać 8 kolumn
                    {
                    if ($data_rows[0]=='Data')    //jeżeli pierwsza komórka zawiera ten tekst - to znaczy że jest to wiersz nagłówkowy
                        {
                        $data_write['status']='head';
                        }
                    else          //a jeżeli nie - to analizujemy wszystkie pola
                        {

                        $data_write['status']='ok';
                        $data_write['row_number']=$row_number;
                        $data_write['import_row']=$import_row;
                        //$data_write['room_id']=$data['info']['room_id'];
                        $data_write['simmed_date']=substr($data_rows[0],6,4).'-'.substr($data_rows[0],3,2).'-'.substr($data_rows[0],0,2);
                            $data_write['simmed_alternative_title']=$data_write['simmed_date'].' ';
                        $data_write['simmed_time_begin']=substr($data_rows[2],0,5);
                        $data_write['simmed_time_end']=substr($data_rows[2],6,5);


                        $data_write['simmed_leader_id']=User::find_user(trim($data_rows[4]));

                        if ($data_write['simmed_leader_id']==0)
                            $data_write['simmed_alternative_title'].=$data_rows[4].' ';
                        $data_write['simmed_leader']=trim($data_rows[4]);

                        $data_write['student_subject']=trim($data_rows[7]);
                        $data_write['student_subject_id']=StudentSubject::find_subject($data_write['student_subject']);
                        if ($data_write['student_subject_id']==0)
                            $data_write['simmed_alternative_title'].=$data_write['student_subject'].' ';

                        $data_write['student_group']=trim($data_rows[8]);
                        $data_write['student_group_id']=StudentGroup::find_group($data_rows[5]);
                        if ($data_write['student_group_id']==0)
                            $data_write['simmed_alternative_title'].=$data_rows[7].' ';

                        $data_write['student_subgroup']=trim(str_replace(',','',$data_rows[5]));
                        $data_write['student_subgroup']=substr($data_write['student_subgroup'],-2);
                        $data_write['student_subgroup_id']=StudentSubgroup::find_subgroup($data_write['student_group_id'],$data_write['student_subgroup']);

                        $data_write['room_number']=trim($data_rows[9]);
                        $data_write['room_id']=Room::find_xls_room($data_write['room_number']);

                        if ($data_write['student_group_id']==0)
                            $data_write['simmed_alternative_title'].=$data_write['room_number'].' ';

                        if ($data_write['simmed_leader_id']==0)
                            if ($data_write['simmed_leader']!="")
                                {
                                $data['no_leader_list'][$data_rows[4]]['row']=$row_number;
                                $data['no_leader_list'][$data_rows[4]]['name']=$data_write['simmed_leader'];
                                if (($data['no_leader_list'][$data_rows[4]]['name']=='') || !(strpos($data['no_leader_list'][$data_rows[4]]['name'],' ')))
                                    $data['no_leader_list'][$data_rows[4]]['action']='pomiń';
                                else
                                    $data['no_leader_list'][$data_rows[4]]['action']='pomiń';//'dodaj';
                                $data_write['simmed_leader']='';
                                $data['info']['missing_leaders']++;
                                }

                        if (($data_write['student_subject_id']==0) && ($data_write['student_subject']!=''))
                            {
                            $data['no_subject_list'][$data_write['student_subject']]['row']=$row_number;
                            $data['no_subject_list'][$data_write['student_subject']]['name']=$data_write['student_subject'];
                            if (substr($data_write['student_subject'],1,6)=='ezerwa')       //jeśli tematem zajęć jest rezerwacja, to nie dodawaj jej do tematów zajęć
                                $data['no_subject_list'][$data_write['student_subject']]['action']='pomiń';
                            else
                                $data['no_subject_list'][$data_write['student_subject']]['action']='pomiń';//'dodaj';
                            $data['info']['missing_subjects']++;
                            }

                        if (($data_write['student_group_id']==0) && ($data_write['student_group']!=''))
                            {
                            $data['no_group_list'][$data_write['student_group']]['row']=$row_number;
                            $data['no_group_list'][$data_write['student_group']]['name']=$data_write['student_group'];
                            $data['no_group_list'][$data_write['student_group']]['action']='pomiń';//'dodaj';
                            $data['info']['missing_groups']++;
                            }
                        if ( ($data_write['student_subgroup_id']==0) && ($data_write['student_subgroup']!='') )
                            {
                            if ($data_write['student_group_id']>0)
                                {
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['row']=$row_number;
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['group_id']=$data_write['student_group_id'];
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['name']=$data_write['student_subgroup'];
                                $data['no_subgroup_list'][$data_write['student_group_id']][$data_write['student_subgroup']]['action']='pomiń';//'dodaj';
                                }
                            $data['info']['missing_subgroups']++;
                            }

                        $data['simmeds'][]=$data_write;
                        }   //elseif ($pokoj_znaleziony) 
                    }   //if (count($data_rows)==8)
                // elseif (strlen($import_row)>1)
                //     {
                //     //dump('ManSimmedControler błędny wiersz:'.$import_row);
                //     $data['info']['wrong_count']++;
                //     $data['wrong'][]=$import_row;
                //     }
                }   // koniec analizy pliku z eXcela Ilony //
                    //////////////////////////////////////

            //to wyrzucamy póki co - nawet jak nie znalazł sali, to niech to jednak zaczyta i przeanalizuje)
            // //dd($data,$data['info']['room_id']);
            // if ($data['info']['room_id']==0)
            //     {
            //         $data['step']='add_data';
            //         $data['err_info']='nie wykryto sali... '.$data['info']['missing_room_name'];
            //     }
            // elseif (SimmedTempRoom::where('room_id',$data['info']['room_id'])->where('import_status',0)->get()->count()>0)
            //     {
            //         $data['step']='add_data';
            //         $data['err_info']='wybrany import został juz wczytany do systemu...';
            //     }

            break;
        

    if ($request->step=="check_exist")
        {
        $exist_list=[];
        foreach ($data['info']['room_id_tab'] as $room_id_ex)
            $exist_list[$room_id_ex][]=0;
        //dump('ManSimmedControler 436 data');
        //dump($data);
        $i=1;
        $data['noexist_list']=[];
        $data['old_list']=[];

        if ($data['simmeds'] != NULL)
            foreach ($data['simmeds'] as $import_one_data)
                {
                //dump('ManSimmedControler 445 data');
                $simmed_look=Simmed::where('room_id',$import_one_data['room_id'])
                        ->where('simmed_date',$import_one_data['simmed_date'])
                        ->where('simmed_time_begin',$import_one_data['simmed_time_begin'])
                        ->where('simmed_time_end',$import_one_data['simmed_time_end'])
                        ->where('simmed_status','<',4);

                if ($import_one_data['simmed_leader_id']>0)
                    $simmed_look=$simmed_look->where('simmed_leader_id',$import_one_data['simmed_leader_id']);
                if ($import_one_data['student_subject_id']>0)
                    $simmed_look=$simmed_look->where('student_subject_id',$import_one_data['student_subject_id']);
                if ($import_one_data['student_group_id']>0)
                    $simmed_look=$simmed_look->where('student_group_id',$import_one_data['student_group_id']);
                if ($import_one_data['student_subgroup_id']>0)
                    $simmed_look=$simmed_look->where('student_subgroup_id',$import_one_data['student_subgroup_id']);

                $simmed_look=$simmed_look->get()->first();

                if ($simmed_look!=null)                                     //jeżeli taki wpis istnieje
                    {
                    $exist_list[$import_one_data['room_id']][]=$simmed_look->id;                         //to dopisz id tej sali do listy istniejących wpisów
                    //dump('exist: '.$simmed_look->id);
                    }
                else                                                        //a jeśli nie
                    {
                    $exist_list[$import_one_data['room_id']][]=0;   //to dodałem, żeby sprawdzał także te sale, których nie dopisał wcześniejszy if (bo żaden z wprowadzanych wpisów nie dotyczył danego pokoju)
                    $import_one_data['room_name']=Room::find($import_one_data['room_id'])->room_name;
                    $import_one_data['id']=0;//'n'.$i++;
                    $import_one_data['head']='nowy';
                    $data['noexist_list'][]=$import_one_data;                   //to dodaj wpis do tablicy nowych wpisów
                    }
                }

            foreach ($exist_list as $key=>$value)
                {
                $old_list=Simmed::whereNotIn('id',$value)
                    ->where('room_id','=',$key)
                    ->where('simmed_date','>=',$data['info']['from'])
                    ->where('simmed_date','<=',$data['info']['to'])
                    ->where('simmed_status','<',4)
                    ->get();
                    //->toArray();
                /*
                $zapytanie_wprost="select * from `simmeds` where `id` not in (".implode(',',$value).") and `room_id` = $key and `simmed_date` >= ".$data['info']['from']." and `simmed_date` <= ".$data['info']['to']." and `simmed_status` < 4";
                echo $zapytanie_wprost;
                dd($zapytanie_wprost);
                dump($key.' : '.Room::find($key)->room_name.' : '.$data['info']['from'].' > '.$data['info']['to'],$old_list);
                */

                foreach ($old_list as $old_one)
                    {
                    $old_tab=$old_one->toArray();
                    $old_tab['import_row']='EXIST';
                    $old_tab['simmed_time_begin']=substr($old_one['simmed_time_begin'],0,5);
                    $old_tab['simmed_time_end']=substr($old_one['simmed_time_end'],0,5);
                    $old_tab['simmed_leader']=$old_one->name_of_leader();
                    $old_tab['student_subject']=$old_one->name_of_student_subject();
                    $old_tab['student_group']=$old_one->name_of_student_group();
                    $old_tab['student_subgroup']=$old_one->name_of_student_subgroup();
                    $old_tab['room_name']=Room::find($old_one['room_id'])->room_name;
                    $old_tab['head']='brak';
                    $old_tab['class']='bg_info';
                    //$old_tab['id']=0;
                    //dump('ManSimmedControler 508 old tab');
                    //dump($old_tab['room_name']);

                    $data['old_list'][]=$old_tab;
                    }
                }

            SimmedTempRoom::add_simmed_tmp_room($data);

            SimmedTemp::add_simmed_tmp($data['noexist_list']);
            SimmedTemp::add_simmed_tmp($data['old_list']);



            //dump($data['noexist_list']);
            //dump($data['old_list']);
            //dd('end');

            $data['step']='import_tmp';
        }

        //dump('ManSimmedControler 531 data');
        //dump($data);
               // dd('tu tu tu: '.$request->step);
               dump('pierwszy przebieg operuje na danych niebazodanowych - to już wiem :)');
               dump('data:',$data);
               dd('data_rows:',$data_rows);

        return view('mansimmeds.import')->with($data);
    }   //end of public function import
}



}
