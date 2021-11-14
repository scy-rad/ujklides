<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj symulacjami: grupy studenckie")

@section('content')

<h1>Zarządzenie grupami studenckimi</h1>

<table>
    <tr>
        <td>
        id
        </td>
        <td>
        nazwa
        </td>
        <td>
        kierunek
        </td>
        <td>
        status
        </td>
    </tr>
    @foreach ($groups as $group)
    <tr>
        <td>
        {{$group->id}}
        </td>
        <td>
        {{$group->student_group_name}}
        </td>
        <td>
        {{$group->name_of_direction()}}
        </td>
        <td>
        {{$group->student_group_status}}
        </td>
    </tr>
    @endforeach
</table>

@endsection