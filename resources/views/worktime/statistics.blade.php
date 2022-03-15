@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki "." "." - technicy" )

<?php
        function m2h($time,$total)
        {
            if ($time['count']==0)
                return '-';
            $sign = $time['time'] < 0 ? '-' : '';
            $time['time'] = abs($time['time']);
            $return=$sign.floor($time['time']/60).':'.str_pad($time['time']%60, 2, '0', STR_PAD_LEFT);
            $return.=' ['.$time['count'].']';
            $return.=' <strong>'.round($time['time']/($total[$time['type']]['time']/8)*100,2).' %</strong>';

            return $return;
        }

?>
@section('content')





<form action="{{ route('worktime.statistics') }}" method="get">
    <div class="row">
        <div class="col-sm-2">
            <label for"start">od:</label><br>
            <input type="date" name="start" value="{{$filtr['start']}}">
        </div>
        <div class="col-sm-2">
            <label for"stop">do:</label><br> 
            <input type="date" name="stop" value="{{$filtr['stop']}}">
        </div>
        <div class="col-sm-3">
            <label for"technician">technik:</label> 
                <select class="form-control" id="technician" name="technician">
                    <option value="777">      </option>
                    @foreach ($technician_list as $row_one)
                    <option value="{{$row_one->id*1}}"<?php if ($row_one->id*1==$filtr['technician']) echo ' selected="selected"'; ?>>{{$row_one->name}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-2">
            <label for"character">charakter:</label> 
                <select class="form-control col-sm-2" id="character" name="character">
                    <option value="777">      </option>
                   @foreach ($technician_char as $row_one)
                   <option value="{{$row_one->id}}"<?php if ($row_one->id==$filtr['character']) echo ' selected="selected"'; ?>>{{$row_one->character_short}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-1">
            <label for"room">sala:</label> 
                <select class="form-control col-sm-2" id="room" name="room">
                    <option value="777">      </option>
                   @foreach ($room_list as $row_one)
                   <option value="{{$row_one->id}}"<?php if ($row_one->id==$filtr['room']) echo ' selected="selected"'; ?>>{{$row_one->room_number}}</option>
                    @endforeach
                </select>
        </div>
        
        <div class="col-sm-2">            
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
        </div>
    </div>
</form>



<table width="100%" class="table">
    <tr>
        <th>Imię i nazwisko</th>
        @foreach ($characters as $character)
        <th>{{$character->character_short}}</th>
        @endforeach
    </tr>
    @foreach ($tabelka as $tab_one)
    <tr>
        <td>{{$tab_one['name']}}</td>
        @foreach ($tab_one['current'] as $tab_one_current)
            <td>{!!m2h($tab_one_current,$total['current'])!!}

            </td>
        @endforeach
    </tr>
    @endforeach
     
 
</table>
<hr>
<table class="table table-dark" style="background-color: #dfd">
    @foreach ($total['current'] as $tab_one)
        <tr>
            <td>
               {{$tab_one['type']}}
            </td>
            <td>
                {!!m2h($tab_one,$total['current'])!!} / 8
            </td>
            <td>
                <?php $tab_one['time']=$tab_one['time']/8; ?>
                {!!m2h($tab_one,$total['current'])!!}
            </td>
        </tr>
    @endforeach
</table>


@if (!is_null($extra_tab))
    <table class="table" data-toggle="table" data-search="false">
        <tr>
        <th>    data   </th>
        <th>    dzień  </th>
        <th>    czas   </th>
        <th>    sala   </th>
        <th>    inst   </th>
        <th>    tech   </th>
        <th>    char   </th>
        <th>    tem    </th>
        <th>    grupa  </th>
        </tr>

    @foreach ($extra_tab as $row_one)
        <tr>
        <td>    {{$row_one->simmed_date}}   </td>
        <td>    {{$row_one->DayOfWeek}}   </td>
        <td>    {{$row_one->time}}   </td>
        <td>    {{$row_one->room_number}}   </td>
        <td>    {{$row_one->leader}}   </td>
        <td>    {{$row_one->technician_name}}   </td>
        <td>    {{$row_one->character_short}}   </td>
        <td>    {{$row_one->student_subject_name}}   </td>
        <td>    {{$row_one->student_group_code}}   </td>
        </tr>
    @endforeach
    </table>

@endif


@endsection


