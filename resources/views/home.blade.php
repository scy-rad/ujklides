@extends('layouts.app')
<?php 
$curr_date='';
$dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );

function rowek($simmed)
    {
    ?>
        <li>
        <div class="row">
            <div class="col-sm-2">
                <a href="{{route('simmeds.show', [$simmed->id, 0])}}"> 
                    {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }} 
                    <span class="glyphicon glyphicon-list-alt text-success" aria-hidden="true"></span>
                </a>
                <nobr><strong >{{ $simmed->room()->room_number }}</strong></nobr>
            </div>
            <div class="col-sm-2"> 
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
            <div class="col-md-1 bg-primary">
                {{$simmed->technician_character()->character_name}}
                </div>
        </div>
        </li>
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

        <h3>Czas pracy dziś ({{date('Y-m-d')}}): 
            <a href="{{route('worktime.day_data', [ date('Y-m-d'), Auth::user()->id ])}}">
                @foreach ($work_times['times'] as $row_one)
                    {{$row_one['start']}} - {{$row_one['end']}},
                @endforeach
                <span class="glyphicon glyphicon glyphicon-briefcase text-success" aria-hidden="true"></span>            
            </a>

        </h3>

        <h3>Plan zajęć (tygodniowy) użytkownika: {{Auth::user()->firstname}} {{Auth::user()->lastname}}:</h3>
        <ol>
        @foreach ($main_simulations as $simmed)
                @if ($curr_date != $simmed->simmed_date)
                </ol>
                    <hr>
                    <?php $curr_date = $simmed->simmed_date; ?>
                <ol><h3>
                <a href="/scheduler/{{$simmed->simmed_date}}">
                    {{$simmed->simmed_date}} 
                    <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
                    ({{$dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ] }})
                    </a></h3>

                 
                @endif
                <?php rowek($simmed); ?>
        @endforeach
        </ol>
        @endif
    </div>
</div>

@endsection
