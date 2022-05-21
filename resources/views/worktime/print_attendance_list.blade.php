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
  footer {margin-bottom: 100px; font-size: 0.3rem; text-align: right}
  @media print {
  footer {page-break-after: always;}
}
  </style>
  <body>

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
        @foreach ($users_tab as $user_one)
            <th class="time">
                godz. pracy
            </th>
            <th class="name">
            {{$user_one->firstname}} <br> {{$user_one->lastname}}
            </th>
        @endforeach
    </tr>
    @foreach ($days_tab as $day_one)
        <tr>
        <th class="date"> {{$day_one['day']}}  &nbsp; <span style="font-weight: normal">{{$day_one['day_of_week']}}</span> </th>
        @foreach ($users_tab as $user_one)
            <td class="time {{$big_tab[$user_one->id][$day_one['number']]['cell_class']}}"> {{$big_tab[$user_one->id][$day_one['number']]['AL_begin']}}-<br>{{$big_tab[$user_one->id][$day_one['number']]['AL_end']}}&nbsp;</td>
            <td class="sign {{$big_tab[$user_one->id][$day_one['number']]['cell_class']}}"> </td>
        @endforeach
        </tr>
    @endforeach
</table>

<div style="width: 100%; background: yellow; clear: both">
</div>
<footer>[xD]</footer>

</body>
</html>
