<?php
if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

        @extends('layouts.app')

@section('title', " Zarządzaj symulacjami: tematy")

@section('content')

<h1>Zarządzenie tematami symulacji</h1>

<table>
    <tr>
        <td>
        id
        </td>
        <td>
        nazwa
        </td>
        <td>
        status
        </td>
    </tr>
    @foreach ($subjects as $subject)
    <tr>
        <td>
        {{$subject->id}}
        </td>
        <td>
        {{$subject->student_subject_name}}
        </td>
        <td>
        {{$subject->student_subject_status}}
        </td>
    </tr>
    @endforeach
</table>

@endsection        