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
    public function index(string $simtype)
    {
        switch ($simtype)
        {
            case 'all':
                $simmeds = Simmed::where('simmed_status','<>',4)->orderBy('simmed_date')->orderBy('simmed_time_begin')->get()
                ;
            break;
            case 'now':
                $simmeds =  Simmed::where('simmed_status','<>',4)->where('simmed_date','>=',date('Y-m-d'))->where('simmed_date','<=',date('Y-m-d',strtotime( date('Y-m-d') .' +7 day' )))->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();
            break;
        }
        return view('simmeds.index', compact('simmeds'));
    }


    public function plane(Request $request)
    {

        //to chyba realizuje już tylko funkacja ajax poniżej
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
        //dump('tu trzeba zmienić na +6 dni zamiast +1 - zmienione na czas testów tylko');
        $ret['sch_date_to']=date('Y-m-d',strtotime("$ret_sch_date +6 day"));
        $ret['sch_date_next']=date('Y-m-d',strtotime("$ret_sch_date +7 day"));
        $ret['sch_date_prev']=date('Y-m-d',strtotime("$ret_sch_date -7 day"));

            //$rows_plane=Simmed::simmeds_for_plane($ret_sch_date); -  ta funkcja będzie do wywalenia, bo zmieniłem skrypt datatable
            $rows_plane=Simmed::select('*','simmeds.id as sim_id')->where('simmed_date','>=',$ret_sch_date)->where('simmed_date','<=',$ret['sch_date_to'])
                ->where('simmed_status','<',4);
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
            $ret['technician_char'][$row['id']]=$row;   //zmiana, żeby id wiersza było id tabeli

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

    return redirect()->route('simmeds.index')->with('success', 'Dane zostały dodane.');

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

        //return json_encode(array('statusCode'=>$request->id, 'status'=> $status));
        //return Json(new { result = true });
        return json_encode(array('result'=>false, 'tescik' =>'przykladowy_tekst', 'statusx'=> $status));
     }


    public function ajaxtechnicianchar(Request $request) 
    {
        $status = DB::table('simmeds')
       ->where('id', $request->id)
       ->update(['simmed_technician_character_id' => $request->character_id]);
       return json_encode(array('result'=>false, 'tescik' =>'przykladowy_tekst', 'statusx'=> $status));
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Simmed $simmed)
    {
        //echo '<h1>funkcja SHOW Simmed Controller</h1>';
        
        $history['technician_history'] = SimmedArcTechnician::where('simmed_id', $simmed->id)
        ->get();
        $history['simmed_history'] = \App\SimmedArc::where('simmed_id', $simmed->id)
        ->get();
 
//        dump($technician_history);

        return view('simmeds.show', compact('simmed'),$history);
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
        $ret['technicians_list']=User::role_users('technicians', 1, 1)->get();
        $ret['leaders_list']=User::role_users('instructors', 1, 1)->get();
        $ret['subjects_list']=\App\StudentSubject::where('student_subject_status',1)->orderBy('student_subject_name')->get();
        //$ret['rooms_list']=\App\Room::where('room_status',1)->orderBy('room_number')->get();
        $ret['rooms_list']=\App\Room::where('room_xp_code','<>','')->orderBy('room_number')->get();
        $ret['status_list']=Simmed::status_table();
        $ret['technician_characters_list']=\App\TechnicianCharacter::orderBy('id')->get();
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
        $modified_row=SiMmed::find($request->id);

// dump($request);
// dump($request->simmed_technician_id);
// dd();

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
        $arc_row->change_code                       = 20; //edycja ręczna
        $arc_row->simmed_id                         = $modified_row->id;
        $arc_row->user_id                           = $modified_row->user_id;
        

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
        if ($request->simmed_technician_id==0)
            $modified_row->simmed_technician_id    		= null;
        else
            $modified_row->simmed_technician_id    		= $request->simmed_technician_id;
        $modified_row->simmed_technician_character_id   = $request->simmed_technician_character_id;
        $modified_row->simmed_alternative_title		    = $request->simmed_alternative_title;
        $modified_row->simmed_status 					= $request->simmed_status;
        $modified_row->user_id                          = Auth::user()->id;
        
        $ret_save=$modified_row->save();

        if (count($modified_row->getChanges())>0 )
            $arc_row->save();
        
        $history['technician_history'] = SimmedArcTechnician::where('simmed_id', $modified_row->id)
        ->get();
        $history['simmed_history'] = \App\SimmedArc::where('simmed_id', $modified_row->id)
        ->get();
        $simmed=$modified_row;
        return view('simmeds.show', compact('simmed'),$history);
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
