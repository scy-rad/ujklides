@extends('layouts.app')

@section('title', "symulacje dnia $sch_date")
@section('content')


    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/jquery.schedule/dist/css/style.css')}}" />



<?php
$dayofweek[1]='poniedziałek';
$dayofweek[2]='wtorek';
$dayofweek[3]='środa';
$dayofweek[4]='czwartek';
$dayofweek[5]='piątek';
$dayofweek[6]='sobota';
$dayofweek[7]='niedziela';
$bef=1;
$aft=1;
if (date('N', strtotime($sch_date))==1) $bef=3;
if (date('N', strtotime($sch_date))==5) $aft=3;
?>

<h1>
<div class="row">
<div class="col-md-2 text-right">
        <a href="{{route('simmeds.scheduler', date('Y-m-d', strtotime($sch_date . ' -'.$bef.' day')))}}"><span class="glyphicon glyphicon-backward"></span></a>
</div>
<div class="col-md-5 text-center">
        {{$sch_date}} {{ $dayofweek[date('N', strtotime($sch_date))] }}
</div>
<div class="col-md-1">
        <a href="{{route('simmeds.scheduler', date('Y-m-d', strtotime($sch_date . ' +'.$aft.' day')))}}"><span class="glyphicon glyphicon-forward"></span></a></h1>
</div>
</div>
</h1>

<div class="containerX">
    <div style="padding: 0 0 10px;">
        <div id="schedule"></div>


@if (Auth::user()->hasRole('Administrator')) 
        <div class="row">
            <div class="col-md-8">
                <h3>Log - widoczny tylko dla administratora w widoku scheduler (simmeds)</h3>
            </div>
            <div class="col-md-4 text-right">
                <a class="btn btn-default" style="margin-top: 16px;" id="clear-logs">clear</a>
            </div>
        </div>
        <div style="padding: 12px 0 0;">
            <div id="logs" class="table-responsive"></div>
        </div>
@endif
    </div>

</div>



<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="editModalLabel">szczegóły zadania...</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
        <form method="post" action="{{ route('fault.store') }}">
            {{ csrf_field() }}

            <fieldset>
                <span id="span_start"></span> - <span id="span_end"></span><br>
                <span id="span_character"></span><br><br>
                <span id="span_text"></span><br>
                <span id="span_simdescript"></span>
            </fieldset>
      </div> <!-- modal-body -->

      <div class="modal-footer">
          <input type="hidden" id="simmed_id" name="simmed_id" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">zamknij</button>
            <!--button type="submit" class="btn btn-primary">zapisz</button-->
      </div>    <!-- modal-footer -->
        </form>
    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->




<span onclick="alert('Kliknięto link strony głównej!')"> alert </span>
<span onclick="openModal(1);"> kliknij </span>

<script>
function openModal(num)
{
    $('#editModal').modal('show');
}

</script>

<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" type="text/javascript" language="javascript"></script>

<script type="text/javascript" src="{{ URL::asset('js/jquery.schedule/dist/js/jq.schedule.js')}}"></script>
<script type="text/javascript">
    function addLog(type, message){
        var $log = $('<tr />');
        $log.append($('<th />').text(type));
        $log.append($('<td />').text(message ? JSON.stringify(message) : ''));
        @if (Auth::user()->hasRole('Administrator')) 
        $("#logs table").prepend($log);
        @endif
    }
    $(function(){
        $("#logs").append('<table class="table">');
        var isDraggable = false;
        var isResizable = false;
        var $sc = $("#schedule").timeSchedule({
            startTime: "07:00", // schedule start time(HH:ii)
            endTime: "22:00",   // schedule end time(HH:ii)
            widthTime: 60 * 15,  // cell timestamp example 10 minutes
            //timeLineY: 80,       // height(px)
            verticalScrollbar: 20,   // scrollbar (px)
            timeLineBorder: 2,   // border(top and bottom)
            bundleMoveWidth: 6,  // width to move all schedules to the right of the clicked time line cell
            draggable: isDraggable,
            resizable: isResizable,
            resizableLeft: true,
            rows : {
                {!!$rows_scheduler!!}
            },
            onClick: function(node, data){
                addLog('onClick!', data);
                addLog('onClick!', data['data']['id']);
                $('#editModal').modal('show');
                //alert(JSON.stringify(data, null, 4));
                document.getElementById('span_start').innerHTML = data['start'];
                document.getElementById('span_end').innerHTML = data['end'];
                document.getElementById('span_character').innerHTML = data['class'];
                document.getElementById('span_text').innerHTML = data['text'];
                document.getElementById('span_simdescript').innerHTML = data['simdescript'];
                
              //  ui-resizable
            },
        /*
            onChange: function(node, data){
                addLog('onChange', data);
                addLog('onChange', data['start']);
                
                
                if (data['data']['start_sent'] == null)
                    {
                    }
                else
                    {
                    if ((data['start'] == data['data']['start_sent']) && (data['end'] == data['data']['end_sent']))
                        {
                        node.removeClass("sc_bar_move");
                        node.addClass('sc_bar_team');
                        }
                    else
                        {
                        node.removeClass("sc_bar_team");
                        node.addClass('sc_bar_move');
                        }
                    }

            },
            onInitRow: function(node, data){
                addLog('onInitRow', data);
            },
            onClick: function(node, data){
                addLog('onClick!', data);
                addLog('onClick!', data['data']['id']);
                //$('#editModal').modal('show');
                document.getElementById('modal_start').value = data['start'];
                document.getElementById('modal_end').value = data['end'];
                document.getElementById('modal_leader').value = data['text'];
                document.getElementById('modal_technician').value = data['subtxt'];
                
                ui-resizable
            },
            onAppendRow: function(node, data){
                addLog('onAppendRow', data);
            },
            onAppendSchedule: function(node, data){
                addLog('onAppendSchedule', data);
                if(data.data.class){
                    node.addClass(data.data.class);
                }
                if(data.data.image){
                    var $img = $('<div class="photo"><img></div>');
                    $img.find('img').attr('src', data.data.image);
                    node.prepend($img);
                    node.addClass('sc_bar_photo');
                }
            },
            onScheduleClick: function(node, time, timeline){
                var start = time;
                var end = $(this).timeSchedule('formatTime', $(this).timeSchedule('calcStringTime', time) + 3600);
                $(this).timeSchedule('addSchedule', timeline, {
                    start: start,
                    end: end,
                    text:'Insert Schedule',
                    subtxt:'Ojoj',
                    data:{
                        class: 'sc_bar_insert',
                        sdtest: 'testowywpis'
                    }
                });
                addLog('onScheduleClick', time + ' ' + timeline);
            },
        */
        });
        /*
        $('#event_timelineData').on('click', function(){
            addLog('timelineData', $sc.timeSchedule('timelineData'));
        });
        $('#event_scheduleData').on('click', function(){
            addLog('scheduleData', $sc.timeSchedule('scheduleData'));
        });
        $('#event_resetData').on('click', function(){
            $sc.timeSchedule('resetData');
            addLog('resetData');
        });
        $('#event_resetRowData').on('click', function(){
            $sc.timeSchedule('resetRowData');
            addLog('resetRowData');
        });
        $('#event_setDraggable').on('click', function(){
            isDraggable = !isDraggable;
            $sc.timeSchedule('setDraggable', isDraggable);
            addLog('setDraggable', isDraggable ? 'enable' : 'disable');
        });
        $('#event_setResizable').on('click', function(){
            isResizable = !isResizable;
            $sc.timeSchedule('setResizable', isResizable);
            addLog('setResizable', isResizable ? 'enable' : 'disable');
        });
        */
        $('#clear-logs').on('click', function(){
            $('#logs .table').empty();
        });
    });
</script>

@endsection
