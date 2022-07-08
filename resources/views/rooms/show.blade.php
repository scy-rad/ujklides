
@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', $room->room_number." ".$room->room_name )


@section('content')

<div class="col-sm-8">
    <h1>{{ $room->room_number }}<br>
    {{ $room->room_name  }}</h1>
    <img src="{{ $room->room_photo }}" class="col-sm-12">
    {!! $room->room_description  !!}
</div>


<div class="col-sm-4">
    <div class="tile" style="float:right">
        <a href="{{route('rooms.show', $room->id)}}">
            <img src="{{ $room->room_photo }}" class="tile">
        </a>
        <div class="tiletitle">
            {{ $room->room_number }}.
            {{ $room->room_name  }}
        </div>
    </div>
    <div class="clearfix"> </div>
    @if ($room->galleries->count()>0)
        <div class="head_content">
            galerie:
        </div>
        <ul>
        @foreach ($room->galleries as $galx)
            <li>
                <a href="{{route('galleries.show', $galx->id)}}">
                {{$galx->gallery_name}}
                </a>
            </li>
        @endforeach
        </ul>
    @endif
    @foreach (App\ItemType::MasterTypes() as $type)

    <div class="head_content">
        {{$type->id}}. {{$type->item_type_name}}
    </div>

    <ol>
        @foreach ($room->itemsByType($type->id)->get() as $row)
        <?php
            if ($row->room_storage_current_id != $row->room_storage_id)
                {
                if ($row->storage()->room()->id == $room->id )
                    {
                    $class="bg-danger text-warning font-weight-bold";
                    $tekst=' <br><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <strong>wypożyczony do innej sali</strong>';
                    $tekst.=' <span class="glyphicon glyphicon-home" aria-hidden="true"></span> '.$row->current_storage()->room()->room_number;
                    }
                else
                    {
                    $class="bg-warning text-danger font-weight-bold";
                    $tekst=' <br><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <strong>wypożyczony z innej sali</strong>';
                    $tekst.=' <span class="glyphicon glyphicon-home" aria-hidden="true"></span> '.$row->storage()->room()->room_number;
                    }
                }
            else
                {
                $class="";
                $tekst="";
                }
            ?>

            <li>
                <div class="{{$class}}">
                    <a href="{{route('items.show', $row->id_item)}}"> {{$row->item_group_name}} <!-- {{$row->item_group_producent}} {{$row->item_group_model}}  [{{$row->item_inventory_number}}] --> {!!$tekst!!}</a> 
                </div>
            </li>
        @endforeach
    </ol>
    @endforeach


    <hr>



    <div class="head_content">
        <a href="{{route('rooms.showstorages', $room->id)}}">magazyny</a>
        [ <a href="{{route('rooms.showinventory', $room->id)}}">inv</a> ]
    </div>
    <ul>
        @foreach ($room->storages as $storage)
            <li> <b>{{ $storage->room_storage_number }}</b>: {{ $storage->room_storage_name }} <!--({{ $storage->room_storages_description }})-->
                @if ($storage->room_storage_shelf_count>1)
                [ilość poziomów: <b>{{ $storage->room_storage_shelf_count }}</b>]
                @endif
            </li>
        @endforeach
    </ul>
</div>

@endsection


