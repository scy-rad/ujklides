<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku Palne','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>



@extends('layouts.app')

@section('title', 'symulacje')


@section('content')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script-->
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>

<?php
    $lista_tech='<div id ="form_open"><form class="klasa_form"><select class="form-select form-select-lg" id="changed_technician">';
    $lista_tech.='<option value="0"> - - - (brak wyboru) </option>';
    foreach ($technician_list as $technician_one)
        $lista_tech.='<option value="'.$technician_one['id'].'">'.$technician_one->full_name().'</option>';
    $lista_tech.='</select></form><span id="form_close" onclick="close_form()" class="glyphicon glyphicon-remove text-danger pull-left" style="font-size: 2em;"></span> <span onClick="technician_change()" class="glyphicon glyphicon-ok text-success pull-right" style="font-size: 2em;"></span></div>';

    //$dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );
    $dni_tygodnia = array( 'Ni', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So' );
    $curr_date='';  // do kolorowania wierszy
    $curr_class=''; // do kolorowania wierszy
    

        
 ?>

@foreach ($to_plane as $one_row)
    {{$one_row->simmed_date}};
    {{$one_row->simmed_time_begin}};
    {{$one_row->simmed_time_end}};
    {{$one_row->room()->room_number }};
    {{$one_row->name_of_leader()}};
    {{$one_row->name_of_student_subject() }};
    {{$one_row->name_of_student_group() }};
    {{$one_row->name_of_student_subgroup() }}<br>
@endforeach
<?php dd(); ?>

<span id="current_row" data-value="jakieś value"></span>

<div class="form-group">
    <div class="form-row">
        <div class="col-md-3">
            <form action="/simmedsplane">
                <!--span class="glyphicon glyphicon-backward"></span--> 
                <input type="hidden" name="date" value="{{$sch_date_prev}}">
                <input type="submit" class="form-control" value="Poprzedni tydzień od {{$sch_date_prev}}">
                <input type="hidden" name="csm" value="{{$sch_csm}}">
            </form>
        </div>
        <div class="col-md-2 align-items-center">
            <span class="glyphicon glyphicon-calendar"></span>{{$sch_date}}<span class="glyphicon glyphicon-calendar"></span>
        </div>
        <div class="col-md-3">
            <form action="/simmedsplane">
                <!--span class="glyphicon glyphicon-forward"></span-->
                <input type="hidden" name="date" value="{{$sch_date_next}}">
                <input type="submit" class="form-control" value="Kolejny tydzień od {{$sch_date_next}}">
                <input type="hidden" name="csm" value="{{$sch_csm}}">
            </form>
        </div>
        <div class="col-md-2">
            <form action="/simmedsplane">
                <input type="hidden" name="date" value="{{$sch_date}}">
                <div class="form-row">
                    <div class="col-auto">
                        <select class="form-control" name="csm">
                            <option value="0" @if (0 == $sch_csm) selected="selected" @endif>wszystko</option>
                            <option value="-1" @if (-1 == $sch_csm) selected="selected" @endif>nieokreślone</option>
                            @foreach ($center_list as $center_one)
                            <option value="{{$center_one->id}}" @if ($center_one->id == $sch_csm) selected="selected" @endif>{{$center_one->center_direct}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="submit" class="form-control" value="pokaż grupy">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <!--th scope="col">ids</th-->
          <th scope="col">data</th>
          <th scope="col">od - do</th>
          <th scope="col">Nr sali</th>
          <th scope="col">typ</th>
          <th scope="col"><div style="width:200px;">technik</div></th>
          <th scope="col">instruktor</th>
          <th scope="col">temat</th>
          <th scope="col">grupa</th>
          <!--th scope="col">Statusik</th-->
      <tr>
    </thead>

    <tbody>
        @foreach ($rows_plane as $row_one)
        <?php
        if ($curr_date != $row_one->simmed_date)
        {
            $curr_date = $row_one->simmed_date;
            if ($curr_class=="bg-info")
                $curr_class="bg-white";
            else
                $curr_class="bg-info";
        }
        ?>
        <tr class="{{$curr_class}}">
            <!--th scope="row">{{$row_one->sim_id }}</th-->
            <td>{{$row_one->simmed_date }}: {{$dni_tygodnia[ date('w',strtotime($row_one->simmed_date)) ]}}</td>
            <td>{{substr($row_one->simmed_time_begin,0,5) }} - {{ substr($row_one->simmed_time_end,0,5) }}</td>
            <td>{{$row_one->room()->room_number }} {{$row_one->technician_id}}</td>
            <td><div id="type{{$row_one->sim_id }}" onclick="ChangeSimType({{$row_one->sim_id }},{{$row_one->simmed_technician_character_id *1 }});"> {{$row_one->technician_character()->character_short }} </div></td>
            <td><div id="{{$row_one->sim_id }}" onclick="onclickHandler({{$row_one->sim_id }},{{$row_one->simmed_technician_id *1 }});"> {{$row_one->name_of_technician() }} </div></td>

            <td>{{$row_one->name_of_leader() }}</td>
            <td>{{$row_one->name_of_student_subject() }}</td>
            <td>{{$row_one->name_of_student_group() }}</td>
            <!--td><a href="#" class=actions>X: {{$row_one->simmed_status }}</a></td-->
        <tr>
        @endforeach
    </tbody>
</table>

<script>
    function ChangeSimType(id_row,id_technika)
    {

        zmiana = document.getElementById('type'+id_row);

        let new_val = '{!!$technician_char[1]['character_short']!!}';
        zmiana.innerHTML=new_val;

        zmiana.setAttribute("onclick","('somethingDiff1',"+"'somethingDiff2')");

        ajaxtechnicianchar

    }


    function onclickHandler(id_row,id_technika)
    {
    jestjuz = document.getElementsByClassName("klasa_form");
    if (jestjuz.length == 0)
        {
        zmiana = document.getElementById(id_row);
        let lista_tech   = '{!!$lista_tech!!}';

        zmiana.parentNode.insertAdjacentHTML('beforeEnd',lista_tech);

        var curr_row = document.getElementById("current_row");
        curr_row.dataset.value = id_row;

        //alert('Akapit został kliknięty! div: '+id_row+' instr: '+id_technika);
        }

    }

    function close_form()
    {
        var elem = document.getElementById("form_open");
        elem.remove();
        //alert('usuwam');
    }

    var technician_change = function()
        {
            var techid = document.getElementById("changed_technician");
            var curr_row = document.getElementById("current_row");

            var curr_tech_name = document.getElementById(curr_row.dataset.value);

            curr_tech_name.innerHTML=techid.options[techid.selectedIndex].text;

            //alert('zmianiam wiersz '+curr_row.dataset.value+' na id '+techid.value+' czyli na '+techid.options[techid.selectedIndex].text);

            let _token   = '{{csrf_token()}}';
            $.ajax(
            {
            url: "/simmed/ajaxsavetechnician",
            type:"POST",
            data:{
                id: curr_row.dataset.value,
                technician_id: techid.value,
                _token: _token
                },
            success:function(response)
                {
                    console.log(response);
                    if(response)
                    {
                        $('.success').text(response.success);
                        //$("#ajaxform")[0].reset();
                        //alert(response.success);
                    }
                },
            });

            var elem = document.getElementById("form_open");
            elem.remove();

        };
    </script>
@endsection