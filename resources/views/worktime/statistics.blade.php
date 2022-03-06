@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki "." "." - technicy" )

<?php
        function m2h($min,$count)
        {
            if ($min==0)
                return '-';
            $sign = $min < 0 ? '-' : '';
            $min = abs($min);
            return $sign.floor($min/60).':'.str_pad($min%60, 2, '0', STR_PAD_LEFT).' ['.$count.']';
        }

?>
@section('content')
<table width="100%" class="table">
    <tr>
        <td>Imię i nazwisko</td>
        @foreach ($characters as $character)
        <td>{{$character->character_short}}</td>
        @endforeach
    </tr>
    @foreach ($tabelka as $tab_one)
    <tr>
        <td>{{$tab_one['name']}}</td>
        @foreach ($tab_one['to_date'] as $tab_one_current)
            <td>{{m2h($tab_one_current['time'],$tab_one_current['count'])}}</td>
        @endforeach
    </tr>
    @endforeach
     
</table>

@endsection


