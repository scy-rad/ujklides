@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

@section('title', " zasoby - sssssss" )

@section('content')
<h1>egzemplarze typu: {{$ItemType->item_type_name}}</h1>


@foreach (App\ItemType::typepatcharray($ItemType->id) as $OneType)
<a href="{{route('itemtypes.index', $OneType['id'])}}">
    {{$OneType['name']}}
</a>
<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
@endforeach


<?php 




function show_groups($curr_id)
{
    $ItemGroups=App\ItemGroup::where('item_type_id','=',$curr_id)->get();

    foreach ($ItemGroups as $ItemGroup)
        {
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
        }
}


function recursive_types($curr_id)
{
    //echo '<ul><li>';
    echo '<div class="row">';
    echo App\ItemType::where('id','=',$curr_id)->get()->first()->item_type_name;
    echo '<br>';
    show_groups($curr_id);
    echo '</div>';
    //echo '</li>';
    
    foreach (App\ItemType::where('item_type_parent_id','=',$curr_id)->get() as $CurType)
        {
            recursive_types($CurType->id);
        }
    //echo '</ul>';
}

echo '<div class="container">';
recursive_types($ItemType->id);
echo '</div>';

//show_groups($ItemType->id);
?>
@endsection