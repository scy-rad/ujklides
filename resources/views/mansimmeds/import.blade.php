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
        <!--th>info</th>
        <th>sim id</th>
        <th>status</th>
        <th>merge</th-->
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

@section('title', " Zarządzaj symulacjami: tematy")

@section('content')
<h1>Import zajęć z systemu uczelnia XP</h1>
<div class="text-right">{{$step}}</div>


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

{{-- Jeżeli w tymczasowej bazie sal są jakieś aktywne wpisy, to pokaż które sale są już wczytane --}}
@if (App\SimmedTempRoom::where('import_status',0)->get()->count()>0)
<h2>sale wczytane - wciąż niezaimportowane</h2>
<ol>
@foreach (App\SimmedTempRoom::where('import_status',0)->get() as $exist_room)
    <li>{{$exist_room->room->room_number}} ({{$exist_room->room->room_name}})</li>    
@endforeach
</ol>
@endif

@switch ($step)




@case ('add_data')
{{-- przypadek dodawania danych z pliku wordowego Uczelnia XP - pokaż pole do wklejenia danych --}}
<form action="{{ route('mansimmeds.import') }}" method="post">
    {{ csrf_field() }}
    <textarea class="form-control" name="import_data" rows="3"></textarea>
    <select name="import_type">
        <option value="xp">Uczelnia XP</option>
        <option value="xls">Excel</option>
    </select>
    <input type="hidden" name="step" value="check_data">
    <input type="hidden" name="import_data_id" value="0">
    <input class="btn btn-primary btn-lg" type="submit" value="sprawdź wiersze z UXP">
</form>

@if (DB::table('simmed_temp_posts')->max('id') > 0)
<form action="{{ route('mansimmeds.import') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="step" value="check_data">
    <input type="hidden" name="import_data_id" value="{{DB::table('simmed_temp_posts')->max('id')}}">
    <input class="btn btn-primary btn-lg" type="submit" value="ponownie wczytaj dane z {{App\SimmedTempPost::find(DB::table('simmed_temp_posts')->max('id'))->created_at}}">
</form>
@endif
@break







@case ('check_data')

{{-- przypadek sprawdzania danych wklejonych z pliku Uczelnia XP --}}

<ul>sale: @foreach ($info['room_id_tab'] as $room_one) <li> {{App\Room::find($room_one)->room_number}} : {{App\Room::find($room_one)->room_name}} </li> @endforeach
</ul>
od: {{$info['from']}}<br>
do: {{$info['to']}}<br>
<div class="bg-danger">
    @if ($info['wrong_count']>0 ) błędne wiersze: <strong> {{$info['wrong_count']}} </strong> <br> @endif
    @if ($info['missing_room']>0 ) sale nie znalezione: <strong> {{$info['missing_room_name']}} </strong> <br> @endif
    @if ($info['missing_leaders']>0 ) wiersze z brakującymi instruktorami: <strong>{{$info['missing_leaders']}} </strong> <br> @endif
    @if ($info['missing_subjects']>0 ) wiersze z brakującymi przedmiotami: <strong> {{$info['missing_subjects']}} </strong> <br> @endif
    @if ($info['missing_groups']>0 ) wiersze z brakującymi grupami: <strong> {{$info['missing_groups']}} </strong> <br> @endif
    @if ($info['missing_subgroups']>0 ) wiersze z brakującymi podgrupami: <strong> {{$info['missing_subgroups']}} </strong> <br> @endif
</div>





    <hr>
    {{-- informacja o wierszach pominiętych w analizie --}}
    @if ($info['wrong_count']>0)
        <ul><strong>Wiersze pominięte w imporcie:</strong>
        @foreach ($wrong as $new_wrong)
            <li>{{$new_wrong}}</li>
        @endforeach
        </ul>
    @endif


    {{-- Jeżeli nie znaleziono w importowanych danych zakresu dat, to wyświetl tą informację: --}}
    @if ( $info['missing_date']==1 )
            <h2>nie wykryto zakresu dat!!</h2>
            <p>coś nie właściwego znaleziono w importowanych danych</p>

            <h2>{{$info['from']}}</h2>
            <h2>{{$info['to']}}</h2>

    {{-- Jeżeli nie znaleziono w importowanych danych instruktora, tematu, grupy lub podgrupy, to wyświetl te informacje: --}}
    @else
    @if ( ($info['missing_leaders']>0) || ($info['missing_subjects']>0) || ($info['missing_groups']>0) || ($info['missing_subgroups']>0))

        <form action="{{ route('mansimmeds.import') }}" method="post">
            {{ csrf_field() }}
            <!--input type="hidden" name="import_data" value="{ {$import_data} }"-->
            <input type="hidden" name="import_data_id" value="{{$import_data_id}}">

        {{-- info o nie znalezionych instruktorach --}}

        @if ($info['missing_leaders']>0)
            <?php $cos="";$coma="";?>
            <ul><strong>Instruktorzy nie znalezieni w systemie:</strong>
            @foreach ($no_leader_list as $new_row)
                <li>{{$new_row['name']}}  <input type="hidden" name="missing_leaders-{{$new_row['row']}}" id="missing_leaders-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_leaders-{{$new_row['row']}}" onclick="onclickHandler('missing_leaders-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                <?php $cos.=$coma.$new_row['row'].':'.$new_row['name']; $coma=','; ?>
            @endforeach
            </ul>
            <input type="hidden" name="missing_leaders" value="{{$cos}}">
        @endif

        {{-- info o nie znalezionych tematach --}}
        @if ($info['missing_subjects']>0)
            <?php $cos="";$coma="";?>
            <ul><strong>Tematy zajęć nie znalezione w systemie:</strong>
            @foreach ($no_subject_list as $new_row)
                <li>{{$new_row['name']}} <input type="hidden" name="missing_subjects-{{$new_row['row']}}" id="missing_subjects-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_subjects-{{$new_row['row']}}" onclick="onclickHandler('missing_subjects-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                <?php $cos.=$coma.$new_row['row'].'|'.$new_row['name']; $coma=','; ?>
            @endforeach
            </ul>
            <input type="hidden" name="missing_subjects" value="{{$cos}}">
        @endif

        {{-- info o nie znalezionych grupach --}}
        @if ($info['missing_groups']>0)
            <?php $cos="";$coma="";?>
            <ul><strong>Grupy nie znalezione w systemie:</strong>
            @foreach ($no_group_list as $new_row)
                <li>{{$new_row['name']}} <input type="hidden" name="missing_groups-{{$new_row['row']}}" id="missing_groups-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_groups-{{$new_row['row']}}" onclick="onclickHandler('missing_groups-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                <?php $cos.=$coma.$new_row['row'].'|'.$new_row['name']; $coma=','; ?>
            @endforeach
            </ul>
            <input type="hidden" name="missing_groups" value="{{$cos}}">
        @endif

        {{-- info o nie znalezionych podgrupach --}}
        @if (($info['missing_subgroups']>0) && (isset($no_subgroup_list)))
            <?php $cos="";$coma="";?>
            <ul><strong>Podgrupy nie znalezione w systemie:</strong>
            @foreach ($no_subgroup_list as $key => $value)
                <li>{{ App\StudentGroup::where('id',$key)->first()->student_group_name}}</li>
                    <ul>
                    @foreach ($value as $new_row)
                        <li>{{$new_row['name']}} <input type="hidden" name="missing_subgroups-{{$new_row['row']}}" id="missing_subgroups-{{$new_row['row']}}" value="{{$new_row['action']}}"> (<span id="sp_missing_subgroups-{{$new_row['row']}}" onclick="onclickHandler('missing_subgroups-{{$new_row['row']}}')">{{$new_row['action']}}</span>)</li>
                        <?php $cos.=$coma.$new_row['row'].'|'.$new_row['group_id'].'|'.$new_row['name']; $coma=','; ?>
                    @endforeach
                    </ul>
            @endforeach
            </ul>
            <input type="hidden" name="missing_subgroups" value="{{$cos}}">
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




    {{-- a jeżeli wszystkie pola są określone, to: --}}
    @endif
    <form action="{{ route('mansimmeds.import') }}" method="post">
        {{ csrf_field() }}
        <!--input type="hidden" name="import_data" value="{ {$import_data} }"-->
        <input type="hidden" name="import_data_id" value="{{$import_data_id}}">
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
            <tr><td>{{$new_simmed['simmed_date']}}</td>
                <td>{{$new_simmed['simmed_time_begin']}}</td>
                <td>{{$new_simmed['simmed_time_end']}}</td>
                <td class="bg-info">{{$new_simmed['room_id']}}</td>
                <td><?php if ($new_simmed['room_id']!=0) echo App\room::find($new_simmed['room_id'])->room_number; ?> </td>
                <td class="bg-info">{{$new_simmed['simmed_leader_id']}}</td>
                <td>{{$new_simmed['simmed_leader']}}</td>
                <td class="bg-info">{{$new_simmed['student_subject_id']}}</td>
                <td>{{$new_simmed['student_subject']}}</td>
                <td class="bg-info">{{$new_simmed['student_group_id']}}</td>
                <td>{{$new_simmed['student_group']}}</td>
                <td class="bg-info">{{$new_simmed['student_subgroup_id']}}</td>
                <td>{{$new_simmed['student_subgroup']}}</td>
                <td>{{$new_simmed['simmed_alternative_title']}}</td>
            </tr>
        @endforeach
        @endif
    </table>

        <input type="hidden" name="step" value="check_exist">
        <input class="btn btn-primary btn-lg" type="submit" value="dodaj wpisy do bazy tymczasowej">
        </form>

    @endif


@break




@case ('import_tmp')

<h2>wpisy dodane do bazy tymczasowej:</h2>
<table class="table table-bordered">
<tbody class="row_drag">
    <?php rowEK_head('stan'); ?>
@foreach ($noexist_list as $new_row)
    <?php
    rowEK($new_row);
    ?>
@endforeach
@foreach ($old_list as $old_row)
    <?php
    rowEK($old_row); ?>
@endforeach
</tbody>
</table>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
    $( ".row_drag" ).sortable({
        delay: 100,
        stop: function() {
            var selectedRow = new Array();
            $('.row_drag>tr').each(function() {
                selectedRow.push($(this).attr("id"));
            });
           alert(selectedRow);
        }
    });
</script>

<form action="{{ route('mansimmeds.index') }}" method="get">
    {{ csrf_field() }}

    <input type="hidden" name="step" value="check_data">
    <input class="btn btn-primary btn-lg" type="submit" value="powrót...">
</form>
@break



@endswitch



@endsection