@extends('layouts.app')

<?php include(app_path().'/include/view_common.php'); ?>

@section('content')



<h2>dodawanie nowej rezerwacji sali</h2>


    <form method="post" action="{{ route('simmeds.store') }}">

<div class='col-sm-6'>
<div class='col-sm-6'>
  <div class="form-group">
  <label>Data i godzina początku</label>
    <div class='input-group' id="start_time">
      <input type='text' name="date_begin" class="form-control" />
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-calendar"></span>
      </span>
    </div>
    @if ($errors->has('date_begin'))
    <div class="error">
        {{ $errors->first('date_begin') }}
    </div>
    @endif
  </div>
</div>

<div class='col-sm-6'>
  <div class="form-group">
  <label>Godzina końca</label>
    <div class='input-group' id="end_time">
      <input type='text' name="date_end" class="form-control" id="end_time_input"/>
      <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
      </span>
    </div>
    @if ($errors->has('date_end'))
    <div class="error">
        {{ $errors->first('date_end') }}
    </div>
    @endif
  </div>
</div>
</div>


<div class='col-sm-6'>
<div class='col-sm-12'>
    <div class="form-group">
        <label>Sala</label>
        <div class='input-group'>
        <select type="text" class="form-control" name="room_id" id="room_id">
            <?php echo make_option_list('list_type', $simmed->get_rooms(), $simmed->room_id); ?>
        </select>
        </div>
    </div>
</div>

</div>

<div class='col-sm-6'>
<div class='col-sm-6'>
    <div class="form-group">
        <label>Prowadzący</label>
        <select type="text" class="form-control" name="leader_id" id="leader_id">
            <?php echo make_option_list('list_type', $simmed->get_leaders(), $simmed->leader_id); ?>
        </select>
    </div>
</div>

<div class='col-sm-6'>
    <div class="form-group">
        <label>Technik</label>
        <select type="text" class="form-control" name="technician_id" id="technician_id">
            <?php echo make_option_list('list_type', $simmed->get_technicians(), $simmed->technician_id); ?>
        </select>
    </div>
</div>
</div>

{{ csrf_field() }}

<input type="hidden" name="subaction" value="main">
<input type="submit" name="send" value="Zapisz" class="form-control btn btn-dark btn-block">
</form>




<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/moment.min.js"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<script>
$('#end_time_input').prop('disabled', true);
$('#start_time').datetimepicker({
  daysOfWeekDisabled: [0, 6],
  enabledHours: [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
  format: 'YYYY-MM-DD HH:mm',
//  firstDay: 1,
  useCurrent: false,
});

$('#end_time').datetimepicker({
  format: 'HH:mm',
  daysOfWeekDisabled: [0, 6],
  enabledHours: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
  useCurrent: false,
});

$("#start_time").on("dp.change", function (e) {
  $('#end_time_input').prop('disabled', false);
  if( e.date ){
    $('#end_time').data("DateTimePicker").date(e.date.add(1, 'h'));
  }
  
  $('#end_time').data("DateTimePicker").minDate(e.date);
});

</script>

<hr>


@endsection