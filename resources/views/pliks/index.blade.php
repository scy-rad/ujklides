@extends('layouts.app')


@section('content')

@if (App\PlikType::where('plik_type_menu_code',$type_code)->count()>0)
    <div class="row">
        <div class="col-sm-10">
            <h1> {{App\PlikType::where('plik_type_menu_code',$type_code)->first()->plik_type_name}}</h1>
        </div>
        <div class="col-sm-2">
            <a href="{{ url()->previous() }}"><button> powrót </button></a>
        </div>
    </div>

    <ul>
    @if (App\PlikType::where('plik_type_menu_code',$type_code)->first()->pliks->count()>0)
        @foreach (App\PlikType::where('plik_type_menu_code',$type_code)->first()->pliks as $plik_row)
            <li><a href="{{route('pliks.show', $plik_row)}}">{{$plik_row->plik_description}}</a></li>
            
        @endforeach
    @endif
    </ul>
@endif



@endsection

