@extends('layouts.app')


@section('content')


<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" />

<!-- If you use the default popups, use this. -->
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />

<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>


<div id="calendar" style="height: 800px;"></div>
<script>
    var Calendar = tui.Calendar;

//    var Calendar = require('tui-calendar'); /* CommonJS */
//require("{{URL::asset('js/tui-calendar/dist/tui-calendar.css')}}");

// If you use the default popups, use this.
//require("{{URL::asset('js/tui-date-picker/dist/tui-date-picker.css')}}");
//require("{{URL::asset('js/tui-time-picker/dist/tui-time-picker.css')}}");


var calendar = new Calendar('#calendar', {
  usageStatistics: false,
  defaultView: 'month',
  taskView: true,
  template: {
    monthDayname: function(dayname) {
      return '<span class="calendar-week-dayname-name">' + dayname.label + '</span>';
    }
  }
});


</script>


@endsection
