@extends('layouts.app')

@section('title', "symulacje dnia $sch_date")
@section('content')


    <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/jquery.schedule/dist/css/style.css')}}" />

    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
        #logs{
            border: solid 1px #bbb;
            padding: 16px;
            background: #eee;
        }
        #logs .table{
            margin-bottom: 0;
        }
        #logs .table td,
        #logs .table th{
            border: none;
        }
        #schedule .sc_bar_insert{
            background-color: #ff0000;
        }
        #schedule .sc_bar_move{
            background-color: #ffaa00;
        }

        #schedule .sc_bar_no_leader{
            background-color: #ff4500;
        }
        #schedule .sc_bar_no_technician{
            background-color: #6a5acd;
        }
        #schedule .sc_bar_team{
            background-color: #1e90ff;
        }
        #schedule .example2{
            background-color: #3eb698;
        }
        #schedule .example3{
            color: #2c0000;
            font-weight: bold;
            background-color: #c7ae50;
        }
        #schedule .sc_bar.sc_bar_photo .head,
        #schedule .sc_bar.sc_bar_photo .text
        #schedule .sc_bar.sc_bar_photo .subtxt{
            padding-left: 60px;
        }
        
        #schedule .sc_bar.sc_bar_photo .photo{
            position: absolute;
            left: 10px;
            top: 10px;
            width: 38px;
        }
        #schedule .sc_bar.sc_bar_photo .photo img{
            max-width: 100%;
        }
    </style>

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
<div class="col-md-1">
        <a href="{{route('simmeds.scheduler', date('Y-m-d', strtotime($sch_date . ' -'.$bef.' day')))}}"><span class="glyphicon glyphicon-backward"></span></a>
</div>
<div class="col-md-3">
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
        <h5 class="modal-title" id="editModalLabel">zgłoszenie usterki</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
        <h2>nowe złoszenie </h2>

        <form method="post" action="{{ route('fault.store') }}">
            {{ csrf_field() }}

            <fieldset>
                <label for="instruktor">instruktor:</label>
                <input type="text" id="modal_leader" name="leader" value=""><br><br>
                <label for="technik">trchnik:</label>
                <input type="text" id="modal_technician" name="technician" value=""><br><br>
                <label for="start">start:</label>
                <input type="text" id="modal_start" name="start" value=""><br><br>
                <label for="end">end:</label>
                <input type="text" id="modal_end" name="end" value=""><br><br>
                
            </fieldset>
      </div> <!-- modal-body -->

      <div class="modal-footer">
          <input type="hidden" id="simmed_id" name="simmed_id" value="">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
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
        var isDraggable = true;
        var isResizable = true;
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
            draggable: false,   //dodane żeby nie dało się nic zmieniać
            resizable: false,   //dodane żeby nie dało się nic zmieniać
            rows : {
                {!!$rows_scheduler!!}
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
