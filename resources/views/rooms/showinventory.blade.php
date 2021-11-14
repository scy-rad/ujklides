@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@section('title', $room->room_number." ".$room->room_name." - inwentaryzacja" )

<style>
.gi-2x{font-size: 2em;}
.gi-3x{font-size: 3em;}
.gi-4x{font-size: 4em;}
.gi-5x{font-size: 5em;}
.szary{color: gray; opacity: 0.2;}
</style>

@section('content')

<div class="tile">
    <a href="{{route('rooms.show', $room->id)}}">
        <img src="/storage/img/rooms/{{ $room->room_photo }}" class="tile">
    </a>
    <div class="tiletitle">
        {{ $room->room_number }}.
        {{ $room->room_name  }}
    </div>
</div>
<div class="clearfix"> </div>


<?php

    function show_gl_ico($aF_ico)
    {
    $ret = "text-primary glyphicon ";
    switch ($aF_ico)
        {
        case 0:
            $ret.="text-primary glyphicon-question-sign";
            break;
        case 1:
            $ret.="text-success glyphicon-ok-sign";
            break;
        case 2:
            $ret.="text-danger glyphicon-remove-sign";
            break;
        case 3:
            $ret.="text-warning glyphicon-info-sign";
            break;
        }

    $ret.= " gi-4x";
    return $ret;
    }

    ?>



    @foreach ($room->storages as $storage)
    <div class="boxtitle">
        {{ $storage->room_storage_name }}
    </div>

    <style>
    .cc-selector input{
    margin:0;padding:0;
    -webkit-appearance:none;
       -moz-appearance:none;
            appearance:none;
    }

    .cc-selector input:active +.drinkcard-cc{opacity: .9;}
    .cc-selector input:checked +.drinkcard-cc{
        -webkit-filter: none;
        -moz-filter: none;
                filter: none;
    }
    .drinkcard-cc{
        cursor:pointer;
        background-size:contain;
        background-repeat:no-repeat;
        display:inline-block;
        width:100px;height:70px;
        -webkit-transition: all 100ms ease-in;
        -moz-transition: all 100ms ease-in;
                transition: all 100ms ease-in;
        -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
        -moz-filter: brightness(1.8) grayscale(1) opacity(.7);
                filter: brightness(1.8) grayscale(1) opacity(.7);
    }
    .drinkcard-cc:hover{
        -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
        -moz-filter: brightness(1.2) grayscale(.5) opacity(.9);
                filter: brightness(1.2) grayscale(.5) opacity(.9);
    }

    /* Extras */
    a:visited{color:#888}
    a{color:#444;text-decoration:none;}
    p{margin-bottom:.3em;}
    </style>

    {{$cur_type_id=''}}

    <ol>
        @foreach ($storage->items as $item)
            @if ($item->storage()->room_storage_shelf_count==1)
                @if ($item->group()->type()->item_type_master_id!=$cur_type_id)
                    <div class="clearfix" style="margin-bottom: 10px; border-bottom: 1px dashed">
                    </div>
                    <?php $cur_type_id=$item->item_type_master_id; ?>
                @endif
            @endif

            <div class="clearfix">

                <div style="float:right;">
                    <img src="/storage/img/{{ $item->item_photo }}" class="tile">
                </div>
                <?php
                //@if ($item->active_inventory()->count()>0) ?>
                @if ((!(is_null($item->active_inventory()))) && (Auth::user()->hasRole('magazynier')))

                <?php $item->id_inv=$item->active_inventory()->id;
                      $item->id_item=$item->id;
                ?>
                    <form action="">
                    <div id="form_act_{{$item->id_inv}}" style="float:right">
                        <button type="button" id="show_{{$item->id_inv}}" onclick="myFun_more({{$item->id_inv}})" value="więcej" style="float:right">
                            <span id="form_sym_{{$item->id_inv}}" class="{{show_gl_ico($item->active_inventory()->inventory_item_status)}}"></span>
                        </button>
                        <br>
                        <span id="txt_des_{{$item->id_inv}}"> {{$item->active_inventory()->inventory_item_description}}</span>
                                <!--input type="checkbox" nochecked data-toggle="toggle" data-on="sprawdzony" data-off="nie sprawdzony" data-onstyle="success" data-offstyle="danger"-->
                    </div>

                    <div id="form_hid_{{$item->id_inv}}" style="display:none;"  style="float:right;">
                        <div style="float:right;">
                            <div class="cc-selector">
                                <input id="check_in_{{$item->id_inv}}" type="radio" name="grradio_{{$item->id_inv}}" value="3"  <?php if ($item->active_inventory()->inventory_item_status==3) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_in_{{$item->id_inv}}"><span class="{{show_gl_ico(3)}}"></span></label>
                                <input id="check_no_{{$item->id_inv}}" type="radio" name="grradio_{{$item->id_inv}}" value="2"  <?php if ($item->active_inventory()->inventory_item_status==2) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_no_{{$item->id_inv}}"><span class="{{show_gl_ico(2)}}"></span></label>
                                <input id="check_ok_{{$item->id_inv}}" type="radio" name="grradio_{{$item->id_inv}}" value="1"  <?php if ($item->active_inventory()->inventory_item_status==1) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_ok_{{$item->id_inv}}"><span class="{{show_gl_ico(1)}}"></span></label>
                                <input id="check_of_{{$item->id_inv}}" type="radio" name="grradio_{{$item->id_inv}}" value="0"  <?php if ($item->active_inventory()->inventory_item_status==0) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_of_{{$item->id_inv}}"><span class="{{show_gl_ico(0)}}"></span></label>

                                <input type="text" class="form-control" id="check_descript_{{$item->id_inv}}" name="check_descript_{{$item->id_inv}}" value="{{$item->active_inventory()->inventory_item_description}}"/>
                            </div>
                        </div>
                        <div style="float:right; padding:15px;">
                            <button type="button" id="guzik_{{$item->id_inv}}" onclick="myFun_save({{$item->id_inv}})" value="zapisz">
                                <span class="text-info glyphicon glyphicon glyphicon-floppy-saved gi-5x"></span>
                            </button>
                        </div>
                    </div>
                    </form>
                @endif


            <div style="float:left;">

                <li><a href="{{route('items.show', $item->id)}}"><h3>{{$item->group()->item_group_name}} <!-- {{$item->item_group_producent}} {{$item->item_group_model}} --></h3>
                    [inv: {{$item->item_inventory_number}}] <br>
                    [s/n: {{$item->item_serial_number}}] <br>
                    </a>
                @if ($item->storage()->room_storage_shelf_count>1)
                    poziom {{$item->item_storage_shelf}} z {{ $item->storage()->room_storage_shelf_count }}
                @endif
                </li>

        </div>
        </div>
        @endforeach
    </ol>

    @endforeach



@endsection







@if ((!(is_null($item->active_inventory()))) && (Auth::user()->hasRole('magazynier')))
<script>


function ChangeInventoryItem(MyInv) {
    // sprawdzam, którą opcję wybrałem w polack o id MyInv

    var radios = document.getElementsByName('grradio_'+MyInv);

    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            // jeśli aktywne, przypisz jego wartość do zmiennej b_type
            var b_type = (radios[i].value);
            // tylko jedno pole radio może być aktywne, więc nie sprawdzaj dalej :)
            break;
        }
    }

    //event.preventDefault();

//let email = $("input[name=email]").val();
let descript = $('#check_descript_'+MyInv).val();
let _token   = '{{csrf_token()}}';

$.ajax({
  url: "/ajax-inventory",
  type:"POST",
  data:{
    inv:MyInv,
    b_type:b_type,
    descript:descript,
    _token: _token
  },
  success:function(response){
    console.log(response);
    if(response) {
      $('.success').text(response.success);
      //$("#ajaxform")[0].reset();
      //alert(response.success);
    }
  },
 });

    /* */
    }

function myFun_more(MyIdX) {
    // odkrywa lub ukrywa kliknięty element
    var x = document.getElementById("form_act_"+MyIdX);
    var y = document.getElementById("form_hid_"+MyIdX);
        //x.style.display = "none";
        //y.style.display = "block";
        //alert("form_act_"+MyIdX)
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
        } else {
        x.style.display = "none";
        y.style.display = "block";
    }
}

function myFun_save(MyIdX) {
    // Funkcja zapisująca zmiany
    //pobierz element obrazujący klikniętą opcję
    var x = document.getElementById("form_sym_"+MyIdX);

        //w zależności od klikniętej opcji - przypisz styl do elementu obrazującego
        switch (document.querySelector('input[name="grradio_'+MyIdX+'"]:checked').value){
            case '0':
                x.className = "text-primary glyphicon glyphicon-question-sign gi-4x";
                break;
            case '3':
                x.className = "text-warning glyphicon glyphicon-info-sign gi-4x";
                break;
            case '2':
                x.className = "text-danger glyphicon glyphicon-remove-sign gi-4x";
                break;
            case '1':
                x.className = "text-success glyphicon glyphicon-ok-sign gi-4x";
                break;
            default:
                alert(MyIdX);
        }

        // przypisz wprowadzony opis do pola, które się pokaże po ukryciu diva edycji
        document.getElementById('txt_des_'+MyIdX).textContent=document.getElementById('check_descript_'+MyIdX).value;

        // ukryj lub odkryj div edycji
        myFun_more(MyIdX);
        // wywołaj funkcję z ajaxem
        ChangeInventoryItem(MyIdX);

        //https://codingdriver.com/ajax-post-request-laravel-example.html
    }
</script>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

@endif
