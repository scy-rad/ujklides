<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>wydruk z systemu UJKlides</title>
  </head>
  <style>
  h1, h2 {text-align: center}
  body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 0.5rem; 
  -webkit-print-color-adjust: exact !important;
}
  table {border-collapse: collapse; margin-right: auto; margin-left: auto;}
  th {border: 1px solid black; background: #ff6; font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis}
  td {border: 1px solid black}
  .aright {text-align: right}
  .acenter {text-align: center}
  .smalltxt {font-size: 0.5rem}
  .mediumtxt {font-size: 0.75rem}
  .aitalic {font-style: italic}
  .nowork {background: #fcc}
  .name {width: 150px; max-width: 150px; min-width: 150px;}
  .sign {width: 100px; font-size: 0.5rem}
  .time {width: 50px; font-size: 0.75rem; border-right: 1px solid #ddd; border-left: 3px double black;}
  .date {width: 100px;}
  .free_day {background: #ccc}
  footer {margin-bottom: 100px; font-size: 0.3rem; text-align: right; width: 100%; clear: both}
  @media print {
  .footer2 {page-break-after: always;}
}
  </style>
  <body>
<?php $nofirst=false; ?>
@foreach ($extra_tab as $big_tab)
@if ($nofirst)
<div class="footer2">&nbsp</div>
@else
<?php $nofirst=true; ?>
@endif
<p class="smalltxt aright">Załącznik nr 8 do Regulaminu Pracy w Uniwersytecie Jana Kochanowskiego w Kielcach</small>
<h1>LISTA OBECNOŚCI PRACOWNIKÓW {{\App\Param::select('*')->orderBy('id','desc')->get()->first()->unit_name_wersal}}</h1> 
<br>
<h2>COLLEGIUM MEDICUM</h2>
<br>
<table>
    <tr>
        <th>
            {{$head['month_name']}}<br>
            {{$head['year']}}
        </th>
          <?php $counter=0; ?>
        @foreach ($big_tab['user_id'] as $user_one)
            <?php $counter++; ?>
            <th class="time">
                godz. pracy
            </th>
            <th class="name">
            {{$users_tab[$user_one]->firstname}} <br> {{$users_tab[$user_one]->lastname}}
            </th>

        @endforeach
        @for($i = $counter; $i < $user_count; $i++ )
            <th class="time">
                godz. pracy
            </th>
            <th class="name">
            &nbsp;
            </th>
        @endfor
 
    </tr>
    @foreach ($days_tab as $day_one)
        <tr>
        <th class="date"> {{$day_one['day']}}  &nbsp; <span style="font-weight: normal">{{$day_one['day_of_week']}}</span> </th>
        <?php $counter=0; ?>
        @foreach ($big_tab['user_id'] as $user_one)
          <?php $counter++; ?>
          <td class="time {{$big_tab['table'][$user_one][$day_one['number']]['cell_class']}}"> {{$big_tab['table'][$user_one][$day_one['number']]['AL_begin']}}-<br>{{$big_tab['table'][$user_one][$day_one['number']]['AL_end']}}&nbsp;</td>
          <td class="sign {{$big_tab['table'][$user_one][$day_one['number']]['cell_class']}}"> </td>
        @endforeach
        @for($i = $counter; $i < $user_count; $i++ )
          <td class="time {{$big_tab['table'][$user_one][$day_one['number']]['cell_class']}}"> &nbsp;</td>
          <td class="sign {{$big_tab['table'][$user_one][$day_one['number']]['cell_class']}}"> </td>
        @endfor
        </tr>
    @endforeach
</table>
<footer>[{{date('Hidm')}}]</footer>

@endforeach;
</body>
</html>
