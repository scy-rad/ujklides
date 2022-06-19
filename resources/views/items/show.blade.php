@extends('layouts.app')

<?php // include(app_path().'/include/view_common.php'); ?>

<link href="{{ asset('css/device.css') }}" rel="stylesheet">

<!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script-->


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}" />

@section('title', $item->group()->item_group_name. " inv: ".$item->item_inventory_number )
<script type="text/javascript" src="{{ URL::asset('js/jquery.schedule/dist/js/jq.schedule.js')}}"></script>

@section('content')
    <div class="container">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {{ $message }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Uuuups!</strong> Przecież to nie powinno się wydarzyć!<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
<!--h1> { { $action }} item { { $subid }}</h1-->
<div class="row">
    <div class="col-sm-12">
        {!!$item->group()->type_no_get->typepatch()!!}
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <a href="{{route('items.show', $item->id)}}">
        <div class="device_photo">
            <img src="{{asset('/storage/images/'.$item->photo_OK()) }}" class="device_photo">
        </div>
        </a>
    </div>
    <div class="col-sm-6">
        <h2>{!! $item->group()->item_group_name !!}</h2>
        producent: <strong>{!! $item->group()->item_group_producent !!}</strong>; 
        model: <strong>{!! $item->group()->item_group_model  !!}</strong>
        <br>
        opis: <strong>{!! $item->item_description !!}</strong>
        
    </div>
    <div class="col-sm-3 device_yellow">
    <strong>testowe dane:</strong><br>
    item_group_name: <strong>{!! $item->group()->item_group_name !!}</strong><br>
    item_group_producent: <strong>{!! $item->group()->item_group_producent !!}</strong><br>
    item_group_model: <strong>{!! $item->group()->item_group_model  !!}</strong><br>
    </div>
</div>

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
        <!--{{ $item->storage()->room_storage_number }}-->
        {{ $item->storage()->room_storage_name }}
        @if ($item->storage()->room_storage_shelf_count>1) , poziom: {{$item->item_storage_shelf}} @endif 
        
        

        

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
        <strong>{{ $item->show_status() }}</strong>    
    </div>


    <div class="col-sm-2">
      @if ( (Auth::user()->hasRoleCode('serviceworkers'))  || (Auth::user()->hasRoleCode('technicians')) )
        <button type="button" class="btn btn-primary btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#realocateModal">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
            wypożycz
        </button><br>
      @endif
        <button type="button" class="btn btn-warning btn-outline-primary btn-lg btn-block" data-toggle="modal" data-target="#faultModal">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
            zgłoś usterkę
        </button><br>
      @if ( (Auth::user()->hasRoleCode('itemoperators')) )
        <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#editModal">
        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            edytuj
        </button><br>
            <?php //@if ($item->room_storage_current_id != $item->room_storage_id) ?>
                <button type="button" class="btn btn-info btn-outline-danger btn btn-block" data-toggle="modal" data-target="#changeLocalizationModal">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                    zmień lokalizację
                </button><br>
            <?php //@endif ?>
        <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#pictureModal">
        <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
            zmień zdjęcie
        </button><br>
      @endif
    </div>
</div>

@if ( (Auth::user()->hasRoleCode('serviceworkers'))  || (Auth::user()->hasRoleCode('technicians')) )
    @include('items.modalrealocate')
@endif
@if ( (Auth::user()->hasRoleCode('itemoperators')) )
    @include('items.modaledit')
    @include('items.modalpicture')
    @include('items.modalchangelocalization')
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
    @if ( (Auth::user()->hasRoleCode('serviceworkers')) || (Auth::user()->hasRoleCode('technicians')) )
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
    @if ( ($item->active_reviews->count()>0) && (Auth::user()->hasRoleCode('serviceworkers')) )
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
        
        @if (Auth::user()->hasRoleCode('serviceworkers'))
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
    <a href="/storage/images/{{$gallery->gallery_folder}}/{{$photo->gallery_photo_name}}">
    <div class="tile">
        <img src="/storage/images/{{$gallery->gallery_folder}}/{{$photo->gallery_photo_name}}" class="tile">
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

    @case("basic_view")
    @default
        <p>{!! $item->item_description !!}</p>
        <p>{!! $item->group()->item_group_description !!}</p>
@endswitch


</div>

</div>






















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

            document.getElementById("picture_name_img").src=url;
            document.getElementById("picture_name").src=url;

			//alert('update '+field_id+" with "+url);
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

