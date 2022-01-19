<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="utf-8"><title>{{$title}}</title>
        <style>
            body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #eeffdd;
            }
            #simlist {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            #simlist td, #simlist th {
            border: 1px solid #ddd;
            padding: 8px;
            }

            #simlist tr:nth-child(even){background-color: #f2f2f2;}

            #simlist tr:hover {background-color: #ddd;}

            #simlist th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
            }
        </style>
    </head>
    <body>
        {!! $msgBody !!}
<?php
if (function_exists('show_changes'))
            {
                echo '.';
            }
else
    {
        function show_changes($current,$previous)
            {
            if ($current == $previous)
                return $current;
            else
                return $current.'<br>'.'<del>'.$previous.'</del>';
            }
        function date_changes($current,$previous)
            {
            if ($current == $previous)
                return $current;
            elseif ($previous == '2022-01-01')
                    return $current.'<br>(nowy)';
                else 
                    return $current.'<br>'.'<del>'.$previous.'</del>';
            }
        function time_changes($current,$previous)
            {
            if ($current == $previous)
                return $current;
            elseif ($previous == '00:00-00:00')
                    return $current;
                else
                    return $current.'<br>'.'<del>'.$previous.'</del>';
            }
    }

?>

    
    @foreach ($BigTable as $oneTable)
        <?php if (!(is_null($oneTable['table']))) { ?>
            <hr>
            {!!$oneTable['head']!!}
            <table id ="simlist">
                <tr>
                    <th>Data</th>
                    <th>Dzień</th>
                    <th>godz.</th>
                    <th>sala</th>
                    <th>prowadzący</th>
                    <th>przedmiot</th>
                    <th>grp.</th>
                    <th>technik</th>
                    <th>char.</th>
                </tr>
                @foreach ($oneTable['table'] as $simRow)
                    @if ($simRow->simmed_alternative_title=='') 
                        <?php $spanRow=''; ?>
                    @else 
                        <?php $spanRow='rowspan="2"'; ?>
                    @endif
                    <tr<?php if ($simRow->simmed_status==4) echo ' style="background:red"'; ?>>
                        <td {!!$spanRow!!}>
                            <?php echo date_changes($simRow->simmed_date,$simRow->send_simmed_date); ?>
                        </td>
                        <td {!!$spanRow!!}>
                        {{$simRow->DayOfWeek}}
                        <?php if ($simRow->simmed_status==4) echo '<br><strong> USUNIĘTY!</strong> '; ?>
                        </td>
                        <td {!!$spanRow!!}>
                            <?php echo time_changes($simRow->time,$simRow->send_time); ?>
                        </td>
                        <td {!!$spanRow!!}>
                            <?php echo show_changes($simRow->room_number,$simRow->send_room_number); ?>
                        </td>
                        <td>
                            <?php echo show_changes($simRow->leader,$simRow->send_leader); ?>
                        </td>
                        <td>
                            {{$simRow->student_subject_name}}
                            @if ( ($simRow->student_subject_id != $simRow->send_student_subject_id) && ($simRow->send_simmed_date!='2022-01-01') )
                                <br>(zmiana tematu)
                            @endif
                        </td>
                        <td>
                            {{$simRow->student_group_code}}
                            @if ( ($simRow->student_group_id != $simRow->send_student_group_id) && ($simRow->send_simmed_date!='2022-01-01') )
                                <br>(zmiana grupy)
                            @endif
                        </td>
                        <td>
                            <?php echo show_changes($simRow->technician_name,$simRow->send_technician_name); ?>
                        </td>
                        <td>
                            <?php echo show_changes($simRow->character_name,$simRow->send_character_name); ?>
                        </td>
                        @if ($simRow->simmed_alternative_title!='') 
                        <tr>
                            <td colspan="5">
                                {!!$simRow->simmed_alternative_title!!}
                            </td>
                        </tr>
                        @endif
                    </tr>
                @endforeach
            </table>
        
        <?php } ?>
    @endforeach         
        
        <br>
        <br>
        pozdrawiam<br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;UJKlides<br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;<italic>Internetowy System Informacyjny<br>
        &nbsp;&nbsp;&nbsp;&nbsp;CSM UJK<br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;(made by Sebastian)</italic>
        <br>
        <br>
        <hr>
        Ten mail został wysłany poprzez Nieoficjalny Internetowy System Informacyjny Centrum Symulacji Medycznej Collegium Medicum Uniwersytetu Jana Kochanowskiego w Kielcach.<br>
        System jest tworzony przez <a href="mailto:sebastian.dudek@ujk.edu.pl">Sebastiana</a> - na pewno będzie bardzo wdzięczny za przekazanie konstruktywnych uwag, jak ulepszyć działanie Systemu.
        <br>
        Jeśli nie chcesz otrzymywać maili z Systemu - po prostu <a href="mailto:sebastian.dudek@ujk.edu.pl?subject=proszę o wyłączenie maili z systemu UJKlides">napisz o tym</a>.<br> 
    </body>
</html>
