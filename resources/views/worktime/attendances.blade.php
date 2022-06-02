@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@section('title', "Listy obecności "." "." - pracownicy" )

@section('content')
<div class="container">
    <div class="row">
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
    </div>
</div>

<h1>listy obecności</h1>
<div class="row text-right">

</div>
<ol>
@foreach ($big_table as $row_one)
<div class="row">
    <div class="col-sm-2">
    {{$row_one['list']->dateHR}}
    </div>
    <div class="col-sm-3">
        @if ($row_one['list']->dateWA==null)
        <form action="{{ route('worktime.edit_attendance') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="dateHR" value="{{$row_one['list']->dateHR}}-01">
            <input type="hidden" name="action" value="add">
            <input class="btn btn-success btn-sm d-inline" type="submit" value="stwórz listę {{$row_one['list']->dateHR}}">
        </form>
        </div>
        <div class="col-6">
        </div>
        @else
        </div>
        <div class="col-sm-2">
        <form action="{{ route('worktime.print_attendance') }}" method="post">
            {{ csrf_field() }}

            <select  class="selectpicker" name="users_table[]" multiple="multiple">
                <option value="" selected disabled>Wybierz pracowników:</option>
                @foreach ($row_one['users'] as $technician_one)
                <option value="{{$technician_one->id}}" selected="selected">{{$technician_one->lastname}} {{$technician_one->firstname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-1">
            <select  name="users_count">
                @for ($i=1; $i <= 6; $i++)
                <option value="{{$i}}"@if ($i==4) selected="selected" @endif>{{$i}}</option>
                @endfor
            </select>
        </div>
        <div class="col-sm-2">
            <input type="hidden" name="dateHR" value="{{$row_one['list']->dateHR}}">
            <input type="hidden" name="action" value="print2">
            <input class="btn btn-primary btn-sm d-inline" type="submit" value="wydrukuj listę {{$row_one['list']->dateHR}}">
        </form>
        </div>
        <div class="col-sm-2">
        <form action="{{ route('worktime.edit_attendance') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="dateHR" value="{{$row_one['list']->dateWA}}">
            <input type="hidden" name="action" value="remove">
            <input class="btn btn-danger btn-sm d-inline" type="submit" value="usuń listę {{$row_one['list']->dateHR}}">
        </form>
        </div>
        @endif
    
</div>
@endforeach
</ol>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endsection


