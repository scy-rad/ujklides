@extends('layouts.app')


@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1> {{$galeria->gallery_name}}</h1>
        <h2> {{$galeria->gallery_description}}</h2>
    </div>
    <div class="col-sm-2">
        <a href="{{ url()->previous() }}"><button> powrót </button></a>
    </div>
</div>


@foreach ($galeria->photos as $photox)
        <div class="col-xs-6 col-md-3">
        <a href="{{$photox->gallery_photo_directory}}/{{$photox->gallery_photo_name}}" class="thumbnail"><img src="{{$photox->gallery_photo_directory}}/{{$photox->gallery_photo_name}}" alt="{{$photox->gallery_photo_description}}">{{$photox->gallery_photo_title}}</a>
        </div>
    @endforeach

@endsection

