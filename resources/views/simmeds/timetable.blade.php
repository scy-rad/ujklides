@extends('layouts.app')

@section('title', "terminarz symulacji od dnia $start_date")
@section('content')


<hr>
<?php 
if (date('d',strtotime($start_date))=='01')
  $date_prev=date('Y-m-d',strtotime("$start_date - 1 month"));
else
  $date_prev=date('Y-m',strtotime("$start_date")).'-01';

$date_next=date('Y-m',strtotime("$start_date + 1 month")).'-01';
?>

<hr>
<div class="row">
  <div class="col-sm-1">
  </div>
  <div class="col-sm-10">
    @switch ($what_name)
      @case ('room')
        <h2>pokój: {{App\Room::where('id',$what_no)->first()->room_number}}</h2>
      @break
      @case ('instructors')
        <h2>instruktor: {{App\User::where('id',$what_no)->first()->title->user_title_short}} {{App\User::where('id',$what_no)->first()->firstname}} {{App\User::where('id',$what_no)->first()->lastname}}</h2>
      @break
      @case ('technicians')
        <h2>technik: {{App\User::where('id',$what_no)->first()->title->user_title_short}} {{App\User::where('id',$what_no)->first()->firstname}} {{App\User::where('id',$what_no)->first()->lastname}}</h2>
      @break
    @endswitch
        </div>
</div>
<div class="row">
  <div class="col-sm-1">
  </div>
  <div class="col-sm-1">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="{{$what_name}}">
      <input name="what_no" type="hidden" value="{{$what_no}}">
      <input name="start_date" type="hidden" value="{{$date_prev}}">
      <input type="submit" value="{{$date_prev}}">
    </form>
  </div>
  <div class="col-sm-1">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="{{$what_name}}">
      <input name="what_no" type="hidden" value="{{$what_no}}">
      <input name="start_date" type="hidden" value="{{$date_next}}">
      <input type="submit" value="{{$date_next}}">
    </form>
  </div>

  <div class="col-sm-2">
  </div>

  <div class="col-sm-1">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="all">
      <input name="start_date" type="hidden" value="{{$start_date}}">
      <input type="submit" value="pokaż wszystko">
    </form>
  </div>
  <div class="col-sm-2">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="room">
      <select name="what_no">
        @foreach (App\Room::where('room_xp_code','<>','')->orderBy('room_number')->get() as $room)
          <option value="{{$room->id}}"<?php if ($room->id==$what_no) echo 'selected="selected"'; ?>>{{$room->room_number}}</option>      
        @endforeach
      </select>
      <input name="start_date" type="hidden" value="{{$start_date}}">
      <input type="submit" value="pokaż pokój">
    </form>
  </div>

  <div class="col-sm-2">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="instructors">
      <select name="what_no">
        <?php if ($what_name=='instructors') $what_sel=$what_no; else $what_sel=5; ?>
        @foreach (App\User::role_users('instructors', '1', null)->get() as $user)
          <option value="{{$user->id}}"<?php if ($user->id==$what_sel) echo 'selected="selected"'; ?>>{{$user->lastname}} {{$user->firstname}}, {{$user->title->user_title_short}}</option>      
        @endforeach
      </select>
      <input name="start_date" type="hidden" value="{{$start_date}}">
      <input type="submit" value="pokaż instruktora">
    </form>
  </div>

  <div class="col-sm-2">
    <form action="/timetable">
      <input name="what_name" type="hidden" value="technicians">
      <select name="what_no">
        <?php if ($what_name=='technicians') $what_sel=$what_no; else $what_sel=5; ?>
        @foreach (App\User::role_users('technicians', '1', null)->get() as $user)
          <option value="{{$user->id}}"<?php if ($user->id==$what_sel) echo 'selected="selected"'; ?>>{{$user->lastname}} {{$user->firstname}}, {{$user->title->user_title_short}}</option>      
        @endforeach
      </select>
      <input name="start_date" type="hidden" value="{{$start_date}}">
      <input type="submit" value="pokaż technika">
    </form>
  </div>

</div>

<style>
    .calendar_default_colheader_inner{
            color: #2c0000;
            border: white solid 1px;
            font-size: 14px;
            //background-color: #c7ae50;
        }
</style>
<div class="row">
<div id="dp"></div>
</div>


<script src="{{URL::asset('js/daypilot-lite/scripts/daypilot-all.js')}}" type="text/javascript"></script>


<script>
  var dp = new DayPilot.Calendar("dp");
  dp.startDate = "{{$start_date}}";
  //dp.viewType = "Week";
  dp.viewType = "Days";
  dp.days = 35,
  dp.locale = "pl-pl";
  dp.headerDateFormat = "dd-MM   ddd";
  //dp.headerHeightAutoFit = false;
  dp.headerHeight="60";
  dp.hideFreeCells = true;
  
  dp.events.list = {!!$rows_timetable!!}
  /* [
    {
      "start": "2021-02-21T10:30:00",
      "end": "2021-02-21T13:30:00",
      "id": "225eb40f-5f78-b53b-0447-a885c8e92233",
      "text": "Calendar Event 1"
    },
    {
      "start": "2021-02-22T12:30:00",
      "end": "2021-02-22T15:00:00",
      "id": "1f67def5-e1dd-57fc-2d39-eb7a5f8e789a",
      "text": "Calendar Event 2"
    },
    {
      "start": "2021-02-23T10:30:00",
      "end": "2021-02-23T16:00:00",
      "id": "aba78fd9-09d0-642e-612d-0e7e002c29f5",
      "text": "Calendar Event 3"
    }
  ] */
  ;
  dp.init();


  function loadEvents() {
  DayPilot.Http.ajax({
    url: "backend_events.php?start=" + dp.visibleStart() + "&end=" + dp.visibleEnd(),   // in .NET, use "/api/CalendarEvents"
    success: function(data) {
        dp.events.list = data;
        dp.update();
    }
  });
}

// https://code.daypilot.org/17910/html5-event-calendar-open-source
</script>

@endsection
