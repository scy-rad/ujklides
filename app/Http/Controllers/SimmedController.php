<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Simmed;
use App\User;
use App\SimmedArcTechnician;
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
                $simmeds =  Simmed::where('simmed_status','<>',4)->where('simmed_date','>=',date('Y-m-d'))->orderBy('simmed_date')->orderBy('simmed_time_begin')->get();
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
    
        $sch_date=$request['date'];
        $sch_csm=$request['csm'];

        if (!(validateDate($sch_date, 'Y-m-d')))
            $sch_date=date('Y-m-d');

        $do_poniedzialku=date('N',strtotime($sch_date))-1;
        $sch_date=date('Y-m-d',strtotime("$sch_date - $do_poniedzialku day"));
        $sch_date_to=date('Y-m-d',strtotime("$sch_date +6 day"));
        $sch_date_next=date('Y-m-d',strtotime("$sch_date +7 day"));
        $sch_date_prev=date('Y-m-d',strtotime("$sch_date -7 day"));
    
            //$rows_plane=Simmed::simmeds_for_plane($sch_date); -  ta funkcja będzie do wywalenia, bo zmieniłem skrypt datatable
            $rows_plane=Simmed::select('*','simmeds.id as sim_id')->where('simmed_date','>=',$sch_date)->where('simmed_date','<=',$sch_date_to)
                ->where('simmed_status','<',4);
            if ($sch_csm<0)
                //dump($request);
                $rows_plane=$rows_plane->whereNull('student_group_id');
            if ($sch_csm>0)
                //dump($request);
                $rows_plane=$rows_plane->join('student_groups', 'simmeds.student_group_id', '=', 'student_groups.id')->where('center_id','=',$sch_csm);
            

                $rows_plane=$rows_plane->orderBy('simmed_date')
                ->orderBy('simmed_time_begin')
                ->orderBy('room_id')
                ->get();

        $technician_list=User::role_users('technicians', 1, 1)->get();
        $center_list=\App\Center::all();

    return view('simmeds.plane', compact('rows_plane'),['sch_csm' => $sch_csm, 'sch_date' => $sch_date, 'sch_date_next' => $sch_date_next, 'sch_date_prev' => $sch_date_prev, 'technician_list' => $technician_list, 'center_list' => $center_list]);
    }

    public function scheduler( string $sch_date)
    {
        //$simmeds =  Simmed::all();
        $rows_scheduler=Simmed::simmeds_for_scheduler($sch_date);
        return view('simmeds.scheduler', compact('rows_scheduler'),['sch_date' => $sch_date]);
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

        $role = new SimmedArcTechnician();
        $role->simmed_id = $request->id;
        $role->technician_id = $request->technician_id*1;
        $role->user_id = Auth::user()->id;
        $role->save();


        
        //return json_encode(array('statusCode'=>$request->id, 'status'=> $status));
        //return Json(new { result = true });
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
        return view('simmeds.show', compact('simmed'));
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
        echo '<h1>funkcja EDIT Simmed Controller</h1>';
        //return view('simmeds.edit', compact('simmed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Simmed $simmed)
    {
        echo '<h1>funkcja UPDATE Simmed Controller</h1>';
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
