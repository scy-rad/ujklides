@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki "." "." - technicy" )

<?php
        function m2h($time)
        {
            return floor($time/60).':'.str_pad($time%60, 2, '0', STR_PAD_LEFT);
        }
        function m2h_total($time,$total)
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

<div class="row text-right">
<a href="{{ route('worktime.statpertech') }}">Statystyki - technicy</a>
</div>
                                    
<form action="{{ route('worktime.statistics') }}" method="get">
    <div class="row">
        <div class="col-sm-3">
            <label for"start">od-do:</label><br>
            <input type="date" name="start" value="{{$filtr['start']}}">
            <input type="date" name="stop" value="{{$filtr['stop']}}">
        </div>
        <div class="col-sm-1">
            <label for"character">charakter:</label> 
                <select class="form-control col-sm-2" id="character" name="character">
                    <option value="777">      </option>
                   @foreach ($technician_char as $row_one)
                   <option value="{{$row_one->id}}"<?php if ($row_one->id==$filtr['character']) echo ' selected="selected"'; ?>>{{$row_one->character_short}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-2">
            <label for"technician">technik:</label> 
                <select class="form-control" id="technician" name="technician">
                    <option value="777">      </option>
                    @foreach ($technicians_list as $row_one)
                    <option value="{{$row_one->id*1}}"<?php if ($row_one->id*1==$filtr['technician']) echo ' selected="selected"'; ?>>{{$row_one->name}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-2">
            <label for"instructor">instruktor:</label> 
                <select class="form-control" id="instructor" name="instructor">
                    <option value="777">      </option>
                    @foreach ($instructors_list as $row_one)
                    <option value="{{$row_one->id*1}}"<?php if ($row_one->id*1==$filtr['instructor']) echo ' selected="selected"'; ?>>{{$row_one->lastname}} {{$row_one->firstname}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-2">
            <label for"subject">przedmiot:</label> 
                <select class="form-control" id="subject" name="subject">
                    <option value="777">      </option>
                    @foreach ($subjects_list as $row_one)
                    <option value="{{$row_one->id*1}}"<?php if ($row_one->id*1==$filtr['subject']) echo ' selected="selected"'; ?>>{{$row_one->student_subject_name}}</option>
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
        
        <div class="col-sm-1">
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
        </div>
    </div>
</form>



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
    <?php $total_min=0; ?>
    @foreach ($extra_tab as $row_one)
            <?php $total_min+=$row_one->time_minutes; ?>
        <tr>
        <td>    <a href="/scheduler/{{$row_one->simmed_date}}">
            {{ $row_one->simmed_date }}
            <span class="glyphicon glyphicon glyphicon-tasks" aria-hidden="true"></span>
            </a>
       </td>
        <td>    {{$row_one->DayOfWeek}}   </td>
        <td>    

            <a href="{{route('simmeds.show', [$row_one->id, 0])}}">
            {{$row_one->time}}  ({{m2h($row_one->time_minutes)}})
                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
            </a>


        </td>
        <td>    {{$row_one->room_number}}   </td>
        <td>    {{$row_one->leader}}   </td>
        <td>    {{$row_one->technician_name}}   </td>
        <td>    {{$row_one->character_short}}   </td>
        <td>    {{$row_one->student_subject_name}}   </td>
        <td>    {{$row_one->student_group_code}}   </td>
        </tr>
    @endforeach
        <tr>
        <th>       </th>
        <th>       </th>
        <th><h3>{{m2h($total_min)}}</h3>   </th>
        <th>       </th>
        <th>       </th>
        <th>       </th>
        <th>       </th>
        <th>       </th>
        <th>       </th>
        </tr>
    </table>
    
@endif


@endsection


