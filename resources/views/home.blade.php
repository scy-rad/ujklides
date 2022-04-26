@extends('layouts.app')
<?php 
$curr_date='';
$dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );

function rowek($simmed)
    {
        //dump($simmed);
        $link=[$simmed->id, 0];
    ?>
        <div class="row border border-dark" style="border-bottom: 0.25rem solid green;">
            <div class="col-xs-2">
                <a href="{{route('simmeds.show', $link)}}"> 
                    {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }} 
                    <span class="glyphicon glyphicon-list-alt text-success" aria-hidden="true"></span>
                </a>
                <nobr><strong >{{ $simmed->room()->room_number }}</strong></nobr>
            </div>
            <div class="col-xs-2"> 
                {{ $simmed->name_of_leader() }}
            </div>
            <div class="col-md-2">
                {{ $simmed->name_of_student_subject() }}
            </div>
            <div class="col-md-1 text-light bg-dark">
                {{$simmed->code_of_student_group()}}
            </div>
            <div class="col-md-4">
                @if ( Auth::user()->hasRole('Technik') && ($simmed->descript()!=null))
                    <span class="text-danger"><strong>{{$simmed->descript()->simmed_secret}}</strong></span><br>
                @endif
                {!!$simmed->simmed_alternative_title!!}
            </div>
            <div class="col-sm-1 bg-primary">
                {{$simmed->technician_character()->character_name}}
                </div>
        </div>
    <?php
    }
?>


@section('content')

<div class="panel panel-default">
    <!--div class="panel-heading"><img src="{{ asset('img/cmscsm/csm_ujk.svg') }}" width="50%"></div-->

    <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (Auth::user()->hasRole('Technik'))
            @foreach ($home_data as $row_one)
                <div class="row bg-warning">
                    <div class="col-xs-6">
                    <h3>
                    <a href="/scheduler/{{$row_one['date']}}">
                        {{$row_one['date']}} 
                        <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
                        ({{$row_one['wdname'] }})
                    </a>
                    </h3>
                    </div>
                    <div class="col-xs-6">
                    <h3>
                    <span style="font-size: 1.25rem">
                        @foreach ($row_one['work_times']['work_types'] as $row_time)
                            [{{$row_time}}]
                        @endforeach
                    </span>
                    <a href="{{route('worktime.day_data', [ $row_one['date'], Auth::user()->id ])}}">
                        @foreach ($row_one['work_times']['times'] as $row_time)
                            [{{$row_time['start']}} - {{$row_time['end']}}]
                        @endforeach
                        <span class="glyphicon glyphicon glyphicon-briefcase text-success" aria-hidden="true"></span>
                    </a>
                    </h3>
                    </div>
                </div>
                @foreach ($row_one['simmeds'] as $simmed)                 
                    <?php rowek($simmed); ?>
                @endforeach
            @endforeach
        @endif
    </div>
</div>

@endsection
