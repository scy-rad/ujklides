<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj symulacjami: tematy")

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

<h1>Zarządzenie tematami symulacji</h1>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">nazwa PL</th>
          <th scope="col" data-sortable="true">nazwa EN</th>
          <th scope="col">status</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($subjects as $subject)
    <tr>
        <td>
        {{$subject->id}}
        </td>
        <td>
        <span id="PL{{$subject->id}}">{{$subject->student_subject_name}}</span>
        </td>
        <td>
        <span id="EN{{$subject->id}}">{{$subject->student_subject_name_en}}</span>
        </td>
        <td>
        <span id="ST{{$subject->id}}">{{$subject->student_subject_status}}</span>
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('{{$subject->id}}')">Edycja</span>
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
        <span onClick="javascript:showMyModalForm('0')">Nowy</span>
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
        <form action="{{ route('libraries.save_subject') }}" method="post">
    
        <div class="form-group">
            <label for"modal_pl">temat PL:</label>
            <input type="text" class="form-control" id="modal_pl" name="modal_pl">

            <label for"modal_en">temat EN:</label>
            <input type="text" class="form-control" id="modal_en" name="modal_en">

            <label for"modal_st">status:</label>
            <input type="text" class="form-control" id="modal_st" name="modal_st">

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


function showMyModalForm(id) {
    if (id > 0)
    {
        $('#modal_pl').val(document.getElementById('PL'+id).innerHTML);
        $('#modal_en').val(document.getElementById('EN'+id).innerHTML);
        $('#modal_st').val(document.getElementById('ST'+id).innerHTML);
        $('#idid').val(id);
    }
    else
    {
        $('#modal_pl').val('');  
        $('#modal_en').val('');
        $('#modal_st').val('');
        $('#idid').val(id);
    }

    $('#exampleModal').modal('show');
}

</script>





@endsection