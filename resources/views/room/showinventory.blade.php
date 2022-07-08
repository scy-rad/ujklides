@extends('layouts.app')



<link href="{{ asset('css/device.css') }}" rel="stylesheet">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
        <img src="{{ $room->rooms_photo }}" class="tile">
    </a>
    <div class="tiletitle">
        {{ $room->rooms_number }}.
        {{ $room->rooms_name  }}
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
        {{ $storage->room_storages_name }}
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
    
                <div style="float:right;">
                    <img src="{{ $row->item_photo }}" class="tile">
                    {{$row->inventory_item_status}}
                    
                </div>
                @if ($row->get_inventory($row->id_item)->get()->count()>0)
                <?php $row->id_inv=$row->get_inventory($row->id_item)->first()->id; ?>
                    <form action="">
                    <div id="form_act_{{$row->id_inv}}" style="float:right">
                        <button type="button" id="show_{{$row->id_inv}}" onclick="myFun_more({{$row->id_inv}})" value="więcej" style="float:right">
                            <span id="form_sym_{{$row->id_inv}}" class="{{show_gl_ico($row->get_inventory($row->id_item)->first()->inventory_item_status)}}"></span>
                        </button>
                        <br>
                        <span id="txt_des_{{$row->id_inv}}"> {{$row->get_inventory($row->id_item)->first()->inventory_item_description}}</span>
                                <!--input type="checkbox" nochecked data-toggle="toggle" data-on="sprawdzony" data-off="nie sprawdzony" data-onstyle="success" data-offstyle="danger"-->
                    </div>
                            
                    <div id="form_hid_{{$row->id_inv}}" style="display:none;"  style="float:right;">
                        <div style="float:right;">
                            <div class="cc-selector">
                                <input id="check_in_{{$row->id_inv}}" type="radio" name="grradio_{{$row->id_inv}}" value="3"  <?php if ($row->get_inventory($row->id_item)->first()->inventory_item_status==3) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_in_{{$row->id_inv}}"><span class="{{show_gl_ico(3)}}"></span></label>
                                <input id="check_no_{{$row->id_inv}}" type="radio" name="grradio_{{$row->id_inv}}" value="2"  <?php if ($row->get_inventory($row->id_item)->first()->inventory_item_status==2) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_no_{{$row->id_inv}}"><span class="{{show_gl_ico(2)}}"></span></label>
                                <input id="check_ok_{{$row->id_inv}}" type="radio" name="grradio_{{$row->id_inv}}" value="1"  <?php if ($row->get_inventory($row->id_item)->first()->inventory_item_status==1) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_ok_{{$row->id_inv}}"><span class="{{show_gl_ico(1)}}"></span></label>
                                <input id="check_of_{{$row->id_inv}}" type="radio" name="grradio_{{$row->id_inv}}" value="0"  <?php if ($row->get_inventory($row->id_item)->first()->inventory_item_status==0) echo 'checked="checked"';?> />
                                    <label class="drinkcard-cc" for="check_of_{{$row->id_inv}}"><span class="{{show_gl_ico(0)}}"></span></label>
                                    
                                <input type="text" class="form-control" id="check_descript_{{$row->id_inv}}" name="check_descript_{{$row->id_inv}}" value="{{$row->get_inventory($row->id_item)->first()->inventory_item_description}}"/>
                            </div>
                        </div>
                        <div style="float:right; padding:15px;">
                            <button type="button" id="guzik_{{$row->id_inv}}" onclick="myFun_save({{$row->id_inv}})" value="zapisz">
                                <span class="text-info glyphicon glyphicon glyphicon-floppy-saved gi-5x"></span>
                            </button>
                        </div>
                    </div>
                    </form>          
                @endif
            
            
            <div style="float:left;">
            
                <li><a href="{{route('items.show', $row->id_item)}}"> {{$row->item_group_name}} <!-- {{$row->item_group_producent}} {{$row->item_group_model}} --><br>
                    [inv: {{$row->item_inventory_number}}] <br>
                    [s/n: {{$row->item_serial_number}}] <br>
                    </a> 
                @if ($row->room_storages_shelf_count>1) 
                    poziom {{$row->item_storage_shelf}} z {{ $row->room_storages_shelf_count }}
                @endif
                </li>

        </div>
        </div>
        @endforeach
    </ol>

    @endforeach


@endsection


<script>


function ChangeInventoryItem(MyInv) { 
    

    var radios = document.getElementsByName('grradio_'+MyInv);

for (var i = 0, length = radios.length; i < length; i++) {
  if (radios[i].checked) {
    // do whatever you want with the checked radio
    var b_type = (radios[i].value);

    // only one radio can be logically checked, don't check the rest
    break;
  }
}
    

    var urlX = 'ajaxData/'+MyInv;
    //var url = "{{URL('+MyInv+')}}";
    var url = "{{URL("ajaxData/")}}"+'/'+MyInv;
    //var alx = document.getElementById('check_descript_'+MyInv).value;
    //alert('sprawdź toto: showinventory.blade.php '+$('#check_descript_'+MyInv).val());
    //alert('UjUjU: '+b_type+$('#guzik_'+MyInv).val());
    var id= 
    $.ajax({
        url: url,
        type: "PATCH",
        cache: false,
        data:{
            _token:'{{ csrf_token() }}',
            type: 3,
            invit_descript: $('#check_descript_'+MyInv).val(),
            invit_status: b_type,
            //$('#grradio_'+MyInv).val()
        },
        success: function(dataResult){
            dataResult = JSON.parse(dataResult);
            
         if(dataResult.statusCode)
         {
            //alert("SD SUCCESS: ["+b_type+"] "+dataResult.SQLcode);
            //window.location = "/ajaxData";
         }
         else{
             alert("Internal Server Error");
         }
            
        }  
    }); //ajax
    /* */    
} 

    function myFun_more(MyIdX) {
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
        var x = document.getElementById("form_sym_"+MyIdX);
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

        
        document.getElementById('txt_des_'+MyIdX).textContent=document.getElementById('check_descript_'+MyIdX).value;

        //var y = document.forms.your-form-name.elements.radio-button-name.value
        //alert(document.querySelector('input[name="grradio_'+MyIdX+'"]:checked').value);
        myFun_more(MyIdX);
        ChangeInventoryItem(MyIdX);
        //alert('sssssssssssss');

        //https://appdividend.com/2018/02/07/laravel-ajax-tutorial-example/
        //https://www.positronx.io/laravel-ajax-example-tutorial/
    }
</script>
<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
