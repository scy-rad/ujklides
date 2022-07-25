@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

<!-- scripts for FileManager -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
<!-- /scripts for FileManager -->

@section('title', " zasoby - ".$ItemGroup->item_group_name )

@section('content')

@include('layouts.success_error')

{!!$ItemGroup->type_no_get->typepatch()!!}


<p>producent: <strong>{{$ItemGroup->item_group_producent}}</strong>, model: <strong>{{$ItemGroup->item_group_model}}</strong></p>


<h2>
    <a href="{{route('itemgroups.show_something', [$ItemGroup->id, 'show', 0])}}">       
        {{$ItemGroup->item_group_name}}
    </a>
</h2>

<div class="row">
    <div class="col-sm-4">
        <ol>egzemplarze
        <?php
        $Items=App\Item::where('item_group_id','=',$ItemGroup->id)->get();
        ?>
        @foreach ($Items as $Item)
            <?php /* card-version
            <a href="{{route('items.show', $Item->id)}}">
                <div class="tile">
                    <img src="{{$Item->photo_OK()}}" class="tile">

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
            @endforeach
        </ol>

        <ol>pliki
            @foreach ($pliki as $plik_one)
                <li>
                <a href="{{route('itemgroups.show_something', [$ItemGroup->id, 'fils', $plik_one->id])}}">
                    @if ($plik_one->item_id>0) <span class="glyphicon glyphicon-file" aria-hidden="true"></span> @endif
                    @if ($plik_one->item_group_id>0) <span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> @endif
                    {{$plik_one->plik_title}}
                </a>
                </li>
            @endforeach
        </ol>

        @if ($do_what!='fils')
        <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#fileModal">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            dodaj plik
        </button>
    @endif
        

    </div>
    <div class="col-sm-8">
        @switch($do_what)
            @case("fils")
                <?php   $plik=App\PlikForGroupitem::    where('id',$id_what)->get()->first(); ?>
                @include('items.incfiles')
            @break
            @case("show")
            @default
                <div class="device_photo">
                    <img src="{{asset($ItemGroup->photo_OK())}}" class="device_photo">
                </div>
                @if ( (Auth::user()->hasRoleCode('itemoperators')) )
                    <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#pictureModal">
                        <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                        zmień zdjęcie
                    </button>
                    <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#editModal">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                        edytuj
                    </button>
                @endif
                <p>nazwa:<br>
                <strong>{{$ItemGroup->item_group_name}}</strong></p>
                <p>producent:<br>
                <strong>{{$ItemGroup->item_group_producent}}</strong></p>
                <p>model:<br>
                <strong>{{$ItemGroup->item_group_model}}</strong></p>
                <p>opis:<br>
                <strong>{!!$ItemGroup->item_group_description!!}</strong></p>
                <p>status:<br>
                <strong>{{$ItemGroup->item_group_status}}</strong></p>
                
        @endswitch

    </div>

</div>

@if ( (Auth::user()->hasRoleCode('itemoperators')) )
    @include('pliks.modalfile')
    <?php
    $action    = route('itemgroups.update', $ItemGroup->id);
    $photo_old = asset($ItemGroup->item_group_photo);
    $picture_name = 'picture_name';
    $picture_name_img = 'picture_name_img';
    ?>
    @include('pliks.modalpicture')
    @include('itemgroups.modaledit')
@endif



<?php   /*
        |
        | INCLUDE JAVA SCRIPT FILES
        +--------------------------------------------    
        */ ?>
@if ( (Auth::user()->hasRoleCode('itemoperators')) )
    @include('pliks.modaljs')
@endif

@endsection