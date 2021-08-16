@extends('layouts.app')


@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1> {{$plik->plik_title}} </h1>
        
    </div>
    <div class="col-sm-2">
        <a href="{{ url()->previous() }}"><button> powrót </button></a>
    </div>
</div>

<div class="row">
<div class="col-sm-2">
<h3> {{$plik->plik_version}} </h3>
<p> {!!$plik->plik_description!!} </p>
        
</div>
<div class="col-sm-10">
<iframe src="/storage/files{{$plik->plik_directory}}/{{$plik->plik_name}}" style="width: 100%; box-sizing: border-box;  height: 85vh;border: 1px solid #000;">Wystąpił błąd</iframe>
</div>
</div>



@endsection

