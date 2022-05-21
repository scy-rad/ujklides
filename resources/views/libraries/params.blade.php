<?php
    if ( (!Auth::user()->hasRole('Operator Symulacji'))
    && (!Auth::user()->hasRole('Operator Kadr'))
    && (!Auth::user()->hasRole('Administrator'))
)
    return view('error',['head'=>'błąd wywołania widoku Libraries params','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem lub Administratorem']);
?>

@extends('layouts.app')

@section('title', " Ustalanie parametrów")

@section('content')


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

<h1>Parametry systemu</h1>



<form action="{{ route('libraries.params_save') }}" method="post">
    
    <div class="form-group">
        <label for"unit_name">nazwa jednostki:</label>
        <input type="text" class="form-control" name="unit_name" value="{{$params->unit_name}}">
        <label for"unit_name">nazwa jednostki (wersaliki):</label>
        <input type="text" class="form-control" name="unit_name_wersal" value="{{$params->unit_name_wersal}}">
    
        <label for"leader_for_simmed">leader_for_simmed:</label>
          <select class="form-control" name="leader_for_simmed">
              @foreach ($leaders_list as $leader_one)
              <option value="{{$leader_one->id}}"<?php if ($leader_one->id == $params->leader_for_simmed) echo 'selected="selected"'; ?>>{{$leader_one->lastname}} {{$leader_one->firstname}}</option>
              @endforeach
          </select>

        <label for"technician_for_simmed">technician_for_simmed:</label>
        <select class="form-control" name="technician_for_simmed">
              @foreach ($technicians_list as $technician_one)
              <option value="{{$technician_one->id}}"<?php if ($technician_one->id == $params->technician_for_simmed) echo 'selected="selected"'; ?>>{{$technician_one->lastname}} {{$technician_one->firstname}}</option>
              @endforeach
          </select>

        <label for"statistics_start">Początek statystyk (bieżącego semestru):</label>
        <input type="date" class="form-control" name="statistics_start" value="{{$params->statistics_start}}">

        <label for"statistics_stop">koniec statystyk, miesiąc wysyłki e-maili:</label>
        <input type="date" class="form-control" name="statistics_stop" value="{{$params->statistics_stop}}">

        <label for"simmed_days_edit_back">ile dni "wstecz" technik może edytować przypisanie technika do symulacji:</label>
        <input type="text" class="form-control" name="simmed_days_edit_back" value="{{$params->simmed_days_edit_back}}">

        <label for"worktime_days_edit_back">ile dni "wstecz" technik może edytować swój czas pracy:</label>
        <input type="text" class="form-control" name="worktime_days_edit_back" value="{{$params->worktime_days_edit_back}}">

    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
    <input type="hidden" name="id" value="{{$params->id}}">
    {{ csrf_field() }}
    </form>

@endsection