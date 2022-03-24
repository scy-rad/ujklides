@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki wszystkich techników" )

<?php
        function m2h($time)
        {
            return floor($time/60).':'.str_pad($time%60, 2, '0', STR_PAD_LEFT);
        }
        function m2h_total($count,$time,$type,$total,$technician_count)
        {
            if ($count==0)
                return '-';
            $sign = $time < 0 ? '-' : '';
            $time = abs($time);
            $return=$sign.floor($time/60).':'.str_pad($time%60, 2, '0', STR_PAD_LEFT);
            $return.=' ['.$count.']';
            $return.=' <strong>'.round($time/($total[$type]['time']/$technician_count)*100,2).' %</strong>';

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
            <td>
                {!!m2h_total($tab_one_current['count'],$tab_one_current['time'],$tab_one_current['type'],$total['current'],$total['technicians_count'])!!}
                    @if (isset($tab_one_current['sick_average']))
                        @if ($tab_one_current['sick_average']>0)
                        {{$tab_one_current['type']}}
                        <br>(L4: {{m2h($tab_one_current['sick_average'])}})
                        @endif
                    @endif
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
                {!!m2h_total($tab_one['count'],$tab_one['time'],$tab_one['type'],$total['current'],$total['technicians_count'])!!} / {{$total['technicians_count']}}
                @if (isset($tab_one['sick_time']))
                <br>
                ( w tym L-4: <strong>{!!m2h($tab_one['sick_time'])!!}</strong>)
                @endif

            </td>
            <td>
                <?php $tab_one['time']=$tab_one['time']/$total['technicians_count']; ?>
                {!!m2h_total($tab_one['count'],$tab_one['time'],$tab_one['type'],$total['current'],$total['technicians_count'])!!}
            </td>
        </tr>
    @endforeach
</table>

@endsection


