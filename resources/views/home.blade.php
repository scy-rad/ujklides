@extends('layouts.app')

@section('content')
<div class="container">
    <div>
        <div>
            <div class="panel panel-default">
                <!--div class="panel-heading"><img src="{{ asset('img/cmscsm/csm_ujk.svg') }}" width="50%"></div-->

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


<?php 
$curr_date='';
$dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );

function rowek($simmed)
    {
    ?>
        <li>
        <div class="row">
            <div class="col-md-2">
            <a href="{{route('simmeds.show', $simmed)}}">
                {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }} 
                </a>
            </div>
            <div class="col-md-1">
                <strong >{{ $simmed->room()->room_number }}</strong>
            </div>
            <div class="col-md-2"> 
                {{ $simmed->name_of_leader() }}
            </div>
            <div class="col-md-2">
                {{ $simmed->name_of_student_subject() }}
            </div>
            <div class="col-md-1 text-light bg-dark">
                {{$simmed->code_of_student_group()}}
            </div>
            <div class="col-md-1 bg-primary">
                {{$simmed->technician_character()->character_name}}
                </div>
            <div class="col-md-3">    
                {!!$simmed->simmed_alternative_title!!}
            </div>
        </div>
        </li>
    <?php
    }

function rowek_tech($simmed)
    {
    ?>
        
        <div class="row" style="border-bottom: 1px dotted green">
            <div class="col-md-2">
            <li>
                <div class="col-md-12 col-sm-6">
                    <a href="{{route('simmeds.show', $simmed)}}">
                    {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}
                    </a>
                </div>
                <div class="col-md-12 col-sm-6 strong">
                    {{ $simmed->room()->room_number }}
                </div>
            </div>
            <div class="col-md-2">
                <div class="col-md-12 col-sm-6 bg-danger strong">    
                    {{ $simmed->login_of_technician() }}
                </div>
                <div class="col-md-12 col-sm-6 bg-primary">
                    {{$simmed->technician_character()->character_name}}
                </div>
            </div>
            @if (($simmed->simmed_leader_id*1==0) && ($simmed->student_subject_id*1==0))
            <div class="col-md-4 bg-warning">    
                {!!$simmed->simmed_alternative_title!!}
            </div>
            @else
            <div class="col-md-4">
                <div class="col-md-12 col-sm-6 strong"> 
                    <strong>{{ $simmed->name_of_leader() }}</strong>
                </div>
                <div class="col-md-12 col-sm-6">
                    {{ $simmed->name_of_student_subject() }}
                </div>
            </div>
            @endif
            <div class="col-md-2">
                {{$simmed->code_of_student_group()}}
            </div>
            </li>
        </div>
        
    <?php
    }


?>
<ol><h3>Plan zajęć (tygodniowy) użytkownika: {{Auth::user()->firstname}} {{Auth::user()->lastname}}:</h3>
@foreach ($main_simulations as $simmed)
        @if ($curr_date != $simmed->simmed_date)
        </ol>
            <hr>
            <?php $curr_date = $simmed->simmed_date; ?>
        <ol>{{$simmed->simmed_date}} ({{$dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ] }})
        @endif
        <?php rowek($simmed); ?>
@endforeach
</ol>



<?php 
$curr_date='';
?>
<ol><h3>Plan zajęć na najbliższe 2 dni:</h3>

@foreach ($next_simulations as $simmed)
        @if ($curr_date != $simmed->simmed_date)
        </ol>
            <hr>
            <?php $curr_date = $simmed->simmed_date; ?>
        <ol>{{$simmed->simmed_date}} ({{$dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ] }})
        @endif
        <?php /*
        <li><a href="{{route('simmeds.show', $simmed)}}">
            {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}: 
            </a>
            <strong >{{ $simmed->room()->room_number }}</strong>: 
            <span class="bg-danger strong">{{ $simmed->login_of_technician() }} </span>
            <span class="bg-success">{{ $simmed->name_of_leader() }} </span>
            {{ $simmed->name_of_student_subject() }}
            <span class="text-light bg-dark">[{{$simmed->code_of_student_group()}}]</span>
            <span class="bg-primary">[{{$simmed->technician_character()->character_name}}]</span>
        </li>
        */ ?>
        <?php rowek_tech($simmed); ?>
@endforeach
</ol>







                </div>
            </div>
        </div>
    </div>
</div>
@endsection
