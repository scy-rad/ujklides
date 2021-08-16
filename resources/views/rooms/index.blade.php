@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', 'pokoje')

@section('content')

@foreach($rooms as $room)
        <a href="{{route('rooms.show', $room)}}">
            <div class="tile">
                <img src="/storage/img/rooms/{{ $room->room_photo }}" class="tile">
                
                <div class="tiletitle">
                    {{ $room->room_number }}.
                    {{ $room->room_name  }}
                </div>
            </div>
        </a>
    @endforeach

@endsection