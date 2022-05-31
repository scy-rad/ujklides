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
  no-print {display: none !important;}
}
  </style>
  <body>

  <no-print>
    <form action="{{ route('worktime.month') }}" method="get">
    <div style="width: 100%; clear: both; background: gray; ">
        <div style="width: 30%; float: left">
            <label for"technician">pracownik:</label>
            <select class="form-control" name="technician">
            @foreach (app\user::role_users('workers', 1, 0)
               ->orderBy('name')->get() as $tech_one)
                <option value="{{$tech_one->id}}"<?php if ($filtr['user']==$tech_one->id) echo ' selected'?>>{{$tech_one->name}} [{{$tech_one->full_name()}}]</option>
            @endforeach
            </select>
        </div>
        <div style="width: 30%; float: left">
            <label for"month">miesiąc:</label>
            <select class="form-control" name="month">
            @foreach ($months as $month_one)
                <option value="{{$month_one}}" <?php if ($filtr['month']==$month_one) echo ' selected'?>>{{$month_one}}</option>
            @endforeach
            </select>
        </div>
                <input class="form-control" type="hidden" name="workcard" value="get">
                <input class="btn btn-primary btn-big col-sm-12" type="submit" value="podgląd dokumentacji czasu pracy">
    </div>    
    </form>

  </no-print>
  <?php //function m2hcard($timeX) { return floor($timeX/60).':'.str_pad($timeX%60, 2, '0', STR_PAD_LEFT); } ?>


<p class="smalltxt aright">Załącznik nr 8 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<h1>LISTA OBECNOŚCI PRACOWNIKÓW {{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name_wersal}}</h1>
 
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
<footer>[{{date('Hidm')}}]</footer>


@if ($total['hrminutes_over']>0)
<p class="smalltxt aright">Załącznik nr 4 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<p class="aright">Kielce, dnia .................................</p>

<h1>Zlecenie pracy w godzinach nadliczbowych</h1>

<p>Na podstawie art. 151 § 1 Kodeksu Pracy zlecam pracownikowi <strong>{{$user->full_name()}}</strong> wykonanie pracy w godzinach nadliczbowych w dniach:</p>
<ul>
     @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==1)
            <li> {{  date('d-m-Y',strtotime($row_one['date'])) }} w godz. od {{substr($row_one['hr_wt']['o_time_begin'],0,5)}} do {{substr($row_one['hr_wt']['o_time_end'],0,5)}} => (<strong>{{$row_one['hr_wt']['o_hoursmin']}}</strong> godz.)</li> 
        @endif
     @endforeach
</ul>
<p>razem: <strong>{{$total['hrtimes_over']}}</strong> godzin.</b>

<h2>uzasadnienie</h2>
<p>Nadgodziny wynikają ze specyfiki pracy personelu technicznego w {{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name}}.</p>
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
<footer>[{{date('Hidm')}}]</footer>
@endif





@if ( ($total['hrminutes_under']>0) || ($total['total_quarter_over']>0) )
<div style="all_page">

<p class="smalltxt aright">Załącznik nr 6 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<p class="aright">Kielce, dnia .................................</p>

<p><strong>{{$user->full_name()}}</strong><br>
<span class="mediumtxt aitalic">(imię i nazwisko)</span></p`>
<br>
<p><strong>{{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name_wersal}}</strong><br>
<span class="mediumtxt aitalic">(jednostka organizacyjna)</span></p>
<br>
<br>

<h1>WNIOSEK O UDZIELENIE CZASU WOLNEGO OD PRACY W ZAMIAN ZA CZAS PRZEPRACOWANY W GODZINACH NADLICZBOWYCH:</h1>


@if ($total['hrminutes_under']>0)
    <p>Proszę o udzielenie czasu wolnego od pracy w miesiącu <strong>{{$total['month_name']}} {{$total['year']}}</strong> w liczbie <strong>{{$total['hrtimes_under']}}</strong> godzin w terminie: </p>
    <ul>
        @foreach ($tabelka as $row_one)
        @if ($row_one['hr_wt']['over_under']==2)
            <li> {{ date('d-m-Y',strtotime($row_one['date'])) }} w godz. {{$row_one['hr_wt']['under_txt']}} => (<strong>{{$row_one['hr_wt']['o_hoursmin']}}</strong> godz.)</li> 
        @endif
        @endforeach
    </ul>
@endif

<ul>w zamian za czas:
    @if ($total['quarter_minutes'] > $total['quarter_norm'])    
        <li>przepracowany w godzinach nadliczbowych w liczbie <strong>{{floor(($total['quarter_minutes']-$total['quarter_norm'])/60).':'.str_pad(($total['quarter_minutes']-$total['quarter_norm'])%60, 2, '0', STR_PAD_LEFT)}}</strong> godzin w terminie od <strong>{{ date('d-m-Y',strtotime($total['quarter_start'])) }}</strong> do <strong>{{ date('d-m-Y',strtotime($total['quarter_stop'])) }}</strong>.</li>
    @endif
    @if ($total['hrminutes_over'] > 0)    
        <li>przepracowany w godzinach nadliczbowych w liczbie <strong>{{floor(($total['hrminutes_over'])/60).':'.str_pad(($total['hrminutes_over'])%60, 2, '0', STR_PAD_LEFT)}}</strong> godzin w terminie od <strong>{{date('d-m-Y',strtotime($filtr['month'].'-01'))}}</strong> do <strong>{{date('t-m-Y',strtotime($filtr['month'].'-01'))}}</strong>.</li>
    @endif
</ul>

@if ( ($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) > 0)
    <p>Udzielenie czasu wolnego od pracy w liczbie <strong>{{floor(( ($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) )/60).':'.str_pad(( ($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) )%60, 2, '0', STR_PAD_LEFT)}}</strong> godzin planuję w następujących terminach: </p>
    <ul>
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
    </ul>
@elseif ( ($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) < 0)
    <p>Godziny nadmiarowe w liczbie <strong>{{floor(( -($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) )/60).':'.str_pad(( -($total['total_quarter_over']+$total['hrminutes_over']-$total['hrminutes_under']) )%60, 2, '0', STR_PAD_LEFT)}}</strong> godzin zostaną odpracowane w terminie: </p>
    <ul>
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
            <li> ................................ w godz. ........................ : (....................)</li> 
    </ul>
@endif


@if ($total['quarter_minutes'] < $total['quarter_norm'])
    ilość udzielonych godzin czasu wolnego w okresie (od {{ date('d-m-Y',strtotime($total['quarter_start'])) }} do {{ date('d-m-Y',strtotime($total['quarter_stop'])) }}) ponad ilość godzin przepracowanych w godzinach nadliczbowych:
    <strong>({{floor(($total['quarter_minutes']-$total['quarter_norm'])/60).':'.str_pad(($total['quarter_minutes']-$total['quarter_norm'])%60, 2, '0', STR_PAD_LEFT)}})</strong>
@endif


<p>Zastępstwo pełnić będą wybrani pracownicy {{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name}}.</p>
<hr>
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
