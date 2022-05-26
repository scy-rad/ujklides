<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>wydruk z systemu UJKlides</title>
  </head>
  <style>
  h1, h2, h3 {text-align: center}
  h3 {color: green; border-top-style: solid; border-top-color: currentcolor}
  body { font-family: Verdana, Arial, Helvetica, sans-serif }
  table {border-collapse: collapse;}
  th {border: 1px solid black; background: #ff6}
  td {border: 1px solid black}
  .aright {text-align: right}
  .acenter {text-align: center}
  .smalltxt {font-size: 0.5rem}
  .mediumtxt {font-size: 0.75rem}
  .aitalic {font-style: italic}
  .nowork {background: #fcc}
  footer {margin-bottom: 100px; font-size: 0.3rem; text-align: right}
  @media print {
  footer {page-break-after: always;}
}
  </style>
  <body>
  <?php function m2hcard($timeX) { return floor($timeX/60).':'.str_pad($timeX%60, 2, '0', STR_PAD_LEFT); } ?>

<h2>INFORMACJA O ZMIANACH CZASU PRACY<br>
{{$user->full_name()}}</h2>

<h3>zmiana harmonogramu pracy {{$total['month_name']}} {{$total['year']}}</h3>

<div style="width: 90%; margin: auto">
    <table style="width:100%; border: 2px; text-align: center">
        <thead>
            <tr>
            <th scope="col">{{$total['month_name']}} {{$total['year']}}</th>
            <th scope="col">&nbsp;</th>
            <th scope="col">godziny pracy</th>
            <th scope="col">zmiana na</th>
            </tr>
        </thead>
    @foreach ($tabelka as $row_one)
        @if ( ($row_one['changes']['hr_time_begin'] != $row_one['hr_wt']['hr_time_begin']) || ($row_one['changes']['hr_time_end'] != $row_one['hr_wt']['hr_time_end']) )
                    
    <tr>
        <td>
            {{substr($row_one['date'],8,2)}}
        </td>
        <td>
            {{$row_one['day_name_short']}}
       </td>
        <td>
            {{$row_one['hr_wt']['hr_time_begin']}} - {{$row_one['hr_wt']['hr_time_end']}}
       </td>
        <td>
            <srong>{{$row_one['changes']['hr_time_begin']}} - {{$row_one['changes']['hr_time_end']}}</strong>
        </td>
    </tr>
    @endif
    @endforeach
    </table>
</div>

<h3>zmiany zleceń pracy w godzinach nadliczbowych</h3>

<ul>
     @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==1)
            @if ($row_one['hr_wt']['over_txt'] != $row_one['changes']['over_txt'])
                @if ($row_one['changes']['over_under']==1)
                    <li> {{$row_one['date']}} zmiana z <strong>{{$row_one['hr_wt']['over_txt']}}</strong> na <strong>{{$row_one['changes']['over_txt']}}</strong> 
                    </li>
                @else
                    <li> {{$row_one['date']}} usunięto godziny nadliczbowe: <strong>{{$row_one['hr_wt']['over_txt']}}</strong>
                    </li>
                @endif
            @else
            <li> <s>{{$row_one['date']}} bez zmian: {{$row_one['hr_wt']['over_txt']}}</s></li>
            @endif
        @else
            @if ($row_one['changes']['over_under']==1)
            <li> {{$row_one['date']}} dodano godziny  nadliczbowe <strong>{{$row_one['changes']['over_txt']}}</strong> </li>
            @endif
        @endif
     @endforeach
</ul>
<p>razem było: <strong>{{$total['hrtimes_over']}}</strong> godzin, a po zmianach jest: <strong>{{m2hcard($total['changes_minutes_over'])}}</strong> godzin.</p>


<h3>zmiany udzielenia czasu wolnego w zamian za czas przepracowany w godzinach nadliczbowych</h3>


<ul>
     @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==2)
            @if ($row_one['hr_wt']['under_txt'] != $row_one['changes']['under_txt'])
                @if ($row_one['changes']['over_under']==2)
                    <li> {{$row_one['date']}} zmiana z {{$row_one['hr_wt']['under_txt']}} na {{$row_one['changes']['under_txt']}} </li>
                @else
                    <li> {{$row_one['date']}} usunięto odebranie godzin nadliczbowych: {{$row_one['hr_wt']['under_txt']}} ( {{$row_one['changes']['under_txt']}} ) </li>
                @endif
            @else
            <li> <s>{{$row_one['date']}} bez zmian: {{$row_one['hr_wt']['under_txt']}}</s></li>
            @endif
        @else
            @if ($row_one['changes']['over_under']==2)
            <li> {{$row_one['date']}} dodano odebranie godzin nadliczbowych {{$row_one['changes']['under_txt']}} </li>
            @endif
        @endif
     @endforeach
</ul>
<p>razem było: <strong>{{$total['hrtimes_under']}}</strong> godzin, a po zmianach jest: <strong>{{m2hcard($total['changes_minutes_under'])}}</strong> godzin.</p>


<h3>całkowite podliczenie czasu przepracowanego i wolnego</h3>

<p>
@if ($total['hrminutes_under']>$total['hrminutes_over'])
    razem było: <strong>{{m2hcard($total['hrminutes_under']-$total['hrminutes_over'])}}</strong> godzin udzielonego czasu wolnego 
@endif

@if ($total['hrminutes_under']<$total['hrminutes_over'])
    razem było: <strong>{{m2hcard($total['hrminutes_over']-$total['hrminutes_under'])}}</strong> godzin przepracowanych w godzinach nadliczbowych 
@endif

@if ($total['hrminutes_under']==$total['hrminutes_over'])
    razem norma czasu pracy została zrealizowana 
@endif

<br>
a po zmianach:
<br>

@if ($total['changes_minutes_under']>$total['changes_minutes_over'])
    razem jest: <strong>{{m2hcard($total['changes_minutes_under']-$total['changes_minutes_over'])}}</strong> godzin udzielonego czasu wolnego 
@endif

@if ($total['changes_minutes_under']<$total['changes_minutes_over'])
    razem jest: <strong>{{m2hcard($total['changes_minutes_over']-$total['changes_minutes_under'])}}</strong> godzin przepracowanych w godzinach nadliczbowych 
@endif

@if ($total['changes_minutes_under']==$total['changes_minutes_over'])
    razem norma czasu pracy została zrealizowana 
@endif
</p>


</body>
</html>
