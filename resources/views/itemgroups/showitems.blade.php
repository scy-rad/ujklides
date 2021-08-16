@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

@section('title', " zasoby - sssssss" )

@section('content')
<h1>egzemplarze: {{$ItemGroup->item_group_name}}</h1>


@foreach (App\ItemType::typepatcharray($ItemGroup->type->id) as $OneType)
<a href="{{route('itemtypes.index', $OneType['id'])}}">
    {{$OneType['name']}}
</a>
<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
@endforeach

<div class="row">

<?php 

        $Items=App\Item::where('item_group_id','=',$ItemGroup->id)->get();
        foreach ($Items as $Item)
            {
            ?>
            <a href="{{route('items.show', $Item->id)}}">
                <div class="tile">
                    <img src="/storage/img/items/{{$Item->photo_OK()}}" class="tile">
                    
                    <div class="tiletitle">
                        {{ $Item->group()->item_group_name }}
                    </div>
                </div>
            </a>
            <?php
            }
?>
</div>
@endsection