<?php
if (!Auth::user()->hasRole('Operator Kadr'))
        return view('error',['head'=>'błąd wywołania widoku Libraries WorkMonth','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Kadr']);
?>

@extends('layouts.app')

@section('title', " Zarządzaj czasem pracy: miesiące")

@section('content')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>


            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {!! $message !!}

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

<h1>Zarządzenie miesięcznym czasem pracy</h1>


<form action="{{ route('libraries.workmonths') }}" method="get">
    <div class="row">
        <div class="col-sm-3">
            <label for"month_selected">wybór miesiąca:</label> 
                <select class="form-control col-sm-2" id="month_selected" name="month_selected">
                @foreach ($month_list as $month_one)             
                    <option value="{{$month_one->work_month}}"<?php if ($month_one->work_month==$filtr['month_selected']) echo ' selected="selected"'; ?>>{{$month_one->work_month}}</option>
                @endforeach
                </select>
        </div>
        <div class="col-sm-1">
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
        </div>
    </div>
</form>


@if ($WorkMonths->count()>0)
<table id="table" data-toggle="table" data-search="true">
    <thead>
      <tr>
          <th scope="col">id</th>
          <th scope="col" data-sortable="true">miesiąc</th>
          <th scope="col" data-sortable="true">technik</th>
          <th scope="col">godz.do przepracowania</th>
          <th scope="col" data-sortable="true">godz. przepracowane</th>
          <th scope="col">przeliczone</th>
          <th scope="col">akcja</th>
      <tr>
    </thead>

    <tbody>
    @foreach ($WorkMonths as $row_one)
    
    <tr>
    <td>
        {{$row_one->id}}
        </td>
        <td>
        {{$row_one->work_month}}
        </td>
        <td>
        <span id="us{{$row_one->id}}">{{$row_one->owner()->name}}</span>
        </td>
        <td>
        <span id="hr{{$row_one->id}}">{{$row_one->hours_to_work}}</span>
        </td>
        <td>
        {{$row_one->hours_worked}}
        </td>
        <td>
        {{$row_one->calculated}}
        </td>
        <td>
        <span onClick="javascript:showMyModalForm('{{$row_one->id}}')">Edycja</span>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif


<form action="{{ route('libraries.save_workmonth') }}" method="post">
    <div class="row">
        <div class="col-sm-4">
            <label for"gen_value">generuj dla miesiąca {{$filtr['month_selected']}}:</label> 
            <input type="text" class="form-control" id="gen_value" name="gen_value">
        </div>
        <div class="col-sm-2">
            <br>
            <input type="hidden" name="action" value="generate">
            <input type="hidden" name="month_selected" value="{{$filtr['month_selected']}}">
            {{ csrf_field() }}
            <input class="btn btn-primary btn-sm" type="submit" value="generuj">
        </div>
    </div>
</form>


<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja czasu pracy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_workmonth') }}" method="post">
    
        <div class="form-group">
            <label for"modal_us">technik:</label>
            <input type="text" disabled class="form-control" id="modal_us" name="modal_us">

            <label for"modal_hr">ilość godzin:</label>
            <input type="text" class="form-control" id="modal_hr" name="modal_hr">
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
       $('#modal_us').val(document.getElementById('us'+id).innerHTML);
       $('#modal_hr').val(document.getElementById('hr'+id).innerHTML);
       $('#idid').val(id);
       $('#exampleModal').modal('show');
    }
}

</script>





@endsection