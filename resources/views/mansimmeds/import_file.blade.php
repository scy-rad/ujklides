<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds import','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
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


{{-- przypadek dodawania danych z pliku wordowego Uczelnia XP lub arkusza Excel - pokaż pole do wklejenia danych --}}
<form action="{{ route('mansimmeds.import_check') }}" method="post">
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

@if ($max_import_number > 0)
<form action="{{ route('mansimmeds.import_reread') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="step" value="check_data">
    <input class="btn btn-primary btn-lg" type="submit" value="pokaż dane w poczekalni...">
</form>
@endif




@endsection