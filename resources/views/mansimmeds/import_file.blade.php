<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds import','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Importowanie pliku z symulacjami")

@section('content')
<h1>Import zajęć ... </h1>

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

<form action="{{ route('mansimmeds.import_check') }}" method="post">
    {{ csrf_field() }}
    <textarea class="form-control" name="import_data" rows="3"></textarea>
    <select name="import_type">
        <option value="xp">Uczelnia XP</option>
        <option value="xls">Excel</option>
    </select>
    <input class="btn btn-primary btn-lg" type="submit" value="wczytaj wiersze ...">
</form>

@if ($max_import_number > 0)

<div class="clearfix"></div>

<form action="{{ route('mansimmeds.import_reread') }}" method="post">
    {{ csrf_field() }}
    <input class="btn btn-primary btn-lg" type="submit" value="pokaż dane w poczekalni...">
</form>

<form action="{{ route('mansimmeds.clear_import_tmp') }}" method="post">
    {{ csrf_field() }}
    <input class="btn btn-primary btn-lg" type="submit" value="usuń dane z poczekalni...">
</form>

@endif

@endsection