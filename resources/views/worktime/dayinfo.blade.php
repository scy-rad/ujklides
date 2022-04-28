@extends('layouts.app')
<link href="{{ asset('css/device.css') }}" rel="stylesheet">
<?php $date=$dateT['date']; ?>
@section('title', "Czas pracy ".$user->lastname." "." - technik " )

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

<h4>{{$user->full_name()}}</h4>

<div class="btn-group">
  <a href="{{route('worktime.day_data', [ date('Y-m-d',strtotime("$date -1 day")), $user->id ])}}" class="btn btn-primary btn-lg">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
      <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
    </svg>

  </a>

  <div class="btn btn-info btn-lg">
  <a href="{{ route('worktime.month',['technician' => $user->id, 'month' => substr($date,0,7) ] ) }}">
  {{$date}}<br>
  {{$dateT['dayname']}}
  </a>
  </div>
  <a href="{{route('worktime.day_data', [ date('Y-m-d',strtotime("$date +1 day")), $user->id ])}}" class="btn btn-primary btn-lg">
  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-right-square-fill" viewBox="0 0 16 16">
    <path d="M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v12zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1z"/>
  </svg>
  </a>
</div>

<h2>zaplanowane zajęcia w CSM:         <a href="/scheduler/{{$date}}">
{{$date}}
            <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
            </a>
</h2>
<div>
@foreach ($simmeds as $row_one)
    <div class="row">
      <div class="col-sm-1">
        <a href="/scheduler/{{$row_one->simmed_date}}">
            {{ $row_one->simmed_date }}
            <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
            </a>
            <br>
        <?php 
        $dni_tygodnia = array( 'Ni', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'Sb' );
        echo $dni_tygodnia[ date('w',strtotime($row_one->simmed_date)) ];
        ?>
          <a href="{{route('simmeds.show', [$row_one->id, 0])}}"> pokaż
              <span class="glyphicon glyphicon-list-alt text-success" aria-hidden="true"></span>
          </a>
      </div>
      <div class="col-sm-1">
          {{$row_one->start}} - {{$row_one->end}}
      </div>
      <div class="col-sm-1">
          <strong>{{$row_one->character_short}}</strong>
      </div>
      <div class="col-sm-1">
          <strong>{{$row_one->room_number}}</strong>
      </div>
      <div class="col-sm-2">
          {{$row_one->text}}
      </div>
      <div class="col-sm-2">
          [{{$row_one->student_group_code}}: {{$row_one->student_subject_name}}]
      </div>
    </div>
@endforeach
</div>

<h2>rozpisany czas pracy:</h2>
<div>
@foreach ($work_times as $row_one)
    <div class="row" style="padding-bottom: 10px;">
        <div class="btn btn-primary btn-sm col-sm-2" onClick="javascript:showMyModalForm('{{$row_one->id}}', '{{$row_one->work_time_types_id}}')">
        <span id="begin{{$row_one->id}}">{{$row_one->time_begin}}</span> - <span id="end{{$row_one->id}}">{{$row_one->time_end}}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
</svg>
        </div>
        <div class="col-sm-2">
            {{$row_one->short_name}}
        </div>
        <div class="col-sm-6">
            <span id="descript{{$row_one->id}}">{{$row_one->description}}</span>
        </div>
        <div class="col-sm-1"></div>    
    </div>
@endforeach
<div class="row" style="padding-bottom: 10px;">
<div class="btn btn-primary btn-sm col-sm-2" onClick="javascript:showMyModalForm('0', '0')">
        dodaj nowy <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
</svg>
        </div>
</div>
</div>

<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">{{$user->firstname}} {{$user->lastname}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('worktime.save_data') }}" method="post">
    
        <div class="form-group">
            <label for"modal_start">od:</label>
            <input type="time" class="form-control" id="modal_start" name="modal_start" step="300">


            <label for"modal_end">do:</label>
            <input type="time" class="form-control" id="modal_end" name="modal_end" step="300">
            <label for"modal_work_type_id">rodzaj:</label>
            <select class="form-control" id="work_time_types_id" name="work_time_types_id">
                @foreach ($work_time_types as $type_one)
                <option value="{{$type_one->id}}">{{$type_one->short_name}}</option>
                @endforeach
            </select>
            <label for"modal_description">opis:</label>
            <input type="text" class="form-control" id="modal_description" name="modal_description">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">[ Anuluj ]</button>
        <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
        <input type="hidden" id="idid" name="id">
        <input type="hidden" name="date" value="{{$date}}">
        <input type="hidden" name="user_id" value="{{$user->id}}">
        {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>
</div>

<script>

// $('#exampleModal').on('show.bs.modal', function (event) {
//   var button = $(event.relatedTarget) // Button that triggered the modal
//   var recipient = 'recipient SD'
//   var modal = $(this)
//   modal.find('.modal-title').text('New message to ' + recipient)
//   modal.find('.modal-body input').val(recipient)
// })


function showMyModalForm(id, worktype_id) {
if (id > 0)
{
  $('#modal_start').val(document.getElementById('begin'+id).innerHTML);
  $('#modal_end').val(document.getElementById('end'+id).innerHTML);
  $('#modal_work_type_id').val(worktype_id);
  $('#modal_description').val(document.getElementById('descript'+id).innerHTML);
  $('#idid').val(id);
}
else
{
  worktype_id=1;
  $('#modal_start').val('07:30');  
  $('#modal_end').val('15:30');
  $('#modal_work_type_id').val(worktype_id);
  $('#modal_description').val('');
  $('#idid').val(id);
}

//$('#myModalTitle').html(document.getElementById('end'+id).innerHTML);

const $select = document.querySelector('#work_time_types_id');
  $select.value = worktype_id;

$('#exampleModal').modal('show');
}

</script>

@endsection


