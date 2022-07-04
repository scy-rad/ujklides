@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

@section('title', " zasoby - ".$ItemGroup->item_group_name )

@section('content')


{!!$ItemGroup->type_no_get->typepatch()!!}


<p>producent: <strong>{{$ItemGroup->item_group_producent}}</strong>, model: <strong>{{$ItemGroup->item_group_model}}</strong></p>

<h2>nazwa: {{$ItemGroup->item_group_name}}</h2>


<div class="row">
    <div class="col-sm-4">
        <ol>egzemplarze
        <?php
        $Items=App\Item::where('item_group_id','=',$ItemGroup->id)->get();
        foreach ($Items as $Item)
            {
            ?>
            <?php /* card-version
            <a href="{{route('items.show', $Item->id)}}">
                <div class="tile">
                    <img src="/storage/img/items/{{$Item->photo_OK()}}" class="tile">

                    <div class="tiletitle">
                        {{ $Item->group()->item_group_name }}
                    </div>
                </div>
            </a>
            */ ?>
            <li>
            <a href="{{route('items.show', $Item->id)}}">
                        {{ $Item->group()->item_group_name }}
            </a>
            </li>
            <?php
            }
            ?>
        </ol>
    </div>
    <div class="col-sm-4">
    <ol>pliki
        <?php
        foreach ($Pliki as $Plik)
            {
            ?>
            <li>
                {{$Plik->plik_title}}
            </li>
            <?php
            }
            ?>
        </ol>
    </div>

</div>
@endsection