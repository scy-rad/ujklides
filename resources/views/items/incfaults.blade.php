<?php 
if (isset($fault_id))
  $fault=App\Fault::all()->where('id',$fault_id)->first(); 
  ?>

@if (($item->open_faults->count()>0) && (($fault_option=='showall') || ($fault_option=='create')))
<h2>aktywne złoszenia</h2>
<ol>
@foreach ($item->open_faults as $fault_one)
    <li>
    {{$fault_one->start_date}}: <strong>{{$fault_one->fault_title}}</strong>
        [<a href="{{ route('fault.show', $fault_one->id) }}">pokaż</a>]
    </li>
@endforeach
</ol>
@endif




@switch ($fault_option)


@case ('show')

<hr>
<div class="row">
  <div class="col-sm-12">
  
  <h4>szczegóły zgłoszenia [{{$fault->id}}]  </h4>
  
  </div>
</div>
<div class="row bg-primary">
  <div class="col-sm-3">
    zgłaszający:<br>
   {{$fault->notifier()->full_name()}}
  </div>
  <div class="col-sm-2">
    data zgłoszenia:<br>
   {{$fault->start_date}}
  </div>
  <div class="col-sm-2">
    @if (($fault->fault_status!=100) && (Auth::user()->hasRole('magazynier')))
      <a href="{{ route('fault.edit', $fault->id) }}"><span class="bg-info glyphicon glyphicon-edit glyphiconbig pull-right"></span></a>
    @endif
    status:<br>
   {{$fault->fault_status}}
  </div>
    @if ($fault->fault_status==100)
      <div class="col-sm-3">
        zamykający:<br>
      {{$fault->notifier()->full_name()}}
      </div>
      <div class="col-sm-2">
        data zamknięcia:<br>
      {{$fault->close_date}}
      </div>
    @endif
</div>
<div class="row bg-info">
  <div class="col-sm-12">
    <h3>{{$fault->fault_title}}</h3>
    {!!$fault->notification_description!!}
    <br><br>
  </div>
    
    <div class="col-sm-12  bg-primary">
      <h3>opis działań:</h3>
      {!!$fault->repair_description!!}
      <br><br>
    </div>
    
  
</div>

  <hr>
@break





@case ('edit')
@case ('close')

<hr>

<!--form method="post" action="{{ route('item.update', $item->id) }}"-->
<form method="post" action="{{ route('fault.update') }}">
    <input type="hidden" name="_method" value="PUT">
    {{ csrf_field() }}
      <h2>edycja zgłoszenia [{{$fault->id}}]</h2>
      <fieldset>
        <label for="fault_title">tytuł:</label>
          <input type="text" class="form-control" id="fault_title" name="fault_title" value="{{$fault->fault_title}}">
        <label for="notification_description">opis zgłoszenia:</label>
          <textarea class="form-control" id="notification_description" name="notification_description" rows="4" cols="150">{!!$fault->notification_description!!}</textarea>

        <label for="repair_description">opis podjętych działań:</label>
          <textarea class="form-control" id="repair_description" name="repair_description" rows="4" cols="150">{!!$fault->repair_description!!}</textarea>

        <label for="action">działanie:</label>
          <select class="form-control form-select" name="action">
            <option value="edit">edytuj</option>
            <option value="close">zamknij</option>
          </select>

      </fieldset>


  <div class="modal-footer">
    <input type="hidden" name="fault_id" value="{{$fault_id}}">
    <a href="{{ url()->previous() }}">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
      </a>
    <button type="submit" class="btn btn-primary">zapisz</button>
  </div>
  </form>
  <hr>
@break


@endswitch


@if (($item->close_faults->count()>0) && ($fault_option=='showall'))
<h2>złoszenia zamkniete </h2>
<ol>
@foreach ($item->close_faults as $fault_one)
    <li>
        {{$fault_one->fault_title}}
        [<a href="{{ route('fault.show', $fault_one->id) }}">pokaż</a>]
    </li>
@endforeach
</ol>
@endif