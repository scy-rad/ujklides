<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds import','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

<?php
function rowEK_head($head)
{
    ?>
    <thead>
    <tr>
    @if ($head!='')
        <th>{{$head}}</th>
    @endif
        <th>data</th>
        <th>od</th>
        <th>do</th>
        <th class="bg-info">ID:</th>
        <th>sala</th>
        <th class="bg-info">ID:</th>
        <th>instruktor</th>
        <th class="bg-info">ID:</th>
        <th>temat</th>
        <th class="bg-info">ID:</th>
        <th>grupa</th>
        <th class="bg-info">ID</th>
        <th>sub</th>
    </tr>
    </thead>
    <?php
}
?>

<?php
function rowEK($new_simmed)
{
    ?>
    <tr id="{{$new_simmed['id']}}">
    @if ($new_simmed['head']!='')
        <td>{{$new_simmed['head']}}</td>
    @endif
        <td>{{$new_simmed['simmed_date']}}</td>
        <td>{{$new_simmed['simmed_time_begin']}}</td>
        <td>{{$new_simmed['simmed_time_end']}}</td>
        <td class="bg-info">{{$new_simmed['room_id']}}</td>
        <td>{{$new_simmed['room_name']}}</td>
        <td class="bg-info">{{$new_simmed['simmed_leader_id']}}</td>
        <td>{{$new_simmed['simmed_leader']}}</td>
        <td class="bg-info">{{$new_simmed['student_subject_id']}}</td>
        <td>{{$new_simmed['student_subject']}}</td>
        <td class="bg-info">{{$new_simmed['student_group_id']}}</td>
        <td>{{$new_simmed['student_group']}}</td>
        <td class="bg-info">{{$new_simmed['student_subgroup_id']}}</td>
        <td>{{$new_simmed['student_subgroup']}}</td>
    </tr>
    <?php
}
?>

@extends('layouts.app')

@section('title', " Import symulacji: sprawdzanie braków")

@section('content')
    <h1>Import zajęć z systemu uczelnia XP - sprawdzanie braków</h1>

@if (isset($err_info))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h1 class="alert-heading">błąd importu</h1>
        <hr>
        {{$err_info}}
    </div>
@endif

@if (isset($info['from']))
    od: {{$info['from']}}<br>
    do: {{$info['to']}}<br>
    <div class="bg-danger">
        @if ($info['wrong_count']>0 ) błędne wiersze: <strong> {{$info['wrong_count']}} </strong> <br> @endif
        @if ($miss['missing_leaders']>0 ) wiersze z brakującymi instruktorami: <strong>{{$miss['missing_leaders']}} </strong> <br> @endif
        @if ($info['missing_subjects']>0 ) wiersze z brakującymi przedmiotami: <strong> {{$info['missing_subjects']}} </strong> <br> @endif
        @if ($info['missing_groups']>0 ) wiersze z brakującymi grupami: <strong> {{$info['missing_groups']}} </strong> <br> @endif
        @if ($info['missing_subgroups']>0 ) wiersze z brakującymi podgrupami: <strong> {{$info['missing_subgroups']}} </strong> <br> @endif
    </div>
    <hr>
    @if ($info['wrong_count']>0)
        <ul><strong>Wiersze pominięte w imporcie:</strong>
            @foreach ($wrong as $new_wrong)
                <li>{{$new_wrong}}</li>
            @endforeach
        </ul>
    @endif
    @if ( $info['missing_date']==1 )
            <h2>nie wykryto zakresu dat!!</h2>
            <p>coś niewłaściwego znaleziono w importowanych danych</p>
            <h2>{{$info['from']}}</h2>
            <h2>{{$info['to']}}</h2>
    @endif
@endif

    @if ($miss['no_rooms']->count()>0)
        <ul><strong>Sale nie znalezione w systemie:</strong>
            @foreach ($miss['no_rooms'] as $new_row)
                <li>{{$new_row->room_xp_txt}}</li>
            @endforeach
        </ul>
    @endif

    @if ( ($miss['missing_leaders']>0) || ($miss['missing_subjects']>0) || ($miss['missing_groups']>0) || ($miss['missing_subgroups']>0))

        <form action="{{ route('mansimmeds.import_complement') }}" method="post">
            {{ csrf_field() }}

            @if ($miss['missing_leaders']>0)
                <ul><strong>Instruktorzy nie znalezieni w systemie:</strong>
                @foreach ($miss['no_leader_list'] as $new_row)
                    <li>{{$new_row['name']}}  <input type="hidden" name="missing_leaders-{{$new_row['row']}}" id="missing_leaders-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_leaders-{{$new_row['row']}}" onclick="onclickHandler('missing_leaders-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                @endforeach
                </ul>
            @endif

            @if ($miss['missing_subjects']>0)
                <ul><strong>Tematy zajęć nie znalezione w systemie:</strong>
                @foreach ($miss['no_subject_list'] as $new_row)
                    <li>{{$new_row['name']}} <input type="hidden" name="missing_subjects-{{$new_row['row']}}" id="missing_subjects-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_subjects-{{$new_row['row']}}" onclick="onclickHandler('missing_subjects-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                @endforeach
                </ul>
            @endif

            @if ($miss['missing_groups']>0)
                <ul><strong>Grupy nie znalezione w systemie:</strong>
                @foreach ($miss['no_group_list'] as $new_row)
                    <li>{{$new_row['name']}} <input type="hidden" name="missing_groups-{{$new_row['row']}}" id="missing_groups-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_groups-{{$new_row['row']}}" onclick="onclickHandler('missing_groups-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                @endforeach
                </ul>
            @endif

            @if (($miss['missing_subgroups']>0) && (isset($miss['no_subgroup_list'])))
                <ul><strong>Podgrupy nie znalezione w systemie:</strong>
                @foreach ($miss['no_subgroup_list'] as $key => $value)
                    <li>{{ App\StudentGroup::where('id',$key)->first()->student_group_name}}</li>
                        <ul>
                        @foreach ($value as $new_row)
                            <li>{{$new_row['name']}} <input type="hidden" name="missing_subgroups-{{$new_row['row']}}" id="missing_subgroups-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_subgroups-{{$new_row['row']}}" onclick="onclickHandler('missing_subgroups-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                        @endforeach
                        </ul>
                @endforeach
                </ul>
            @endif

        <input type="hidden" name="step" value="complement_data">
        <input class="btn btn-primary btn-lg" type="submit" value="uzupełnij braki">
        </form>
        
        <script>
        function onclickHandler(id_name)
        {
            let tekst='alla';
            if (document.getElementById(id_name).value == 'dodaj')
                { tekst='pomiń'; }
            else
                { tekst='dodaj'; }
            document.getElementById(id_name).value = tekst;
            //document.getElementById(id_name).setAttribute('value','pomiń');
            document.getElementById('sp_'+id_name).innerHTML = tekst;
            //alert('Akapit został kliknięty! div: '+id_name);
        }
        </script>

    @endif

    <form action="{{ route('mansimmeds.impanalyze') }}" method="post">
                {{ csrf_field() }}
                <input class="btn btn-primary btn-lg" type="submit" value="analizuj dane z UXP">
        </form>



    {{-- a jeżeli wszystkie pola są określone, to: --}}

    <table width="100%">
        <tr>
            <th>data</th>
            <th>od</th>
            <th>do</th>
            <th class="bg-info">ID:</th>
            <th>sala</th>
            <th class="bg-info">ID:</th>
            <th>instruktor</th>
            <th class="bg-info">ID:</th>
            <th>temat</th>
            <th class="bg-info">ID:</th>
            <th>grupa</th>
            <th class="bg-info">ID</th>
            <th>sub</th>
            <th>info</th>
        </tr>

        @if ($simmeds != NULL)
            @foreach ($simmeds as $new_simmed)
            <tr><td>{{$new_simmed->simmed_date}}</td>
                <td>{{$new_simmed->simmed_time_begin}}</td>
                <td>{{$new_simmed->simmed_time_end}}</td>
                <td class="bg-info">{{$new_simmed->room_id}}</td>
                <td>{{$new_simmed->room_number}}</td>
                <td class="bg-info">{{$new_simmed->simmed_leader_id}}</td>
                <td>{{$new_simmed->simmed_leader_txt}}</td>
                <td class="bg-info">{{$new_simmed->student_subject_id}}</td>
                <td>{{$new_simmed->student_subject_txt}}</td>
                <td class="bg-info">{{$new_simmed->student_group_id}}</td>
                <td>{{$new_simmed->student_group_txt}}</td>
                <td class="bg-info">{{$new_simmed->student_subgroup_id}}</td>
                <td>{{$new_simmed->student_subgroup_txt}}</td>
                <td>{{$new_simmed->simmed_alternative_title}}</td>
            </tr>
            @endforeach
        @endif
    </table>
    {{--
    <form action="{{ route('mansimmeds.import_append') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="step" value="check_exist">
        <input class="btn btn-primary btn-lg" type="submit" value="dodaj wpisy do bazy tymczasowej">
    </form>
    --}}

@endsection