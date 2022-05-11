@extends('layouts.app')
<?php 
function rowek($simmed)
    {
        $link=[$simmed->id, 0];
    ?>
        <div class="row border border-dark" style="height: 100%;">
            <div class="col-md-1 col-sm-4 col-xs-8 {{$simmed->character_colour}}">
                <a style="background: white; color: blue; font-weight: bold; padding: 0.2rem; border: solid 0.1rem black; border-radius: 3px;" href="{{route('simmeds.show', $link)}}"> 
                    {{ $simmed->time }}
                    <span class="glyphicon glyphicon-list-alt text-success" aria-hidden="true"></span>
                </a>
            </div>
            <div class="col-md-1 col-sm-2 col-xs-4 {{$simmed->character_colour}}">
                <nobr><strong>
                <a style="background: white; color: blue; font-weight: bold; padding: 0.2rem; border: solid 0.1rem black; border-radius: 3px;" href="{{route('worktime.statistics', [ 'start' => date('Y-m-d'), 'stop' => date('Y-m-d',strtotime(date('Y-m-d').' + 1 month')), 'room' => $simmed->room_id ])}}">
                
                {{ $simmed->room_number }}
                </a>
                </strong></nobr>
            </div>
            <div class="col-md-1 col-sm-4 col-xs-8 {{$simmed->character_colour}}">
                {{$simmed->character_name}}
                </div>
            <div class="col-md-1 col-sm-2 col-xs-4 {{$simmed->character_colour}}" style="height: 100%; ">
                {{$simmed->student_group_code}}&nbsp;
            </div>


            <div class="col-md-4 col-sm-4 col-xs-12"> 
                {{ $simmed->leader }}
            </div>
            <div class="col-md-4 col-sm-5 col-xs-12">
                {{ $simmed->student_subject_name }}
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                @if ( Auth::user()->hasRole('Technik') && ($simmed->descript()!=null))
                    <span class="text-danger"><strong>{{$simmed->descript()->simmed_secret}}</strong></span><br>
                @endif
                {!!$simmed->simmed_alternative_title!!}
            </div>
        </div>
    <?php
    }
?>


@section('content')

<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/technician_characters.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/worktime_types.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/home.css')}}" />


<div class="panel panel-default">
    <!--div class="panel-heading"><img src="{{ asset('img/cmscsm/csm_ujk.svg') }}" width="50%"></div-->

    <div class="panel-body jq-schedule">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (Auth::user()->hasRole('Technik'))
            @foreach ($home_data as $row_one)
                <div class="row" style="border-top: 0.2rem solid black;">
                    <div class="col-sm-3 col-xs-4">
                    <a href="/scheduler/{{$row_one['date']}}">
                        <div class="callendar_card">
                            <div class="callendar_month">{{$row_one['wdname']}}</div>
                            {{substr($row_one['date'],8,2)}}
                            <div class="callendar_ico"><span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span></div>
                            <div class="callendar_weekday">{{$row_one['monthname']}}</div>
                        </div>

                    </a>
                    </div>
                    <div class="col-sm-3 col-xs-8 text-center">
                        @foreach ($row_one['work_times']['work_types'] as $row_time)
                            [{{$row_time}}]
                        @endforeach
                        <hr style="margin:0rem;">
                    <a class="time" href="{{route('worktime.day_data', [ $row_one['date'], Auth::user()->id ])}}">
                        @foreach ($row_one['work_times']['times'] as $row_time)
                            [{{$row_time['start']}}-{{$row_time['end']}}]
                        @endforeach
                        <hr style="margin:0rem;">
                        <span class="glyphicon glyphicon glyphicon-briefcase text-success" aria-hidden="true"></span>
                    </a>

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
