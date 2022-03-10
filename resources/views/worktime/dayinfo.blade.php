@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', "Czas pracy "." "." - technik " )

@section('content')

@foreach ($work_characters_month as $row_one)
{{$row_one->start}} - {{$row_one->end}}
<strong>{{$row_one->room_number}}</strong>
{{$row_one->text}}
[{{$row_one->student_group_code}}: {{$row_one->student_subject_name}}]
<strong>{{$row_one->character_short}}</strong>
<br>

@endforeach


@endsection


