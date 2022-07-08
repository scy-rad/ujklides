
@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', {{ $room->room_number." ".$room->room_name  }} )


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
<div>

@if ($room->galleries->count()>0)
<ul><strong>galerie:</strong>
@foreach ($room->galleries as $galx)
    <li>
    <a href="{{route('galleries.show', $galx->gallery->id)}}">
    {{$galx->gallery->gallery_name}}
    </a>
    </li>
@endforeach
</ul>
@endif

</div>
<div class="clearfix"> </div>

    <?php $row_count=1; ?>

    <div class="row">       <!-- row begin -->
        @foreach (App\ItemType::MasterTypes() as $type)
        @if ($row_count++>3)
            </div>              <!-- end of row-->
            <div class="row">   <!-- row begin -->
        <?php $row_count=1; ?>
        @endif

    
        <div class="col-sm-4">
            <div class="boxtitle">
                {{$type->id}}. {{$type->item_type_name}}
            </div>
            <ol>
                @foreach ($room->itemsByType($type->id)->get() as $row)
                <?php
                
                    if ($row->item_room_storage_current_id != $row->item_room_storage_id)
                        {
                        if ($row->storage()->room()->id == $room->id )
                            {
                            $class="bg-danger text-warning font-weight-bold";
                            $tekst=' <br><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> WYPOŻYCZONY DO INNEJ SALI';
                            $tekst.=' - '.$row->storage()->room()->id.' == '.$room->id;
                            }
                        else
                            {
                            $class="bg-warning text-danger font-weight-bold";
                            $tekst=' <br><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> WYPOŻYCZONY Z INNEJ SALI';
                            $tekst.=' - '.$row->storage()->room()->id.' == '.$room->id;
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
        </div>

        @endforeach

        @if ($row_count++>3)
            </div>  <!-- end of row-->
            <div class="row">  <!-- row begin -->
            <?php $row_count=1; ?>
        @endif


        <div class="col-sm-4">
            <div class="boxtitle">
                <a href="{{route('rooms.showstorages', $room->id)}}">magazyny</a>
                [ <a href="{{route('rooms.showinventory', $room->id)}}">inv</a> ]
            </div>
            <ul>
                @foreach ($room->storages as $storage)
                    <li> <b>{{ $storage->room_storages_number }}</b>: {{ $storage->room_storages_name }} <!--({{ $storage->room_storages_description }})-->
                        @if ($storage->room_storages_shelf_count>1)
                        [ilość poziomów: <b>{{ $storage->room_storages_shelf_count }}</b>] 
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

    </div>  <!-- end of row-->

    
@endsection


