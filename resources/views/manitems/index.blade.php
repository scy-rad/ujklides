@extends('layouts.app')

<?php include(app_path().'/include/view_common.php'); ?>

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', " Zarządzaj zasobami")

@section('content')

<h1>Zarządzenie zasobami</h1>
<div class="row">
    <div class="col-sm-3">
        <div class="bg-primary">inwentaryzacja</div>
        <ul>
            <li>stwórz inwentaryzację</li>
        </ul>
    </div>

    <div class="col-sm-3">
        <div class="bg-primary">coś tam</div>
        <ul>
            <li>[{{$tab3}}]</li>
        </ul>
    </div>
    
    <div class="col-sm-3">
        <div class="bg-primary">sale</div>
        <ul>
        @foreach ($rooms as $room)
            <li>
                    {{ $room->room_number }}.
                    {{ $room->room_name  }}
                <form action="" method="get">
                <input type="hidden" name="action" value="addinv">
                <input type="hidden" name="forroom" value="{{$room->id}}">
                <input type="submit" name="sub" value="mit">
                </form>
            </li>
        @endforeach
        </ul>
        
    </div>
</div>

@endsection