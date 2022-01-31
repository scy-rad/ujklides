@extends('layouts.app')

<?php // include(app_path().'/include/view_common.php'); ?>

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', $item->group()->item_group_name. " inv: ".$item->item_inventory_number )


@section('content')
<!--h1> { { $action }} item { { $subid }}</h1-->
<div class="row">
    <div class="col-sm-12">
        {!!$item->group()->type_no_get->typepatch()!!}
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <a href="{{route('items.show', $item->id)}}">
        <div class="tile">
            <img src="{{asset('/storage/'.$item->photo_OK()) }}" class="tile">
            <!--div class="tiletitle">
                {!! $item->group()->item_group_name !!}
                {!! $item->group()->item_group_producent !!}
                {!! $item->group()->item_group_model  !!}
            </div-->
        </div>
        </a>
    </div>
    <div class="col-sm-8">
        <h2>{!! $item->group()->item_group_name !!}</h2>
        <p>producent: <strong>{!! $item->group()->item_group_producent !!}</strong>; 
        model: <strong>{!! $item->group()->item_group_model  !!}</strong></p>
        <br>
        {!! $item->item_description !!}
    </div>
</div>
6 maja godz. 10:00
<div class="row">
    <div class="col-sm-2">
        <div>
        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
        data zakupu<br>
        <strong>{{ $item->item_purchase_date }}</strong>
        </div>
        <div>
        <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
        gwarancja do<br>
        <strong>{{ $item->item_warranty_date }}</strong>    
        </div>
    </div>

    <div class="col-sm-2">
        <div>
        <span class="glyphicon glyphicon-sound-6-1" aria-hidden="true"></span>
        numer seryjny<br>
        <strong>{{ $item->item_serial_number }}</strong>    
        </div>
        <div>
        <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
        numer inwentarzowy<br>
        <strong>{{ $item->item_inventory_number }}</strong>    
        </div>
    </div>

    <div class="col-sm-4">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
        miejsce<br>
        <strong>{{ $item->storage()->room()->room_number }}</strong>
        {{ $item->storage()->room()->room_name }} <br>
        {{ $item->storage()->room_storage_number }} 
        <?php if ($item->storage()->room_storage_shelf_count>1) echo ".".$item->item_storage_shelf; 
        ?>:
        {{ $item->storage()->room_storage_name }}

        

    @if ($item->room_storage_current_id != $item->room_storage_id)
        <div class="bg-primary">
            <strong> wypożyczone do:</strong>
        </div>
        <div class="bg-info">
            <strong>{{ $item->current_storage()->room()->room_number }}</strong>
            {{ $item->current_storage()->room()->room_name }} <br>
            {{ $item->current_storage()->room_storage_number }} 
            {{ $item->current_storage()->room_storage_name }}
        </div>
    @endif
    </div>

    <div class="col-sm-2">
        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
        status<br>
        <strong>{{ $item->item_status }}</strong>    
    </div>


    <div class="col-sm-2">
      @if ( (Auth::user()->hasRole('magazynier'))  || (Auth::user()->hasRole('technik')) )
        <button type="button" class="btn btn-primary btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#realocateModal">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            wypożycz
        </button><br>
      @endif
        <button type="button" class="btn btn-warning btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#faultModal">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            zgłoś usterkę
        </button><br>
    </div>
</div>

@if ( (Auth::user()->hasRole('magazynier'))  || (Auth::user()->hasRole('technik')) )
    @include('items.modalrealocate')
@endif
@include('items.modalfault')

<hr>

<div class="row">
<div class="col-sm-2">
    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
    dokumenty<br>
   <ul>
    @foreach ($item->group()->docs as $doc)
        <li>
        <a href="{{route('items.doc', [$item->id, $doc->id])}}">
        {{$doc->doc_title}}
        </a>
        </li>
    @endforeach
    </ul>

    <hr>
    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
    pliki<br>
   <ul>
    @foreach ($item->group()->files as $plik)
        <li>
        <a href="{{route('items.fil', [$item->id, $plik->id])}}">
        {{$plik->plik_title}}
        </a>
        </li>
    @endforeach
    </ul>

    <hr>
    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
    galerie<br>
    @if ($item->galleries->count()>0)
        <ul>
        @foreach ($item->galleries as $galx)
            <li>
            <a href="{{route('items.gal', [$item->id, $galx->id])}}">
            {{$galx->gallery_name}}
            </a>
            </li>
        @endforeach
        </ul>
    @endif



    <hr>
    <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
    serwis
    @if ( (Auth::user()->hasRole('magazynier'))  || (Auth::user()->hasRole('technik')) )
        <a href="{{ route('fault.showall', $item->id) }}" alt="wszystkie"><span class="bg-info glyphicon glyphicon-th-list pull-right"></span></a>
    @endif
    <br>   
    @if ($item->open_faults->count()>0)
        <ul>
        @foreach ($item->open_faults as $fault_list)
            <li>
            <a href="{{ route('fault.show', $fault_list->id) }}">
            {{$fault_list->fault_title}}
            </a>
            </li>
        @endforeach
        </ul>
    @endif
    @if (($item->active_reviews->count()>0) && (Auth::user()->hasRole('magazynier')))
        <ul>
        @foreach ($item->active_reviews as $review_list)
            <li>
            <a href="{{ route('review.show', $review_list->id) }}">
            {{$review_list->review_title}}
            </a>
            
            </li>
        @endforeach
        </ul>
    @endif



</div>

<?php


/*
echo '<pre>';
    
    
    echo '<hr>';
    
    echo '<hr>';
    //echo ($action);
    //print_r($request);
    echo '</pre><hr>';
*/

?>

<div class="col-sm-10">
    <!--h1>{{$item->item_name}}</h1>
    <h4>$item->get_items_by_type($item->id) </h4-->

@switch($do_what)
    @case("docs")
        <?php   $doc=App\Doc::where('id',$id_what)->get()->first(); ?>
        
        @if ( Auth::user()->hasRole('Magazynier') )
            <a href="{{route('docs.edit', ['doc' => $doc->id] )}}" alt="edytuj">
            <span class="glyphicon glyphicon-edit glyphiconbig pull-right"></span>
            </a>
        @endif
        <hr>
        <h1>{{ $doc->doc_title }}</h1>
        <h3>{{ $doc->doc_subtitle }}</h3>
        {!! $doc->doc_description !!}
    @break
    
    @case("fils")
    <?php   $plik=App\Plik::where('id',$id_what)->get()->first();   ?>
    <iframe src="/storage/files{{$plik->plik_directory}}/{{$plik->plik_name}}" style="width: 100%; box-sizing: border-box;  height: calc(100% - 55px);border: 1px solid #000;">Wystąpił błąd</iframe>
    @break

    @case("gals")
    <?php   $gallery=App\Gallery::where('id',$id_what)->get()->first(); ?>
    <h2>{{$gallery->gallery_name}}</h2>
    <p>{{$gallery->gallery_description}}</p>
    <hr>
    @foreach ($gallery->photos as $photo)
    <a href="/storage/img/{{$gallery->gallery_folder}}/{{$photo->gallery_photo_name}}">
    <div class="tile">
        <img src="/storage/img/{{$gallery->gallery_folder}}/{{$photo->gallery_photo_name}}" class="tile">
        <div class="tiletitle">
            {{$photo->gallery_photo_title}}
        </div>
    </div>
    </a>
    @endforeach
    @break
    
    @case("fault")
        @include('items.incfaults')
    @break

    @case("review")
        @include('items.increviews')
    @break

    @case("nothing")
    @default
        <p>{!! $item->item_description !!}</p>
        <p>{!! $item->group()->item_group_description !!}</p>
@endswitch


</div>

</div>

@endsection

