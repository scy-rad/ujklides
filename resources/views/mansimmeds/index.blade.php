<?php
if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

        @extends('layouts.app')

@section('title', " Zarządzaj symulacjami")

@section('content')

<h1>Zarządzenie symulacjami</h1>

<div class="row">
        <div class="col-sm-2">
        <form action="{{ route('mansimmeds.import_file') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="step" value="add_data">
                <input class="btn btn-primary btn-lg" type="submit" value="import z pliku">
        </form>
        </div>

        <div class="col-sm-2">
        <form action="{{ route('mansimmeds.impanalyze') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="atep_code" value="90">
                <input class="btn btn-primary btn-lg" type="submit" value="analizuj dane z UXP">
        </form>
        </div>

        <div class="col-sm-2">
        <form action="{{ route('mansimmeds.subjects') }}" method="get">
                <input type="hidden" name="action" value="addinv">
                <input class="btn btn-primary btn-lg" type="submit" value="tematy">
        </form>
        </div>

        <div class="col-sm-2">
                <form action="{{ route('mansimmeds.groups') }}" method="get">
                        <input type="hidden" name="action" value="addinv">
                        <input class="btn btn-primary btn-lg" type="submit" value="grupy">
                </form>
        </div>

        <div class="col-sm-2">
                <form action="{{ route('mansimmeds.csv') }}" method="get">
                        <input class="btn btn-primary btn-lg" type="submit" value="pobierz csv">
                </form>
        </div>

        <div class="col-sm-2">
                <form action="{{ route('mansimmeds.sendMail') }}" method="get">
                        <input type="hidden" name="mailtype" value="monthinfo">
                        <input class="btn btn-primary btn-lg" type="submit" value="wyślij mail">
                </form>
        </div>

</div>

@if (isset($message_show))
        <div class="alert alert-success">
        {!! $message_body !!}
        </div>
@endif



@endsection        



