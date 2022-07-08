@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

<?php if ($type_id>0) $type_curr=App\ItemType::where('id',$type_id)->get()->first()->item_type_name; else $type_curr="wszystko"; ?>
@section('title', " zasoby - ".$type_curr )

@section('content')



@if (Auth::user()->hasRole('MagazynierX'))
    @if ( Auth::user()->CenterRole('Magazynier','CS Pielęgniarstwo') )
    echo "1,1";
    @endif
<a class="glyphicon glyphicon-plus-sign glyphiconbig pull-right" href="{{route('devices.create')}}"></a>
@endif

<h1>zasoby: {{$type_curr}}</h1>

@foreach (App\ItemType::typepatcharray($type_id) as $OneType)
<a href="{{route('itemtypes.index', $OneType['id'])}}">
    {{$OneType['name']}}
</a>
<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
@endforeach
@if ($OneType['id']>0)
<a href="{{route('itemtypes.showgroups', $OneType['id'])}}">[pokaż]</a>
@endif



<hr>
<?php /*   card-version 
     @foreach($ItemTypes as $ItemType)
     
        <a href="{{route('itemtypes.index', $ItemType->id)}}">
            <div class="tile">
                <img src="{{$ItemType->photo_OK()}}" class="tile">
                
                <div class="tiletitle">
                    {{ $ItemType->item_type_name }}
                </div>
            </div>
        </a>
    @endforeach
*/ ?>
<ol>
@foreach($ItemTypes as $ItemType)
     <li><a href="{{route('itemtypes.index', $ItemType->id)}}">
        {{ $ItemType->item_type_name }}
     </a></li>
 @endforeach


@endsection