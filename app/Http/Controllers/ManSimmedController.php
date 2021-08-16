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
        

        //$data['rooms']=room::all();
       // $data['tab2']=['jeden'=>1, 'dwa'=>2, 'trzy'=>3];
       // $data['tab3']='It for room ';
        //$data['tab3']='It for room '.$request->forroom;

        return view('mansimmeds.index');//->with($data);
    }

    public function subjects() //  metoda GET bez parametrów
    {
        if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
        

        $data['subjects']=StudentSubject::all();
        $data['tab2']=['jeden'=>1, 'dwa'=>2, 'trzy'=>3];
        $data['tab3']='It for room ';
        //$data['tab3']='It for room '.$request->forroom;

        return view('mansimmeds.subjects')->with($data);
    }

    public function groups() //  metoda GET bez parametrów
    {
        if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
        

        $data['groups']=StudentGroup::all();
        $data['tab2']=['jeden'=>1, 'dwa'=>2, 'trzy'=>3];
        $data['tab3']='It for room ';
        //$data['tab3']='It for room '.$request->forroom;

        return view('mansimmeds.groups')->with($data);
    }

    /*##############################*\
    ##                              ##
    ##       I  M  P  O  R  T       ##
    ##                              ##
    \*##############################*/

    public function import(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania funkcji import kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
    
        $data=null;
        $data['step']=$request->step;
        
        $data['import_data_id']=$request->import_data_id;
        if ($request->import_data_id >0)
            $data['import_data']=SimmedTempPost::find($request->import_data_id)->post_data;
        elseif ($request->step == 'check_data')
            {
                echo 'wyczyść tablicę tymczasową<br>i dodaj całe dane do tablicy tymczasowej';
                
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
                    
                    $tab_to_do[$one_leaderX[0]]['fullname']=$one_leaderX[1];
                    $tab_to_do[$one_leaderX[0]]['firstname']='';
                    $tab_to_do[$one_leaderX[0]]['lastname']='';
                    $tab_to_do[$one_leaderX[0]]['title']='';

                    $pozostalo_do_analizy=$one_leaderX[1];
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

            foreach ($rows as $import_row)
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
                    elseif ($pokoj_znaleziony) {                              //a jeżeli nie - to aanalizujemy wszystkie pola
                        $data_write['status']='ok';
                        $data_write['row_number']=$row_number;
                        $data_write['import_row']=$import_row;
                        $data_write['room_id']=$data['info']['room_id'];
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
                        }
                    }
                elseif (substr($import_row,0,8)=='Zajęcia')
                    {
                        $sub_data=explode(" ",$import_row);
                        $data['info']['room_id']=Room::find_xp_room($sub_data[3]);
                        
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
                }


                
            //dd($data,$data['info']['room_id']);
            if ($data['info']['room_id']==0)
                {
                    $data['step']='add_data';
                    $data['err_info']='nie wykryto sali... '.$data['info']['missing_room_name'];
                }
            elseif (SimmedTempRoom::where('room_id',$data['info']['room_id'])->where('import_status',0)->get()->count()>0)
                {
                    $data['step']='add_data';
                    $data['err_info']='wybrany import został juz wczytany do systemu...';
                }

            break;
    }

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

//            dd('STOP TU');
            
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

        return view('mansimmeds.import')->with($data);
    }






    public function impanalyze(Request $request)
    {
        if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania funkcji impanalyze kontrolera ManSimmed','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);

        echo 'Sprawdzam, czy są jakies wpisy do usunięcia<br>';
        
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

               
        echo 'czyszcvzę...';
        
        
        SimmedTemp::truncate();

        
        
        if (SimmedTemp::all()->count()==0)
            SimmedTempRoom::where('import_status',0)->delete();

            

    $data['step']='review_analyze';
    $data['import_data']=SimmedTemp::all();

    return view('mansimmeds.impanalyze')->with($data);
    }



    public function doimport(Request $request)
    {
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
}
