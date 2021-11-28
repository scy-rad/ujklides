@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><img src="{{ asset('img/cmscsm/csm_ujk.svg') }}" width="50%"></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


<?php 
$curr_date='';
$dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );
?>
<ol>Plan zajęć (tydzień) użytkownika: {{Auth::user()->firstname}} {{Auth::user()->lastname}}:
@foreach ($main_simulations as $simmed)
        @if ($curr_date != $simmed->simmed_date)
        </ol>
            <hr>
            <?php $curr_date = $simmed->simmed_date; ?>
        <ol>{{$simmed->simmed_date}} ({{$dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ] }})
        @endif
        <li><a href="{{route('simmeds.show', $simmed)}}">
            {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}: 
            </a>
            <strong >{{ $simmed->room()->room_number }}</strong>: 
            <span class="bg-success">{{ $simmed->name_of_leader() }} </span>
            {{ $simmed->name_of_student_subject() }}
            <span class="bg-primary">[{{$simmed->technician_character()->character_name}}]</span>
            
        </li>
@endforeach
</ol>



<?php 
$curr_date='';
?>
<ol>Plan zajęć na najbliższe 2 dni:
@foreach ($next_simulations as $simmed)
        @if ($curr_date != $simmed->simmed_date)
        </ol>
            <hr>
            <?php $curr_date = $simmed->simmed_date; ?>
        <ol>{{$simmed->simmed_date}} ({{$dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ] }})
        @endif
        <li><a href="{{route('simmeds.show', $simmed)}}">
            {{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}: 
            </a>
            <strong >{{ $simmed->room()->room_number }}</strong>: 
            <span class="bg-danger strong">{{ $simmed->login_of_technician() }} </span>
            <span class="bg-success">{{ $simmed->name_of_leader() }} </span>
            {{ $simmed->name_of_student_subject() }}
            <span class="bg-primary">[{{$simmed->technician_character()->character_name}}]</span>
            
        </li>
@endforeach
</ol>







                </div>
            </div>
        </div>
    </div>
</div>
@endsection
