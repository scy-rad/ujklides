@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Statystyki wszystkich techników" )

<?php
        function m2h($time)
        {
            return floor($time/60).':'.str_pad($time%60, 2, '0', STR_PAD_LEFT);
        }
        function m2h_total($count,$time,$type,$totaltime,$technician_count)
        {
            if ($count==0)
                return '-';
            $sign = $time < 0 ? '-' : '';
            $time = abs($time);
            $return=$sign.floor($time/60).':'.str_pad($time%60, 2, '0', STR_PAD_LEFT);
            $return.=' ['.$count.']';
            if ($totaltime*$technician_count>0)
            $return.=' <br><strong>'.round($time/($totaltime/$technician_count)*100,2).' %</strong>';

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
        <div class="col-sm-3">
            <label for"perspective">perspektywa:</label>
                <select class="form-control col-sm-2" id="perspective" name="perspective">
                    <option value="characters"<?php if ($filtr['perspective']=='characters') echo ' selected="selected"'; ?>> charakter (z L4)</option>
                    <option value="charactersill"<?php if ($filtr['perspective']=='charactersill') echo ' selected="selected"'; ?>> charakter - średnie L4 </option>
                    <option value="charactersnoill"<?php if ($filtr['perspective']=='charactersnoill') echo ' selected="selected"'; ?>> charakter (bez L4) </option>
                    <option value="rooms"<?php if ($filtr['perspective']=='rooms') echo ' selected="selected"'; ?>> sale </option>
                    <option value="leaders"<?php if ($filtr['perspective']=='leaders') echo ' selected="selected"'; ?>> prowadzący </option>
                
                </select>
 
        </div>
        <div class="col-sm-5">
            <label for"transposition">transpozycja:</label><br>
            <input type="checkbox" name="transposition" value="transposition"<?php if ($filtr['transposition']=='transposition') echo 'checked="checked"'; ?>>
        </div>
        <div class="col-sm-4">
            &nbsp;
        </div>
        
        <div class="col-sm-1">
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
        </div>
    </div>
</form>






@if (count($return_table)>0)
@if ($filtr['transposition']<>'transposition')
<table width="100%" class="table">
    <tr>
        <th>poz.</th>
        @foreach ($return_table['total']['technician'] as $row_one)
        <th>{{$row_one['name']}}</th>
        @endforeach
        <th>śred./raz.</th>
    </tr>
    @foreach ($return_table['data'] as $row_one)
    <tr>
        <th>{{$row_one['info']['name']}}</th>
        @foreach ($row_one['data'] as $row_two)
            <td>
                {!!m2h_total($row_two['count'],$row_two['time'],$row_two['name'],
                    $row_one['perspective_total_time']
                    ,$return_table['info']['technicians_count']
                    )!!}
            </td>
        @endforeach
            <td>
                <strong>~ {!!m2h_total(round($row_one['perspective_total_count']/$return_table['info']['technicians_count'],1),
                round($row_one['perspective_total_time']/$return_table['info']['technicians_count'],0)
                ,$row_two['name'],
                    $row_one['perspective_total_time']*0
                    ,$return_table['info']['technicians_count']*0
                    )!!}
                </strong>
                <br>
                {!!m2h_total($row_one['perspective_total_count'],$row_one['perspective_total_time'],$row_two['name'],
                    $row_one['perspective_total_time']*0
                    ,$return_table['info']['technicians_count']*0
                    )!!}

            </td>

    </tr>
    @endforeach

    <tr>
        <th>razem</th>
        @foreach ($return_table['total']['technician'] as $row_one)
        <td>
        {!!m2h_total($row_one['count'],$row_one['time'],$row_two['name'],
                    0
                    ,0
                    )!!}

        </td>
        @endforeach
        <td>
        tech: {{$return_table['info']['technicians_count']}}
        </td>
    </tr>

</table>

        <?php //($filtr['transposition']<>'transposition') ?>
@else

<h1> transpozycja</h1>

<table width="100%" class="table">
    <tr>
        <th>
            technik
        </th>
        @foreach ($return_table['heads'] as $perspective_row)
        <th>
            {{$perspective_row['name']}}
        </th>
        @endforeach
        <th>
            razem
        </th>
    </tr>

    @foreach ($return_table['total']['technician'] as $technician_row)
    <tr>
        <th>
            {{$technician_row['name']}}
        </th>
        @foreach ($return_table['heads'] as $perspective_row)
        <td>
            {!!m2h_total(
                $return_table ['data'] [$perspective_row['perspective_id']] ['data'] [$technician_row['id']] ['count'],
                $return_table ['data'] [$perspective_row['perspective_id']] ['data'] [$technician_row['id']] ['time'],
                $return_table ['data'] [$perspective_row['perspective_id']] ['data'] [$technician_row['id']] ['name'],
                $return_table ['data'] [$perspective_row['perspective_id']] ['perspective_total_time']
                ,$return_table['info']['technicians_count']
                )!!}
        </td>
        @endforeach
        <td>
        {!!m2h_total($technician_row['count'],$technician_row['time'],'nameRST',
                    0
                    ,0
                    )!!}

        </td>
    </tr>
    @endforeach
    <tr>
        <th>
            śr./raz.
        </th>
        @foreach ($return_table['heads'] as $perspective_row)
        <td>
                <strong>~ {!!m2h_total(
                    round( $return_table ['data'] [$perspective_row['perspective_id']] ['perspective_total_count']/$return_table['info']['technicians_count'],1),
                    round( $return_table ['data'] [$perspective_row['perspective_id']] ['perspective_total_time']/$return_table['info']['technicians_count'],0),
                    'nameX',
                    0,
                    0
                    )!!}
                </strong>
                <br>
                {!!m2h_total(
                    $return_table ['data'] [$perspective_row['perspective_id']] ['perspective_total_count'],
                    $return_table ['data'] [$perspective_row['perspective_id']] ['perspective_total_time'],
                    'nameY',
                    0,
                    0
                    )!!}

        </td>
        @endforeach
        <td>
        tech: {{$return_table['info']['technicians_count']}}
        </td>
    </tr>
</table>


@endif
@endif

@endsection


