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

    <form action="{{ route('worktime.month') }}" method="get">
        <div class="row">
        <div class="col-sm-2">
            <label for"technician">technik:</label><br>
            <select class="form-control" name="technician">
            @foreach (app\user::role_users('technicians', 1, 0)
               ->where('id','<>',\App\Param::select('*')->orderBy('id','desc')->get()->first()->technician_for_simmed)
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

    <table class="table table-dark">
    <thead>
    <tr>
        <td><h3>{{$user->full_name()}}</h3></td>
        <td>
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
        </td>
        <td class="text-right">
        <h3>czas pracy: {{$total['times']}}</h3>

        <h3>planowo godzin: {{$total['month_data']->hours_to_work}}</h3>
        @if (Auth::user()->hasRole('Operator Kadr'))
        <form action="{{ route('worktime.month') }}" method="get">
            <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
            <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
            <input class="form-control" type="hidden" name="workcard" value="generate">
            <input class="btn btn-primary btn-big" type="submit" value="generuj kartę czasu pracy">
        </form>
        @endif

        <form action="{{ route('worktime.month') }}" method="get">
            <input class="form-control" type="hidden" name="technician" value="{{$filtr['user']}}">
            <input class="form-control" type="hidden" name="month" value="{{$filtr['month']}}">
            <input class="form-control" type="hidden" name="workcard" value="get">
            <input class="btn btn-primary btn-big" type="submit" value="pobierz kartę czasu pracy">
        </form>


        </td>
    </tr>
    </thead>
    </table>

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


