<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>wydruk z systemu UJKlides</title>
  </head>
  <style>
  h1, h2 {text-align: center}
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
  <?php //function m2hcard($timeX) { return floor($timeX/60).':'.str_pad($timeX%60, 2, '0', STR_PAD_LEFT); } ?>

<p class="smalltxt aright">Załącznik nr 8 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<h1>LISTA OBECNOŚCI PRACOWNIKÓW CENTRUM SYMULACJI MEDYCZNYCH</h1> 
<h2>harmonogram pracy {{$total['month_name']}} {{$total['year']}}</h2>
        <!--h3>planowo godzin: {{$total['month_data']->hours_to_work}}</h3-->
<div style="width: 90%; margin: auto">
    <table style="width:100%; border: 2px; text-align: center">
        <thead>
            <tr>
            <th scope="col">{{$total['month_name']}} {{$total['year']}}</th>
            <th scope="col">&nbsp;</th>
            <th scope="col">{{$user->full_name()}}</th>
            <th scope="col">godziny pracy</th>
            <th scope="col">ilość godzin</th>
            </tr>
        </thead>
    @foreach ($tabelka as $row_one)
    @if ( ($row_one['day_week']>5) || (count($row_one['work_types'])==0) )
    <tr class="nowork">
    @else
    <tr>
    @endif
        <td>
            {{substr($row_one['date'],8,2)}}
        </td>
        <td>
            {{$row_one['day_name_short']}}
       </td>
        <td>
        </td>
        <td>
            {{$row_one['hr_wt']['hr_time_begin']}} - {{$row_one['hr_wt']['hr_time_end']}}
       </td>
        <td>
            {{$row_one['hr_wt']['hr_hoursmin']}}
        </td>
    </tr>
    @endforeach
    <tfoot>
            <tr>
            <th colspan="4" style="text-align: right;"><strong>Razem: &nbsp; </strong> </th>
            <th><strong>{{$total['hr_times']}}</strong></th>
            </tr>
        </tfoot>
    </table>
</div>
<div style="width: 100%;">
    <div style="border-top: 1px dotted black; width: 400px; margin-top: 60px; float:right; clear:both; ">
        <p class="mediumtxt acenter aitalic">data, podpis kierownika/bezpośredniego przełożonego</p>
    </div>
</div>
<div style="width: 100%; background: yellow; clear: both">
</div>
<footer>[xD]</footer>
@if ($total['hrminutes_over']>0)
<p class="smalltxt aright">Załącznik nr 4 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<p class="aright">Kielce, dnia .................................</p>

<h1>Zlecenie pracy w godzinach nadliczbowych</h1>

<p>Na podstawie art. 151 § 1 Kodeksu Pracy zlecam pracownikowi <strong>{{$user->full_name()}}</strong> wykonanie pracy w godzinach nadliczbowych w dniach:</p>
<ul>
     @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==1)
            <li> {{ $row_one['date'],8}} w godz. od {{substr($row_one['hr_wt']['o_time_begin'],0,5)}} do {{substr($row_one['hr_wt']['o_time_end'],0,5)}} : ({{$row_one['hr_wt']['o_hoursmin']}})</li> 
        @endif
     @endforeach
</ul>
<p>razem: <strong>{{$total['hrtimes_over']}}</strong> godzin.</b>

<h2>uzasadnienie</h2>
<p>Nadgodziny wynikają ze specyfiki pracy personelu technicznego w Centrum Symulacji Medycznych.</p>
<p>.........................................................................................................................................................<br><br>
.........................................................................................................................................................<br><br>
.........................................................................................................................................................<br><br>
.........................................................................................................................................................<br><br>
.........................................................................................................................................................</p>

<div style="width: 100%;">
    <div style="border-top: 1px dotted black; width: 400px; margin-top: 60px; float:right; clear:both; ">
        <p class="mediumtxt acenter aitalic">data, podpis kierownika/bezpośredniego przełożonego</p>
    </div>
    <div style="width: 400px; margin-top: 10px; float:right; clear:both; ">
        Wyrażam zgodę:
    </div>
    <div style="border-top: 1px dotted black; width: 400px; margin-top: 60px; float:right; clear:both; ">
        <p class="mediumtxt acenter aitalic">data, podpis pracodawcy</p>
    </div>
</div>
<div style="width: 100%; background: yellow; clear: both">
</div>
<footer>[xD]</footer>
@endif

@if ($total['hrminutes_under']>0)
<div style="all_page">

<p class="smalltxt aright">Załącznik nr 6 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<p class="aright">Kielce, dnia .................................</p>

<p><strong>{{$user->full_name()}}</strong><br>
<span class="mediumtxt aitalic">(imię i nazwisko)</span></p`>
<br>
<p><strong>Centrum Symulacji Medycznych</strong><br>
<span class="mediumtxt aitalic">(jednostka organizacyjna)</span></p>
<br>
<br>

<h1>WNIOSEK O UDZIELENIE CZASU WOLNEGO OD PRACY W ZAMIAN ZA CZAS PRZEPRACOWANY W GODZINACH NADLICZBOWYCH:</h1>


<p>Proszę o udzielenie czasu wolnego od pracy w liczbie <strong>{{$total['hrtimes_under']}}</strong> godzin w terminie: </p>

<ul>
     @foreach ($tabelka as $row_one)
     @if ($row_one['hr_wt']['over_under']==2)
        <li> {{ $row_one['date'],8}} w godz. {{$row_one['hr_wt']['under_txt']}} : ({{$row_one['hr_wt']['o_hoursmin']}})</li> 
     @endif
     @endforeach
</ul>     
<?php
    $tekstA='...';
    if  ( ($total['hrminutes_over']>0) && ($total['hrminutes_over']>=$total['hrminutes_under']) )
        $tekstA='w terminie '.date('d-m-Y',strtotime($filtr['month'].'-01')).' &minus; '.date('t-m-Y',strtotime($filtr['month'].'-01'));
      if  ( ($total['hrminutes_over']>0) && ($total['hrminutes_over']<$total['hrminutes_under']) )
        $tekstA='w terminie '.date('d-m-Y',strtotime($filtr['month'].'-01')).' &minus; '.date('t-m-Y',strtotime($filtr['month'].'-01')).' oraz na rzecz godzin przepracowanych w kolejnym miesiącu';
    if  ( ($total['hrminutes_over']==0) && ($total['hrminutes_over']<$total['hrminutes_under']) )
        $tekstA=' w kolejnym miesiącu';
?>
<p>w zamian za czas przepracowany w godzinach nadliczbowych {{$tekstA}}.<br>
Zastępstwo pełnić będą wybrani pracownicy Centrum Symulacji Medycznych.</p>
<hr>
@if ($total['hrminutes_over'] > $total['hrminutes_under'])
    <p>ilość godzin w miesiącu <strong>{{$total['month_name']}} {{$total['year']}}</strong> przepracowanych powyżej normy: <strong>{{floor(($total['hrminutes_over']-$total['hrminutes_under'])/60).':'.str_pad(($total['hrminutes_over']-$total['hrminutes_under'])%60, 2, '0', STR_PAD_LEFT)}}</strong> (do odbioru do końca kwartału)</p>
@elseif ($total['hrminutes_over'] < $total['hrminutes_under'])
    <p>ilość godzin w miesiącu <strong>{{$total['month_name']}} {{$total['year']}}</strong> przepracowanych poniżej normy: <strong>{{floor(($total['hrminutes_under']-$total['hrminutes_over'])/60).':'.str_pad(($total['hrminutes_under']-$total['hrminutes_over'])%60, 2, '0', STR_PAD_LEFT)}}</strong> (do odpracowania do końca kwartału)</p>
@endif
<!--div style="border: 2px solid black; display: inline-block; margin: 0 auto;">
<div style="background: #ffc; text-align: center; font-weight: bold;">NARASTAJĄCO<br>ilość godzin w {_{$total['quarter']}} kwartale<br>
(od {_{$total['quarter_start']}} do {_{$total['quarter_stop']}}):</div>
<table style="text-align: right;">
    <tr><td style="border: 0px;">do przepracowania ({_{$total['quarter_count']}} d.): </td><td style="border: 0px;"><strong>{_{floor($total['quarter_norm']/60).':'.str_pad($total['quarter_norm']%60, 2, '0', STR_PAD_LEFT) }}</strong></td></tr>
    <tr><td style="border: 0px;">przepracowanych/zaplanowanych: </td><td style="border: 0px;"><strong>{_{floor($total['quarter_minutes']/60).':'.str_pad($total['quarter_minutes']%60, 2, '0', STR_PAD_LEFT) }}</strong></td></tr>
    @_if ($total['quarter_norm']>$total['quarter_minutes'])
    <tr><td style="border: 0px;">poniżej normy: </td><td style="border: 0px;"><strong>{_{floor(($total['quarter_norm']-$total['quarter_minutes'])/60).':'.str_pad(($total['quarter_norm']-$total['quarter_minutes'])%60, 2, '0', STR_PAD_LEFT) }}</strong></td></tr>
    @_elseif ($total['quarter_minutes']>$total['quarter_norm'])
    <tr><td style="border: 0px;">powyżej normy: </td><td style="border: 0px;"><strong>{_{floor(($total['quarter_minutes']-$total['quarter_norm'])/60).':'.str_pad(($total['quarter_minutes']-$total['quarter_norm'])%60, 2, '0', STR_PAD_LEFT) }}</strong></td></tr>
    @_endif
</table>
</div-->
<div style="width: 100%;">
    <div style="border-top: 1px dotted black; width: 400px; margin-top: 60px; float:right; clear:both; ">
        <p class="mediumtxt acenter aitalic">data, podpis</p>
    </div>
    <div style="width: 400px; margin-top: 10px; float:right; clear:both; ">
        Wyrażam zgodę:
    </div>
    <div style="border-top: 1px dotted black; width: 400px; margin-top: 60px; float:right; clear:both; ">
        <p class="mediumtxt acenter aitalic">data, podpis bezpośredniego przełożonego</p>
    </div>
</div>
<div style="width: 100%; background: yellow; clear: both">
</div>



</div>
@endif
</body>
</html>
