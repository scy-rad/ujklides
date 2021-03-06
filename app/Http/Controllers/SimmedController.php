<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Simmed;
use App\User;
use App\SimmedArcTechnician;
use App\TechnicianCharacter;
use Illuminate\Support\Facades\DB;  //dla ajaxa
use Illuminate\Support\Facades\Auth;


class SimmedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {

        if (!isset($request->start))
        {
            $filtr['start'] = date('Y-m-d');
            $filtr['stop'] = date('Y-m-d',strtotime( date('Y-m-d') .' +7 day' ));
            $filtr['gcsm']=0;
            $filtr['rcsm']=0;
            $filtr['route']= 'now';
            $filtr['nofree'] = '';
        }
        else
        {
            $filtr['start'] = $request->start;
            $filtr['stop'] = $request->stop;
            $filtr['gcsm']= $request->gcsm;
            $filtr['rcsm']= $request->rcsm;
            $filtr['route']= $request->route;
            $filtr['nofree']= $request->nofree;
        }
        switch ($filtr['route'])
        {
            case 'all':
                $simmeds =  Simmed::simmeds_join('with_free','without_deleted','without_send')
                ->orderBy('simmed_date')
                ->orderBy('simmed_time_begin')
                ->orderBy('room_number');
            break;
            
            case 'month':
                $simmeds =  Simmed::simmeds_join('with_free','without_deleted','without_send')
                    ->where('simmed_date','>=',date('Y-m').'-01')
                    ->where('simmed_date','<=',date('Y-m-t'))
                    ->orderBy('simmed_date')
                    ->orderBy('simmed_time_begin')
                    ->orderBy('room_number');
            break;

            default:
            case 'now':
                $simmeds =  Simmed::simmeds_join('with_free','without_deleted','without_send')
                    ->where('simmed_date','>=',$filtr['start'])
                    ->where('simmed_date','<=',$filtr['stop'])
                    ->orderBy('simmed_date')
                    ->orderBy('simmed_time_begin')
                    ->orderBy('room_number');
            break;

        }
        $ret['center_list']=\App\Center::all();
        $ret['filtr']=$filtr;

        if ($filtr['gcsm']>0)
        {
            $simmeds=$simmeds->where('student_groups.center_id','=',$filtr['gcsm']);
        }
        elseif ($filtr['gcsm']==-1)
        {
            $simmeds=$simmeds->whereNull('student_groups.center_id');
        }
        if ($filtr['rcsm']>0)
        {
            $simmeds=$simmeds->where('rooms.center_id','=',$filtr['rcsm']);
        }
        elseif ($filtr['rcsm']==-1)
        {
            $simmeds=$simmeds->whereNull('rooms.center_id');
        }
        if ($filtr['nofree']=='nofree')
        {
            $simmeds=$simmeds->whereNull('student_groups.center_id');
        }
        $simmeds = $simmeds->get();




        if (isset($request->csv))
        {

            $filename="lista_symulacji.csv";
            $fp = fopen($filename, 'w');
            fwrite($fp, pack("CCC", 0xef, 0xbb, 0xbf));
    
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
            fputcsv($fp,$to_csv,';');
    
        
                foreach ($simmeds as $row_one)
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
                
                fputcsv($fp,$to_csv,';');
                }
                fputcsv($fp,['koniec'],';');
                fputcsv($fp,[''],';');
                fclose($fp);
                      
            header('Content-type: text/csv');
            header('Content-disposition:attachment; filename="'.$filename.'"');
            readfile($filename);
    
            exit;
    //        dump(serialize($alltmps));
        }

        $ret['filtr']['csv']='csv';



        return view('simmeds.index', compact('simmeds'), $ret );
    }


    public function plane(Request $request)
    {

        //to chyba realizuje ju?? tylko funkacja ajax poni??ej
        function validateDate($date, $format = 'Y-m-d H:i:s')
                {
                    $now = new \DateTime();
                    $d = $now::createFromFormat($format, $date);
                    return $d && $d->format($format) == $date;
                }

        $ret_sch_date=$request['date'];
        $ret['sch_csm']=$request['csm'];
        $ret['sch_important']=$request['important'];

        if (!(validateDate($ret_sch_date, 'Y-m-d')))
            $ret_sch_date=date('Y-m-d');

        $do_poniedzialku=date('N',strtotime($ret_sch_date))-1;
        $ret_sch_date=date('Y-m-d',strtotime("$ret_sch_date - $do_poniedzialku day"));
        //dump('tu trzeba zmieni?? na +6 dni zamiast +1 - zmienione na czas test??w tylko');
        $ret['sch_date_to']=date('Y-m-d',strtotime("$ret_sch_date +6 day"));
        $ret['sch_date_next']=date('Y-m-d',strtotime("$ret_sch_date +7 day"));
        $ret['sch_date_prev']=date('Y-m-d',strtotime("$ret_sch_date -7 day"));

            //$rows_plane=Simmed::simmeds_for_plane($ret_sch_date); -  ta funkcja b??dzie do wywalenia, bo zmieni??em skrypt datatable
            $rows_plane=Simmed::select('*','simmeds.id as sim_id')->where('simmed_date','>=',$ret_sch_date)->where('simmed_date','<=',$ret['sch_date_to'])
                ->where('simmed_status','<>',4);
            if ($ret['sch_csm']<0)
                $rows_plane=$rows_plane->whereNull('student_group_id');
            if ($ret['sch_csm']>0)
                $rows_plane=$rows_plane->join('student_groups', 'simmeds.student_group_id', '=', 'student_groups.id')->where('center_id','=',$ret['sch_csm']);

            if($ret['sch_important']=="on")
                $rows_plane=$rows_plane->where('simmed_technician_character_id','<>',TechnicianCharacter::where('character_short','free')->get()->first()->id);

            // dump(TechnicianCharacter::where('character_short','free')->get()->first()->id);

                $rows_plane=$rows_plane->orderBy('simmed_date')
                ->orderBy('simmed_time_begin')
                ->orderBy('room_id')
                ->get();

        $ret['technician_list']=User::role_users('technicians', 1, 1)->get();
        $ret['center_list']=\App\Center::all();
        $technician_char=TechnicianCharacter::all();
        $prev_id=0;
        foreach ($technician_char as $technician_one)
            {
            if ($prev_id==0)
                {
                $prev_id=$technician_one->id;
                $change_tech=$technician_one;
                }
            else
                {
                $change_tech->next_value=$technician_one->id;
                $change_tech=$technician_one;
                }
            }
            $change_tech->next_value=$prev_id;
        
        $ret_technician_char=$technician_char->toArray();
        foreach ($ret_technician_char as $row)
            $ret['technician_char'][$row['id']]=$row;   //zmiana, ??eby id wiersza by??o id tabeli

        $ret['sch_date']=$ret_sch_date;

        $ret['to_plane'] =  Simmed::where('simmed_status','<>',4)->where('simmed_date','>=',date('Y-m-d'))->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();
        //->where('simmed_date','<=','2021-12-31')

    return view('simmeds.plane', compact('rows_plane'),$ret);
    }

    public function scheduler( string $sch_date)
    {
        //$simmeds =  Simmed::all();
        $rows_scheduler=\App\WorkTime::activity_for_scheduler($sch_date);
        return view('simmeds.scheduler', ['rows_scheduler' => $rows_scheduler,'sch_date' => $sch_date]);
        //return view('simmeds.scheduler', compact('rows_scheduler'),['sch_date' => $sch_date]);
}

    public function timetable(Request $request)
    {
        if ($request->what_name==null)
            {
            $what_name='technicians';
            $what_no=5;
            $start_date=date("Y-m-d");
            }
        else
            {
            $what_name=$request->what_name;
            $what_no=$request->what_no;
            $start_date=$request->start_date;
            }


        $end_date=date( "Y-m-d", strtotime( "$start_date +7 day" ) );
        $rows_timetable=Simmed::simmeds_for_timetable($what_name,$what_no,$start_date,$end_date);
        return view('simmeds.timetable', compact('rows_timetable'),['start_date' => $start_date,'what_name' => $what_name,'what_no' => $what_no]);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $simmed = new Simmed();
        return view('simmeds.create', compact('simmed'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo '<h1>funkcja STORE Simmed Controller</h1>';

     $this->validate($request, [
        'date_begin' => 'required',
        //'date_end' => 'required',
        'date_end' => 'required|regex:/^([0-1])?$/'
        //'status' => 'required|regex:/^\d+$/'
    ]);

//    Devices::create($request->all());

    dd($request);

    return redirect()->route('simmeds.index')->with('success', 'Dane zosta??y dodane.');

    }


    public function ajaxgetplane(Request $request) {

        function validateDate($date, $format = 'Y-m-d H:i:s')
                {
                    $now = new \DateTime();
                    $d = $now::createFromFormat($format, $date);
                    return $d && $d->format($format) == $date;
                }

        if (!(validateDate($request['sch_date'], 'Y-m-d')))
            $sch_date=date('Y-m-d');
        else
            $sch_date=$request['sch_date'];

        $do_poniedzialku=date('N',strtotime($sch_date))-1;
        $sch_date=date('Y-m-d',strtotime("$sch_date - $do_poniedzialku day"));
        $sch_date_to=date('Y-m-d',strtotime("$sch_date +7 day"));

        //$rows_plane=Simmed::simmeds_for_plane($sch_date);
        $rows_plane=[];

        $output = array(
            'draw'		=>	intval($_POST['draw']),
            'recordsTotal'	=>	512,
            'recordsFiltered'	=>	$rows_plane->count(),
            'data'		=>	$$rows_plane
        );

        //return json_encode(array('result'=>false, 'tescik' =>'przykladowy_tekst', 'statusx'=> $status));
        echo json_encode($output);
     }

     public function ajaxsavetechnician(Request $request) {

        $date_back=\App\Param::select('*')->orderBy('id','desc')->get()->first()->simmed_days_edit_back;
        
        if ($date_back<0)
            $date_back='+'.$date_back*(-1);
        else
            $date_back='-'.$date_back;

        if (
            (
            DB::table('simmeds')->find($request->id)->simmed_date >= date('Y-m-d',strtotime('now '.$date_back.' days'))
            && Auth::user()->hasRole('Technik')
            )
            || Auth::user()->hasRole('Operator Symulacji')
           )
        {
            if ($request->technician_id==0)
                $status = DB::table('simmeds')
                ->where('id', $request->id)
                ->update(['simmed_technician_id' => NULL]);
            else
                $status = DB::table('simmeds')
                ->where('id', $request->id)
                ->update(['simmed_technician_id' => $request->technician_id]);

            $history_table = new SimmedArcTechnician();
            $history_table->simmed_id = $request->id;
            $history_table->technician_id = $request->technician_id*1;
            $history_table->user_id = Auth::user()->id;
            $history_table->save();

            $returnBool=true;
            $returnTxt='zapis chyba si?? uda?? :)';
        }
        else
        {
            $status=0;
            $returnBool=false;
            $returnTxt='zmian powy??ej '.$date_back.' dni wstecz mo??e dokona?? tylko Operator Symulacji';
        }
        return json_encode(array('result' => $returnBool, 'tescik' => $returnTxt, 'status' => $status));
    }


    public function ajaxtechnicianchar(Request $request) 
    {
        if (
            Auth::user()->hasRole('Operator Symulacji')
           )
        {
            $status = DB::table($request->table)
            ->where('id', $request->id)
            ->update(['simmed_technician_character_id' => $request->character_id]);
            return json_encode(array('result'=>true, 'tescik' =>'przykladowy_tekst', 'statusx'=> $status));
        }
        return json_encode(array('result'=>false, 'tescik' =>'Nie masz uprawnie?? do tego zapisu (ajaxTechnicianChar in SimmedController)', 'statusx'=> 0));
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Simmed $simmed, Int $filtr)
    {
        $data['technician_history'] = SimmedArcTechnician::where('simmed_id', $simmed->id)
        ->get();
        $data['simmed_history'] = \App\SimmedArc::where('simmed_id', $simmed->id)
        ->get();

        $date_stat=\App\Param::select('statistics_start')->get()->first()->statistics_start;

        $other_sims = \App\Simmed::select('*')
            ->where('id', '<>' ,$simmed->id)
            ->where('student_subject_id', '=' ,$simmed->student_subject_id)
            ->where('simmed_leader_id', '=' ,$simmed->simmed_leader_id)
            ->whereNotNull('student_subject_id')
            ->whereNotNull('simmed_leader_id')
            ->where('simmed_date','>=',$date_stat)
            ->pluck('id');

        $data['simulation_info'] = \App\SimmedDescript::select('*')->whereIn('simmed_id',$other_sims)->get();
        
        $data['simmed_descript'] = \App\SimmedDescript::select('*')
        -> where('simmed_id',$simmed->id)
        ->get();

        if ($data['simmed_descript']->count() == 0)
        {
            $data['simmed_descript'] = new \App\SimmedDescript;
        }
        else
        {
            $data['simmed_descript']=$data['simmed_descript']->first();
        }

        $data['technicians_list']=User::role_users('technicians', 1, 1)->orderBy('lastname')->get();

        return view('simmeds.show', compact('simmed'), $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
    public function edit(Simmed $simmed)
    {
        $ret['technicians_list']=User::role_users('technicians', 1, 1)->orderBy('lastname')->get();
        $ret['leaders_list']=User::role_users('instructors', 1, 1)->orderBy('lastname')->get();
        $ret['subjects_list']=\App\StudentSubject::where('student_subject_status',1)->orderBy('student_subject_name')->get();
        //$ret['rooms_list']=\App\Room::where('room_status',1)->orderBy('room_number')->get();
        $ret['rooms_list']=\App\Room::where('room_xp_code','<>','')->orderBy('room_number')->get();
        $ret['status_list']=Simmed::status_table();
        $ret['technician_characters_list']=\App\TechnicianCharacter::orderBy('id')->orderBy('character_short')->get();
        return view('simmeds.edit', compact('simmed'), $ret);
    }

    public function copy(Simmed $simmed)
    {
        $simmed->id = 0;
        $ret['technicians_list']=User::role_users('technicians', 1, 1)->orderBy('lastname')->get();
        $ret['leaders_list']=User::role_users('instructors', 1, 1)->orderBy('lastname')->get();
        $ret['subjects_list']=\App\StudentSubject::where('student_subject_status',1)->orderBy('student_subject_name')->get();
        //$ret['rooms_list']=\App\Room::where('room_status',1)->orderBy('room_number')->get();
        $ret['rooms_list']=\App\Room::where('room_xp_code','<>','')->orderBy('room_number')->get();
        $ret['status_list']=Simmed::status_table();
        $ret['technician_characters_list']=\App\TechnicianCharacter::orderBy('id')->orderBy('character_short')->get();
        $simmed->simmed_status = 5; //dopisane
        $simmed->simmed_group_id = 0; //brak grupy
        $simmed->simmed_subgroup_id = 0; //brak podgrupy
        return view('simmeds.edit', compact('simmed'), $ret);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @rnurn \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $date_back=\App\Param::select('*')->orderBy('id','desc')->get()->first()->simmed_days_edit_back;

        if ($date_back<0)
            $date_back='+'.$date_back*(-1);
        else
            $date_back='-'.$date_back;


        if ( ($request->id == 0)
            && (!Auth::user()->hasRole('Operator Symulacji'))
           )
           return back()->withErrors('brak uprawnie?? do kopiowania wpis??w (update in SimmedController)');   

        if ( $request->id > 0 )
            if (
                ( DB::table('simmeds')->find($request->id)->simmed_date >= date('Y-m-d',strtotime('now '.$date_back.' days'))
                    && Auth::user()->hasRole('Technik') )
                || Auth::user()->hasRole('Operator Symulacji')
               )
               {
                
               }
            else
               {
                   return back()->withErrors('brak uprawnie?? do zapisu (update in SimmedController)');   
               }
                  

        if ($request->id==0)
        {
            $modified_row = new SiMmed;
            $modified_row->user_id                          = Auth::user()->id;
        }
        else
        { 
            $modified_row=SiMmed::find($request->id);

            if (($modified_row->simmed_technician_id*1) != ($request->simmed_technician_id*1))
            {
                $history_table = new SimmedArcTechnician();
                $history_table->simmed_id = $request->id;
                $history_table->technician_id = $request->simmed_technician_id*1;
                $history_table->user_id = Auth::user()->id;
                $history_table->save();
            }

            $arc_row=new \App\SimmedArc();            
            $arc_row->simmed_date						= $modified_row->simmed_date;
            $arc_row->simmed_time_begin				    = $modified_row->simmed_time_begin;
            $arc_row->simmed_time_end					= $modified_row->simmed_time_end;
            $arc_row->student_subject_id	        	= $modified_row->student_subject_id;
            $arc_row->room_id     					    = $modified_row->room_id;
            $arc_row->simmed_leader_id	    		    = $modified_row->simmed_leader_id;
            $arc_row->simmed_technician_id    		    = $modified_row->simmed_technician_id;
            $arc_row->simmed_technician_character_id    = $modified_row->simmed_technician_character_id;
            $arc_row->simmed_alternative_title		    = $modified_row->simmed_alternative_title;
            $arc_row->simmed_status 					= $modified_row->simmed_status;

            $arc_row->simmed_type_id				    = $modified_row->simmed_type_id;
            $arc_row->student_group_id    			    = $modified_row->student_group_id;
            $arc_row->student_subgroup_id   			= $modified_row->student_subgroup_id;
            $arc_row->simmed_status2		    		= $modified_row->simmed_status2;
            $arc_row->created_at    			    	= $modified_row->created_at;
            $arc_row->updated_at    				    = $modified_row->updated_at;
            $arc_row->change_code                       = 20; //edycja r??czna
            $arc_row->simmed_id                         = $modified_row->id;
            $arc_row->user_id                           = $modified_row->user_id;
        }    

        if (Auth::user()->hasRole('Operator Symulacji'))
        {

            $modified_row->simmed_date						= $request->simmed_date;
            $modified_row->simmed_time_begin				= $request->simmed_time_begin;
            $modified_row->simmed_time_end					= $request->simmed_time_end;
            if ($request->student_subject_id==0)
                $modified_row->student_subject_id	        = null;
            else
                $modified_row->student_subject_id	        = $request->student_subject_id;
            $modified_row->room_id     					    = $request->room_id;
            if ($request->simmed_leader_id==0)
                $modified_row->simmed_leader_id     		= null;
            else
                $modified_row->simmed_leader_id     		= $request->simmed_leader_id;
            $modified_row->simmed_technician_character_id   = $request->simmed_technician_character_id;
            $modified_row->simmed_status 					= $request->simmed_status;
        }
        if ($request->simmed_technician_id==0)
            $modified_row->simmed_technician_id    		= null;
        else
            $modified_row->simmed_technician_id    		= $request->simmed_technician_id;
        $modified_row->simmed_alternative_title		    = substr($request->simmed_alternative_title,0,254);
        
        $ret_save=$modified_row->save();

        if ( (count($modified_row->getChanges())>0 )
                && ($request->id>0) )
            {
            $modified_row->user_id                          = Auth::user()->id;
            $ret_save=$modified_row->save();
            
            $arc_row->save();
            }
            
        if ($request->id==0)
            $request->id=$modified_row->id;
        
        if (strlen($request->simmed_alternative_title)>255)
            return redirect()->route('simmeds.show',[$request->id, 0])->with('success', 'Dane zosta??y zmienione, aczkolwiek inforamcja zosta??a obci??ta do 255 znak??w');
        else
            return redirect()->route('simmeds.show',[$request->id, 0])->with('success', 'Dane zosta??y zmienione.');

        

    }


    public function descript_update(Request $request)
    {

        if (!Auth::user()->hasRole('Technik'))
           return back()->withErrors('brak uprawnie?? do kopiowania wpis??w (descript update in SimmedController). Tylko dla technika');   
         
        if ($request->id==0)
        {
            $modified_row = new \App\SimmedDescript;
            $modified_row->user_id                          = Auth::user()->id;
        }
        else
        { 

            $modified_row=\App\SimmedDescript::find($request->id);

            $arc_row=new \App\SimmedDescriptArc();
            $arc_row=$modified_row;
            unset($arc_row->id);
        }    

        if ( ($modified_row->simmed_secret == $request->simmed_secret)
            && ($modified_row->simmed_descript == $request->simmed_descript) )
            return back()->withErrors('Nie wykryto ??adnych zmian...');

        $modified_row->simmed_id        = $request->simmed_id;
        $modified_row->simmed_secret    = substr($request->simmed_secret,0,255);
        $modified_row->simmed_descript  = $request->simmed_descript;
        
        $ret_save=$modified_row->save();
        if ($request->id>0)
            $ret_save=$arc_row->save();
        if (strlen($request->simmed_secret)>255)
            return back()->with('success',' Zapis zako??czy?? si?? sukcesem, aczkolwiek inforamcja zosta??a obci??ta do 255 znak??w');
        else
            return back()->with('success',' Zapis zako??czy?? si?? sukcesem.');

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
}
