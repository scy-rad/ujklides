@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', $room->room_number." ".$room->room_name." - magazyny" )


@section('content')

    <div class="tile">
        <a href="{{route('rooms.show', $room->id)}}">
            <img src="{{ $room->room_photo }}" class="tile">
        </a>
        <div class="tiletitle">
            {{ $room->room_number }}.
            {{ $room->room_name  }}
        </div>
    </div>
    <div class="clearfix"> </div>

@foreach ($room->storages as $storage)
    <div class="boxtitle">
        {{ $storage->room_storage_name }}
    </div>



    {{$cur_type_id=''}}

    <ol>
        <!--foreach ($room->get_Xitems($room->id, 'storage', $storage['storage']->storageid) as $row)-->
        @foreach ($storage->items as $row)
        <!--div class="clearfix" style="border: 2px dashed blue;">&nbsp;</div-->
            <?php
            if ($row->room_storages_shelf_count==1)
                {
                if ($row->item_type_master_id!=$cur_type_id)
                    {
                    echo '<hr>';
                    $cur_type_id=$row->item_type_master_id;
                    //echo $room->get_type_name($row->item_type_master_id);
                    ?>
                    <div class="clearfix" style="border-bottom: 1px dashed">
                        
                    </div>
                    <?php
                    }
                }
            ?>

            <div class="clearfix">
            
            <div style="float:left;">
            
                <li>
                <a href="{{route('items.show', $row->id)}}"> {{$row->group()->item_group_name}} <span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> [{{$row->item_inventory_number}}] </a> 
                @if ($row->room_storage_shelf_count>1) 
                    poziom {{$row->item_storage_shelf}} z {{ $row->room_storage_shelf_count }}
                @endif
                </li>
            </div>
        </div>
        @endforeach
    </ol>

@endforeach

    
@endsection


