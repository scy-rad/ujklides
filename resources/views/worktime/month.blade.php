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
        <select name="technician">
        @foreach (app\user::role_users('technicians', 1, 0)->get() as $tech_one)
            <option value="{{$tech_one->id}}"<?php if ($filtr['user']==$tech_one->id) echo ' selected'?>>{{$tech_one->name}} [{{$tech_one->full_name()}}]</option>
        @endforeach
        </select>

        <select name="month">
        @foreach ($months as $month_one)
            <option value="{{$month_one}}" <?php if ($filtr['month']==$month_one) echo ' selected'?>>{{$month_one}}</option>
        @endforeach
        </select>

        <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
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
                    <td> {{m2h($total_one->worktime_hours*60 + $total_one->worktime_minutes)}} </td>
                </tr>
                
            @endforeach
            </table>
        </td>
        <td class="text-right"><h3>czas pracy: {{$total['times']}}</h3></td>
    </tr>
    </thead>
    </table>

    <table class="table table-striped thead-dark">
        <thead>
            <tr>
            <th scope="col">data</th>
            <th scope="col">symulacje</th>
            <th scope="col">czas pracy</th>
            <th scope="col">godz.</th>
            </tr>
        </thead>
    @foreach ($tabelka as $row_one)
    <tr>
        <td>
        {{$row_one['date']}}
        </td>
        <td>
        @foreach ($row_one['sims'] as $sim_one)
            <strong>{{$sim_one->time}} {{$sim_one->room_number}} ({{$sim_one->character_short}}) </strong><br>
            {{$sim_one->student_subject_name}}<br>
            
        @endforeach
        </td>
        
        <td>
            {{$row_one['time_start']}} - {{$row_one['time_stop']}}
        </td>
        <td>
            {{$row_one['times']}}
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

