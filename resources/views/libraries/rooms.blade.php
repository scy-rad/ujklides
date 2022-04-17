<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj salami")

@section('content')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>


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

<h1>Zarządzenie salami</h1>


<button onClick="javascript:showMyModalForm('0','0','0')" type="button" class="btn btn-primary">Dodaj...</button>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">numer</th>
          <th scope="col" data-sortable="true">nazwa</th>
          <th scope="col">opis</th>
          <th scope="col">kod UXP</th>
          <th scope="col">prop. char.</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($rooms as $room)
    <tr>
        <td>
        {{$room->id}}
        </td>
        <td>
        <span id="number{{$room->id}}">{{$room->room_number}}</span>
        </td>
        <td>
        <span id="name{{$room->id}}">{{$room->room_name}}</span>
        </td>
        <td>
        <span id="descript{{$room->id}}">{{$room->room_description}}</span>
        </td>
        <td>
        <span id="xp{{$room->id}}">{{$room->room_xp_code}}</span>
        </td>
        <td>
        <span>{{$room->character_short}}</span>
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('{{$room->id}}','{{$room->center_id}}','{{$room->simmed_technician_character_propose_id}}')">Edycja</span>
        <span hidden id="type{{$room->id}}">{{$room->room_type_id}}</span>
        <span hidden id="photo{{$room->id}}">{{$room->room_photo}}</span>
        <span hidden id="status{{$room->id}}">{{$room->room_status}}</span>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja sali</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_room') }}" method="post">



        <div class="form-group">
            <label for"modal_number">numer:</label>
            <input type="text" class="form-control" id="modal_number" name="modal_number">
    
            <label for"modal_name">nazwa:</label>
            <input type="text" class="form-control" id="modal_name" name="modal_name">

            <label for"modal_description">opis:</label>
            <input type="text" class="form-control" id="modal_description" name="modal_description">

            <label for"modal_xp_code">kod UXP:</label>
            <input type="text" class="form-control" id="modal_xp_code" name="modal_xp_code">

            <label for"modal_type">rodzaj sali:</label>
            <input type="text" class="form-control" id="modal_type" name="modal_type">
            1: ćwiczeniowa 2: magazynowa 3: obsługi. 4: inna (np. korytarz) +10: other center<br>

            <label for"modal_center">kierunek:</label>
            <select class="form-control" id="modal_center" name="modal_center">
                @foreach ($centers as $row_one)
                <option value="{{$row_one->id}}">{{$row_one->center_name}}</option>
                @endforeach
            </select>

            <label for"modal_photo">zdjęcie:</label>
            <input type="text" class="form-control" id="modal_photo" name="modal_photo">


            <label for"modal_character">proponowany charakter technika:</label>
            <select class="form-control" id="modal_character" name="modal_character">
                @foreach ($characters as $row_one)
                <option value="{{$row_one->id}}">{{$row_one->character_short}}</option>
                @endforeach
            </select>

            <label for"modal_status">status (czy pokazywać w wykazie sal):</label>
            <input type="checkbox" class="form-control" id="modal_status" name="modal_status">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">[ Anuluj ]</button>
        <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
        <input type="hidden" id="idid" name="id">
        {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>
</div>


<script>


function showMyModalForm(id, center_id, character_id) {
    if (id > 0)
    {

        $('#modal_number').val(document.getElementById('number'+id).innerHTML);
        $('#modal_name').val(document.getElementById('name'+id).innerHTML);
        $('#modal_description').val(document.getElementById('descript'+id).innerHTML);
        $('#modal_xp_code').val(document.getElementById('xp'+id).innerHTML);
        $('#modal_type').val(document.getElementById('type'+id).innerHTML);
        
        $('#modal_center').val(center_id);
        $('#modal_photo').val(document.getElementById('photo'+id).innerHTML);
        $('#modal_character').val(character_id);

        if (document.getElementById('status'+id).innerHTML == 1)
          $('#modal_status').prop("checked", true );
        else
          $('#modal_status').prop("checked", false );
        $('#idid').val(id);
    }
    else
    {
        $('#modal_name').val('');  
        $('#modal_code').val('');
        $('#modal_center').val('');
        $('#modal_center').val(center_id);
        $('#modal_character').prop("checked", false );
        $('#modal_status').prop("checked", true );
        $('#idid').val(id);
    }

    $('#exampleModal').modal('show');
}

</script>



@endsection
