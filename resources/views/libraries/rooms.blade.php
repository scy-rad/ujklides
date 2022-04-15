<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj symulacjami: tematy")

@section('content')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>


            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {{ $message }}

                </div>

            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Uuuups!</strong> Przecież to nie powinno się wydarzyć!<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

<h1>Zarządzenie salami</h1>

<button type="button" class="btn btn-primary">Dodaj...</button>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">numer</th>
          <th scope="col" data-sortable="true">nazwa</th>
          <th scope="col">opis</th>
          <th scope="col">kod UXP</th>
          <th scope="col">prop. char.</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($rooms as $room)
    <tr>
        <td>
        {{$room->id}}
        </td>
        <td>
        <span>{{$room->room_number}}</span>
        </td>
        <td>
        <span>{{$room->room_name}}</span>
        </td>
        <td>
        <span>{{$room->room_description}}</span>
        </td>
        <td>
        <span>{{$room->room_xp_code}}</span>
        </td>
        <td>
        <span>{{$room->character_short}}</span>
        </td>
        <td>
        Edycja
        </td>
    </tr>
    @endforeach
    </tbody>
</table>



@endsection