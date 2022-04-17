<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj grupami stuenckimi")

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

<h1>Zarządzenie grupami studenckimi</h1>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">nazwa</th>
          <th scope="col" data-sortable="true">kod</th>
          <th scope="col">skrót</th>
          <th scope="col">prop. char.</th>
          <th scope="col">status</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($student_groups as $one_row)
    <tr>
        <td>
        {{$one_row->id}}
        </td>
        <td>
        <span id="name{{$one_row->id}}">{{$one_row->student_group_name}}</span>
        </td>
        <td>
        <span id="code{{$one_row->id}}">{{$one_row->student_group_code}}</span>
        </td>
        <td>
        <span id="center{{$one_row->id}}">{{$one_row->center_short}}</span>
        </td>
        <td>
        <span id="character{{$one_row->id}}">{{$one_row->write_technician_character_default}}</span>
        </td>
        <td>
        <span id="status{{$one_row->id}}">{{$one_row->student_group_status}}</span>
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('{{$one_row->id}}','{{$one_row->center_id}}')">Edycja</span>
        </td>
    </tr>
    @endforeach
    <tr>
        <td>
        
        </td>
        <td>
        
        </td>
        <td>
        
        </td>
        <td>
        
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('0','0')">Nowy</span>
        </td>
    </tr>
    </tbody>
</table>



<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja tematu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_student_group') }}" method="post">
    
        <div class="form-group">
            <label for"modal_name">nazwa:</label>
            <input type="text" class="form-control" id="modal_name" name="modal_name">

            <label for"modal_code">kod:</label>
            <input type="text" class="form-control" id="modal_code" name="modal_code">

            <label for"modal_center">kierunek:</label>
            <select class="form-control" id="modal_center" name="modal_center">
                @foreach ($centers as $center_one)
                <option value="{{$center_one->id}}">{{$center_one->center_name}}</option>
                @endforeach
            </select>

            <label for"modal_character">proponuj charakter pracy technika przy imporcie:</label>
            <input type="checkbox" class="form-control" id="modal_character" name="modal_character">

            <label for"modal_status">status (aktywny):</label>
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


function showMyModalForm(id, center_id) {
    if (id > 0)
    {

        $('#modal_name').val(document.getElementById('name'+id).innerHTML);
        $('#modal_code').val(document.getElementById('code'+id).innerHTML);
        $('#modal_center').val(center_id);
        if (document.getElementById('character'+id).innerHTML == 1)
          $('#modal_character').prop("checked", true );
        else
          $('#modal_character').prop("checked", false );
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