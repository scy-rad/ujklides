@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Czas pracy "." "." - technicy" )

<?php
        function m2h($min)
        {
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT);
        }

?>
@section('content')

<div class="container">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {!! $message !!}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Uuuups!</strong> Przecież to nie powinno się wydarzyć!<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>


    <form action="{{ route('worktime.month') }}" method="get">
        <div class="row">
        <div class="col-sm-2">
            <label for"technician">pracownik:</label><br>
            <select class="form-control" name="technician">
            @foreach (app\user::role_users('workers', 1, 0)
               ->orderBy('name')->get() as $tech_one)
                <option value="{{$tech_one->id}}"<?php if ($filtr['user']==$tech_one->id) echo ' selected'?>>{{$tech_one->name}} [{{$tech_one->full_name()}}]</option>
            @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <label for"month">miesiąc:</label><br>
            <select class="form-control" name="month">
            @foreach ($months as $month_one)
                <option value="{{$month_one}}" <?php if ($filtr['month']==$month_one) echo ' selected'?>>{{$month_one}}</option>
            @endforeach
            </select>
        </div>
        <div class="col-sm-1">
            <label for"csv">csv.:</label><br>
                <input class="form-control" type="checkbox" name="csv" value="csv">
        </div>
        <div>
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
    </div>
    </div>
    </form>

    <div class="row">
        <div class="col-sm-4"><h3>{{$user->full_name()}}</h3>
        <h4>czas pracy: {{$total['times']}}</h4>
        <h4>planowo godzin: {{$total['month_data']->hours_to_work}}</h4>
        @if ($total['month_data']['minutes_worked']!=$total['minutes'])
            <form action="{{ route('worktime.month') }}" method="get">
                <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
                <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
                <input class="form-control" type="hidden" name="workcard" value="calculate">
                <input class="btn btn-danger btn-big col-sm-12" type="submit" value="PRZELICZ CZAS PRACY ({{$total['month_data']['minutes_worked']}}!={{$total['minutes']}})">
            </form>
        @endif
        </div>
        <div class="col-sm-5">
            <table class="table table-dark">
                <thead>
                    <tr>
                    <td>charakter</td>
                    <td>ilość</td>
                    <td>czas</td>
                    </tr>
                </thead>
            @foreach ($total['work_characters_month'] as $total_one)
                <tr>
                    <td> {{$total_one->worktime_type}} </td>
                    <td> {{$total_one->worktime_count}} </td>
                    <td> {{m2h($total_one->worktime_minutes)}} </td>
                </tr>
                
            @endforeach
            </table>
        </div>
        <div class="col-sm-3 text-right">
            @if ( (Auth::user()->hasRole('Operator Kadr')) &&
                    (\App\WorkAttendance::where('date','=',$filtr['month'].'-01')->get()->first() === null) )
            <form action="{{ route('worktime.month') }}" method="get">
                <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
                <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
                <input class="form-control" type="hidden" name="workcard" value="generate">
                <input class="btn btn-primary btn-big col-sm-12" type="submit" value="generuj kartę czasu pracy">
            </form>
            <br><br>
            @endif

            <form action="{{ route('worktime.month') }}" method="get">
                <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
                <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
                <input class="form-control" type="hidden" name="workcard" value="get">
                <input class="btn btn-primary btn-big col-sm-12" type="submit" value="wydruk dokumentacji czasu pracy">
            </form>
            <br><br>
            <form action="{{ route('worktime.month') }}" method="get">
                <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
                <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
                <input class="form-control" type="hidden" name="workcard" value="changes">
                <input class="btn btn-warning btn-big col-sm-12" type="submit" value="pokaż różnice">
            </form>
        </div>
    </div>


    <table class="table table-striped thead-dark">
        <thead>
            <tr>
            <th scope="col">data</th>
            <th scope="col">plan</th>
            <th scope="col">symulacje</th>
            <th scope="col">czas pracy</th>
            <th scope="col">godz.</th>
            </tr>
        </thead>
    @foreach ($tabelka as $row_one)
    <tr>
        <td>
        <a href="/scheduler/{{$row_one['date']}}">
            {{$row_one['date']}}
            <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
        </a>
            {{$row_one['day_name_short']}}
       </td>
        @if ($row_one['hr_diffrent']===false)
        <td> <span class="text-danger" style="background-color: yellow"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
        @else
       <td> <span>
        @endif
       {{$row_one['hr_wt']['time_begin']}} - {{$row_one['hr_wt']['time_end']}}
       </span>
       </td>
        <td>
        @foreach ($row_one['sims'] as $sim_one)
            <strong>{{$sim_one['time']}} {{$sim_one['room_number']}} ({{$sim_one['character_short']}}) </strong><br>
            {{$sim_one['student_subject_name']}}<br>     
        @endforeach
        </td>
        <td>
            <a href="{{route('worktime.day_data', [ $row_one['date'], $user->id ])}}">
            @foreach ($row_one['times'] as $time_one)
                {{$time_one['start']}} - {{$time_one['end']}}<br>
            @endforeach
            <span class="glyphicon glyphicon glyphicon-briefcase text-success" aria-hidden="true"></span>
            </a>
            @if (count($row_one['work_types'])==1)
            {{array_values($row_one['work_types'])[0]}}
            @endif
        </td>
        <td>
            {{$row_one['hoursmin']}}
        </td>

    </tr>
    @endforeach
    </table>

    

    <button type="button" class="btn btn-info btn-lg" id="myBtn">Open Modal</button>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>

function OpenModal(cos) {


    zmiana = document.getElementById('exampleModalLabel');

        zmiana.innerHTML = cos;
        //zmiana.parentNode.insertAdjacentHTML('beforeEnd',cos);

    $("#myModal").modal();
}

$(document).ready(function(){
  $("#myBtn").click(function(){
    $("#myModal").modal();
  });
});
</script>


@endsection


