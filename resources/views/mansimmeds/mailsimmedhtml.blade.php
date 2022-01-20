<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Pokaż mail o zmianach")

@section('content')
<h1>treść maila o zmianach</h1>
@include('mansimmeds.mailsimmed')

@endsection