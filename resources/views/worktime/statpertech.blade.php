@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki wszystkich techników" )

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
<a href="{{ route('worktime.statistics') }}">Statystyki...</a>
</div>


<form action="{{ route('worktime.statpertech') }}" method="get">
    <div class="row">
        <div class="col-sm-3">
            <label for"start">od-do:</label><br>
            <input type="date" name="start" value="{{$filtr['start']}}">
            <input type="date" name="stop" value="{{$filtr['stop']}}">
        </div>
        <div class="col-sm-1">
            <label for"character">charakter:</label> 
        </div>
        <div class="col-sm-2">
            <label for"technician">technik:</label> 
        </div>
        <div class="col-sm-2">
            <label for"instructor">instruktor:</label> 
        </div>
        <div class="col-sm-2">
            <label for"subject">przedmiot:</label> 
        </div>
        <div class="col-sm-1">
            <label for"room">sala:</label> 
        </div>
        
        <div class="col-sm-1">
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
            <td>{!!m2h_total($tab_one_current,$total['current'])!!}

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
                {!!m2h_total($tab_one,$total['current'])!!} / 8
            </td>
            <td>
                <?php $tab_one['time']=$tab_one['time']/8; ?>
                {!!m2h_total($tab_one,$total['current'])!!}
            </td>
        </tr>
    @endforeach
</table>

@endsection


