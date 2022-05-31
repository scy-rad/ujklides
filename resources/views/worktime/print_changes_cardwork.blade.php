<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>wydruk z systemu UJKlides</title>
  </head>
  <style>
  h1, h2, h3 {text-align: center}
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
  .row {width: 100%; }
  footer {margin-bottom: 100px; font-size: 0.3rem; text-align: right}
  @media print {
  footer {page-break-after: always;}
}
  </style>
  <body>
  <?php function m2hcard($timeX) { return floor($timeX/60).':'.str_pad($timeX%60, 2, '0', STR_PAD_LEFT); } ?>

<!--p class="smalltxt aright">Załącznik nr 4 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small-->
<p class="aright">Kielce, dnia .................................</p>


@if ($total['hr_changes'])

<div style="margin: 10px auto;">
    <h1>Zmiana harmonogramu pracy</h1>

    <p>Proszę o uwzględnienie następujących zmian w harmonogramie pracy w miesiącu <strong>{{$total['month_name']}} {{$total['year']}}</strong> dla pracownika <strong>{{$user->full_name()}}</strong> zgodnie z poniższym wykazem:</p>
    <ul>
    @foreach ($tabelka as $row_one)
        @if ( ($row_one['changes']['hr_time_begin'] != $row_one['hr_wt']['hr_time_begin']) || ($row_one['changes']['hr_time_end'] != $row_one['hr_wt']['hr_time_end']) )
                    
        <li>{{$row_one['date']}} zmiana z <strong>{{$row_one['hr_wt']['hr_time_begin']}} - {{$row_one['hr_wt']['hr_time_end']}}</strong> na <strong>{{$row_one['changes']['hr_time_begin']}} - {{$row_one['changes']['hr_time_end']}}</strong> </li>
        @endif
    @endforeach
    </ul>
    <div class="row" style="margin-top: 60px; margin-bottom: 20px;">
        <div style="border-top: 1px dotted black; width: 400px;  float:right; ">
            <p class="mediumtxt acenter aitalic">data, podpis kierownika/bezpośredniego przełożonego</p>
        </div>
    </div>
    <div style="clear: both">
</div>
</div>
@endif


@if ($total['changes_over'])
<div style="">
    <h1>Zlecenie pracy w godzinach nadliczbowych</h1>

    <p>Proszę o uwzględnienie następujących zmian w zleconym pracownikowi <strong>{{$user->full_name()}}</strong>  na podstawie art. 151 § 1 Kodeksu Pracy wykonaniu pracy w godzinach nadliczbowych: </p>


    <ul>
        @foreach ($tabelka as $row_one)
            @if ($row_one['hr_wt']['over_under']==1)
                @if ($row_one['hr_wt']['over_txt'] != $row_one['changes']['over_txt'])
                    @if ($row_one['changes']['over_under']==1)
                        <li> {{$row_one['date']}} zmiana z <strong>{{$row_one['hr_wt']['over_txt']}}</strong> na <strong>{{$row_one['changes']['over_txt']}}</strong> 
                        </li>
                    @else
                        <li> {{$row_one['date']}} usunięcie godzin nadliczbowych: <strong>{{$row_one['hr_wt']['over_txt']}}</strong>
                        </li>
                    @endif
                @else
                <li> <s>{{$row_one['date']}} bez zmian: {{$row_one['hr_wt']['over_txt']}}</s></li>
                @endif
            @else
                @if ($row_one['changes']['over_under']==1)
                <li> {{$row_one['date']}} dodanie godzin  nadliczbowych <strong>{{$row_one['changes']['over_txt']}}</strong> </li>
                @endif
            @endif
        @endforeach
    </ul>
    <p>razem było: <strong>{{$total['hrtimes_over']}}</strong> godzin, a po zmianach jest: <strong>{{m2hcard($total['changes_minutes_over'])}}</strong> godzin pracy zleconej w godzinach nadliczbowych.</p>
    <br>
    </div>
    @endif 
    @if ($total['changes_over'] || $total['hr_changes'])
    <div>
    <h2>uzasadnienie</h2>
    <p>Zmiany wynikają z koniczności dostosowania grafiku pracy do zmian w obsługiwanych zajęciach w {{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name}}.</p>
    <p>.........................................................................................................................................................<br><br>
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
</div>
@endif


@if ($total['changes_under'])
<p><strong>{{$user->full_name()}}</strong><br>
<span class="mediumtxt aitalic">(imię i nazwisko)</span></p`>
<br>
<p><strong>{{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name_wersal}}</strong><br>
<span class="mediumtxt aitalic">(jednostka organizacyjna)</span></p>
<br>
<br>
<h1>zmiany udzielenia czasu wolnego w zamian za czas przepracowany w godzinach nadliczbowych</h1>


<p>Proszę o następujące zmiany udzielonego czasu wolnego za czas przepracowany w godzinach nadliczbowych: </p>

<ul>
     @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==2)
            @if ($row_one['hr_wt']['under_txt'] != $row_one['changes']['under_txt'])
                @if ($row_one['changes']['over_under']==2)
                    <li> {{$row_one['date']}} zmiana z <strong>{{$row_one['hr_wt']['under_txt']}}</strong> na <strong>{{$row_one['changes']['under_txt']}}</strong> </li>
                @else
                    <li> {{$row_one['date']}} usunięcie udzielenia czasu wolnego: <strong>{{$row_one['hr_wt']['under_txt']}}</strong> </li>
                @endif
            @else
            <li> <s>{{$row_one['date']}} bez zmian: {{$row_one['hr_wt']['under_txt']}}</s></li>
            @endif
        @else
            @if ($row_one['changes']['over_under']==2)
            <li> {{$row_one['date']}} dodanie udzielenia czasu wolnego <strong>{{$row_one['changes']['under_txt']}}</strong> </li>
            @endif
        @endif
     @endforeach
</ul>
<p>razem było: <strong>{{$total['hrtimes_under']}}</strong> godzin, a po zmianach jest: <strong>{{m2hcard($total['changes_minutes_under'])}}</strong> godzin udzielonego czasu wolnego.</p>

@endif

<div style="width: 50%; border: 2px solid blue; background: #ffd; padding: 12px; font-size: 0.75rem">
<div style="width: 100%; border: 2px solid blue; background: blue; color: #ffd; font-weight: bold; margin: -13px; margin-right: -23px; ; margin-bottom: 12px; padding: 12px; ">
podliczenie czasu przepracowanego w godzinach nadliczbowych i udzielonego czasu wolnego
</div>
    <p>bieżący okres: <strong>{{$total['month_name']}} {{$total['year']}}</strong></p>
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
    @if ($total['quarter_stop']>$total['quarter_start'])
        <hr>
        okres poprzedzający od <strong>{{$total['quarter_start']}}</strong> do <strong>{{$total['quarter_stop']}}</strong>:<br>
        @if ($total['quarter_minutes']>$total['quarter_norm'])
            ilość godzin przepracowanych w godzinach nadliczbowych: <strong>{{m2hcard($total['quarter_minutes']-$total['quarter_norm'])}}</strong> godzin. 
        @elseif ($total['quarter_minutes']<$total['quarter_norm'])
            ilość godzin udzielonego czasu wolnego: <strong>{{m2hcard($total['quarter_norm']-$total['quarter_minutes'])}}</strong> godzin.
        @else
            przepracowany zgodnie z normą czasu pracy. 
        @endif

        <hr>
        <strong>RAZEM:</strong><br>
        @if ($$total['changes_minutes_under']-$total['changes_minutes_over']+$total['quarter_minutes']-$total['quarter_norm'] > 0)
            ilość godzin przepracowanych w godzinach nadliczbowych: <strong>{{m2hcard($total['changes_minutes_under']-$total['changes_minutes_over']+$total['quarter_minutes']-$total['quarter_norm'])}}</strong> godzin. 
        @elseif ($total['changes_minutes_under']-$total['changes_minutes_over']+$total['quarter_minutes']-$total['quarter_norm'] < 0)
            ilość godzin przepracowanych w godzinach nadliczbowych: <strong>{{m2hcard(-($total['changes_minutes_under']-$total['changes_minutes_over']+$total['quarter_minutes']-$total['quarter_norm']))}}</strong> godzin.
        @else
            razem czas przepracowany zgodny z normą czasu pracy.
        @endif
    @endif
</div>

@if ($total['changes_under'])
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

<footer>[xD]</footer>
@endif


</body>
</html>
