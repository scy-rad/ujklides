<?php
if (!Auth::user()->hasRole('Operator Symulacji'))
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds index','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj tytułami naukowymi")

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

<h1>Zarządzenie tytułami naukowymi</h1>

<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">skrót</th>
          <th scope="col" data-sortable="true">sortowanie</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($user_titles as $usertitle)
    <tr>
        <td>
        {{$usertitle->id}}
        </td>
        <td>
        <span id="SH{{$usertitle->id}}">{{$usertitle->user_title_short}}</span>
        </td>
        <td>
        <span id="SO{{$usertitle->id}}">{{$usertitle->user_title_sort}}</span>
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('{{$usertitle->id}}')">Edycja</span>
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
        <form action="{{ route('libraries.save_user_title') }}" method="post">
    
        <div class="form-group">
            <label for"modal_short">skrót:</label>
            <input type="text" class="form-control" id="modal_short" name="modal_short">

            <label for"modal_sort">sortowanie:</label>
            <input type="text" class="form-control" id="modal_sort" name="modal_sort">

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
        $('#modal_sort').val(document.getElementById('SO'+id).innerHTML);
        $('#modal_short').val(document.getElementById('SH'+id).innerHTML);
        $('#idid').val(id);
    }
    else
    {
        $('#modal_sort').val('');  
        $('#modal_short').val('');
        $('#idid').val(id);
    }

    $('#exampleModal').modal('show');
}

</script>



@endsection