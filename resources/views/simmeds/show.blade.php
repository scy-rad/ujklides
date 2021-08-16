@extends('layouts.app')
<?php include(app_path().'/include/view_common.php'); ?>

<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" />

<!-- If you use the default popups, use this. -->
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />
<link href="{{ asset('css/_cards.css') }}" rel="stylesheet">

@section('title', 'scenariusze '.$simmed->simmed_date.': '.$simmed->student_subject()->student_subject_name)

@section('content')


<?php //dump($simmed->simmed_date); ?>

<?php

$Scen_Table='<ul>';

foreach ($simmed->scenarios as $scenlist)
{
  $Scen_Table.='<li><a href="'.asset('scenario/'.$scenlist->id).'">';
  $Scen_Table.=$scenlist->scenario_name;
  $Scen_Table.='</a></li>';
}

$Scen_Table.='</ul>';
?>

<div class="row">
    <div class="col-lg-2 mb-2">
    </div>
{{ kafelek(2, 'sala', $simmed->room()->room_number,NULL) }}
{{ kafelek(4, 'instruktor', $simmed->name_of_leader(),NULL) }}
{{ kafelek(4, 'technik', $simmed->name_of_technician(),NULL) }}
</div>

<div class="row">
    {{ kafelek(2, 'data symulacji', $simmed->simmed_date,NULL) }}
    {{ kafelek(2, 'godziny', substr($simmed->simmed_time_begin,0,5).' - '.substr($simmed->simmed_time_end,0,5),NULL) }}
        
    {{ kafelek(4, 'dział', $simmed->student_subject()->student_subject_name,NULL) }}
    {{ kafelek(2, 'grupa studencka', $simmed->name_of_student_group(),NULL) }}
    {{ kafelek(2, 'podgrupa ', $simmed->name_of_student_subgroup(),NULL) }}
</div>
<div class="row">
    {{ kafelek(12, 'scenariusze', $Scen_Table,NULL) }}
</div>

    
<p>{!! $simmed->opis !!}</p>
<div class="float-right"><a class="btn btn-info" href="{{route('simmeds.edit', $simmed)}}">Edytuj</a></div>

<div id="calendar" style="height: 800px;"></div>
@endsection
<script>
var Calendar = tui.Calendar;
var Calendar = require('tui-calendar'); /* CommonJS */
require("tui-calendar/dist/tui-calendar.css");

// If you use the default popups, use this.
require("tui-date-picker/dist/tui-date-picker.css");
require("tui-time-picker/dist/tui-time-picker.css");
import Calendar from 'tui-calendar'; /* ES6 */
import "tui-calendar/dist/tui-calendar.css";

// If you use the default popups, use this.
import 'tui-date-picker/dist/tui-date-picker.css';
import 'tui-time-picker/dist/tui-time-picker.css';
</script>
<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>