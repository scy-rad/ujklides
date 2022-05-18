@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Listy obecności "." "." - technicy" )

@section('content')
<h1>listy obecności</h1>
<div class="row text-right">

</div>
<ol>
@foreach ($attendances_tab as $row_one)
<div class="row">
    <div class="col-sm-3">
    {{$row_one->dateHR}}
    </div>
    <div class="col-sm-3">
        @if ($row_one->dateWA==null)
        <form action="{{ route('worktime.edit_attendance') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="dateHR" value="{{$row_one->dateHR}}-01">
            <input type="hidden" name="action" value="add">
            <input class="btn btn-success btn-sm d-inline" type="submit" value="stwórz listę {{$row_one->dateHR}}">
        </form>
        </div>
        <div class="col-6">
        </div>
        @else
        </div>
        <div class="col-sm-3">
        <form action="{{ route('worktime.edit_attendance') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="dateHR" value="{{$row_one->dateHR}}-01">
            <input type="hidden" name="action" value="print">
            <input class="btn btn-primary btn-sm d-inline" type="submit" value="wydrukuj listę {{$row_one->dateHR}}">
        </form>
        </div>
        <div class="col-sm-3">
        <form action="{{ route('worktime.edit_attendance') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="dateHR" value="{{$row_one->dateWA}}">
            <input type="hidden" name="action" value="remove">
            <input class="btn btn-danger btn-sm d-inline" type="submit" value="usuń listę {{$row_one->dateHR}}">
        </form>
        </div>
        @endif
    
</div>
@endforeach
</ol>

@endsection


