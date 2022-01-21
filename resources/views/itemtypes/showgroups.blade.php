@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

@section('title', " zasoby - sssssss" )

@section('content')
<h1>zasoby typu: {{$ItemType->item_type_name}}</h1>


@foreach (App\ItemType::typepatcharray($ItemType->id) as $OneType)
<a href="{{route('itemtypes.index', $OneType['id'])}}">
    {{$OneType['name']}}
</a>
<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
@endforeach
<a href="{{route('itemtypes.showitems', $OneType['id'])}}">[pokaż egzemplarze]</a>

<?php 




function show_groups($curr_id)
{
    $ItemGroups=App\ItemGroup::where('item_type_id','=',$curr_id)->get();

    foreach ($ItemGroups as $ItemGroup)
        {
        ?>
        <?php /* card-version
        <a href="{{route('itemgroups.showitems', $ItemGroup->id)}}">
            <div class="tile">
                <img src="/storage/img/items/{{$ItemGroup->photo_OK()}}" class="tile">
                
                <div class="tiletitle">
                    {{ $ItemGroup->item_group_name }}
                </div>
            </div>
        </a>
        */ ?>
        <li>
        <a href="{{route('itemgroups.showitems', $ItemGroup->id)}}">
                    {{ $ItemGroup->item_group_name }}
        </a>
        </li>
        <?php
        }
}


function recursive_types($curr_id)
{
    ?>

    <div class="col-sm-12">
    {{App\ItemType::where('id','=',$curr_id)->get()->first()->item_type_name}}
    <br>
    {{show_groups($curr_id)}}
    </div>

    <?php
    foreach (App\ItemType::where('item_type_parent_id','=',$curr_id)->get() as $CurType)
        {
            recursive_types($CurType->id);
        }

}


recursive_types($ItemType->id);
?>

@endsection