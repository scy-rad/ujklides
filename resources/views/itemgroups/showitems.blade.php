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
@endif



<?php   /*
        |
        | INCLUDE JAVA SCRIPT FILES
        +--------------------------------------------    
        */ ?>
@if ( (Auth::user()->hasRoleCode('itemoperators')) )
    <!-- javascript for Responsive FileManager
        ================================================== --> 
    <!-- Placed at the end of the document so the pages load faster --> 


    <!-- VIDEO -->
    <script src="assets/js/jquery.fitvids.min.js" type="text/javascript"></script>
        
    <script>
        function responsive_filemanager_callback(field_id){
            if(field_id){
                console.log(field_id);
                var url=jQuery('#'+field_id).val();

                // document.getElementById("picture_name_img").src=url;
                // document.getElementById("picture_name").src=url;

                // alert('update '+field_id+" with "+url);
                //your code
            }
        }
    </script>

    <script type="text/javascript">

    jQuery(document).ready(function ($) {
        $('.iframe-btn').fancybox({
            'width'	: 880,
            'height'	: 570,
            'type'	: 'iframe',
            'autoScale'   : false
        });
        //
        // Handles message from ResponsiveFilemanager
        //
        function OnMessage(e){
            var event = e.originalEvent;
            // Make sure the sender of the event is trusted
            if(event.data.sender === 'responsivefilemanager'){
                if(event.data.field_id){
                    var fieldID=event.data.field_id;
                    var url=event.data.url;
                            $('#'+fieldID).val(url).trigger('change');
                            $.fancybox.close();

                            // Delete handler of the message from ResponsiveFilemanager
                            $(window).off('message', OnMessage);
                }
            }
        }

        // Handler for a message from ResponsiveFilemanager
        $('.iframe-btn').on('click',function(){
            $(window).on('message', OnMessage);
        });

        $('#download-button').on('click', function() {
            ga('send', 'event', 'button', 'click', 'download-buttons');      
        });
        $('.toggle').click(function(){
            var _this=$(this);
            $('#'+_this.data('ref')).toggle(200);
            var i=_this.find('i');
            if (i.hasClass('icon-plus')) {
            i.removeClass('icon-plus');
            i.addClass('icon-minus');
            }else{
            i.removeClass('icon-minus');
            i.addClass('icon-plus');
            }
        });
    });

    function open_popup(url)
    {
            var w = 880;
            var h = 570;
            var l = Math.floor((screen.width-w)/2);
            var t = Math.floor((screen.height-h)/2);
            var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
    }
    </script>
@endif

@endsection