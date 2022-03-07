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
            <td>{!!m2h($tab_one_current,$total['to_date'])!!}

            </td>
        @endforeach
    </tr>
    @endforeach
     
 
</table>
<hr>
<table width="50%" class="table">
@foreach ($total['to_date'] as $tab_one)
<tr><td>
{{$tab_one['type']}}
</td>
<td>
<?php $tab_one['time']=$tab_one['time']/8; ?>
{!!m2h($tab_one,$total['to_date'])!!}
</td></tr>
@endforeach
</table>

@endsection


