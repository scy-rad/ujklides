@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Czas pracy ".$user->lastname." "." - technik " )

@section('content')

<h4>{{$user->full_name()}}</h4>
<?php dd(date('Y-m-d',strtotime('-1 day', $date))); ?>
<a href="{{route('worktime.day_data', [ date('Y-m-d',strtotime('-1 day', $date)), $user->id ])}}"> prev </a>

<h2>zaplanowane zajęcia w CSM:</h2>
@foreach ($simmeds as $row_one)
    {{$row_one->start}} - {{$row_one->end}}
    <strong>{{$row_one->room_number}}</strong>
    {{$row_one->text}}
    [{{$row_one->student_group_code}}: {{$row_one->student_subject_name}}]
    <strong>{{$row_one->character_short}}</strong>
    <br>
@endforeach

<h2>rozpisany czas pracy:</h2>
<table width="100%">
@foreach ($work_times as $row_one)
    <tr>
        <td>{{$row_one->id}}</td>
        <td>{{$row_one->time_begin}} - {{$row_one->time_end}}</td>
        <td>{{$row_one->short_name}}</td>
        <td>{{$row_one->description}}</td>
</tr>
@endforeach
</table>


@endsection


