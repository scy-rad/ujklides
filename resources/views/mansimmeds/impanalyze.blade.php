<?php
if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds impanalyze','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

@if ($step_code==101)
    <?php
    function rowEK_head($head)
    {
        ?>
        <thead>
        <tr>
        @if ($head!='')
            <th>{{$head}}</th>
        @endif
            <th>data</th>
            <th>czas</th>
            <th class="bg-info">ID:</th>
            <th>sala</th>
            <th class="bg-info">ID:</th>
            <th>instruktor</th>
            <th class="bg-info">ID:</th>
            <th>temat</th>
            <th class="bg-info">ID:</th>
            <th>grupa</th>
            <th class="bg-info">ID</th>
            <th>sub</th>
            <th class="bg-info">ID</th>
            <th>tech</th>
            <th>char</th>
            <th>info</th>
            <th>sim id</th>
            <th>status</th>
            <th>merge</th>
        </tr>
    </thead>
        <?php
    }

    function rowEK($new_simmed)
    {
        if ($new_simmed->tmp_status==2)
            {
                $class=' class="bg-success"';
                $rowek2='<tr class="bg-danger">';
                $prev=App\Simmed::find($new_simmed->simmed_id);
             
                if ($new_simmed->simmed_date == $prev->simmed_date)
                    $rowek2.='<td></td>';
                else
                    $rowek2.='<td>'.$prev->simmed_date."</td>";
                if ($new_simmed->time == (substr($prev->simmed_time_begin,0,5).'-'.substr($prev->simmed_time_end,0,5)))
                    $rowek2.='<td></td>';
                else
                    $rowek2.='<td>'.substr($prev->simmed_time_begin,0,5).'-'.substr($prev->simmed_time_end,0,5).'</td>';
                if ($new_simmed->room_id == $prev->room_id)
                    $rowek2.='<td></td><td></td>';
                else
                    $rowek2.='<td></td><td>'.$prev->room()->room_number."</td>";
                if ($new_simmed->leader_id == $prev->simmed_leader_id)
                    $rowek2.='<td></td><td></td>';
                elseif ($prev->simmed_leader_id > 0)
                    $rowek2.='<td>'.$prev->simmed_leader_id.'</td><td>'.$prev->leader()->full_name()."</td>";
                else 
                    $rowek2.='<td></td><td>- - -</td>';
                if ($new_simmed->student_subject_id == $prev->student_subject_id)
                    $rowek2.='<td></td><td></td>';
                else
                    $rowek2.='<td>'.$prev->student_subject_id.'</td><td>'.$prev->student_subject()->student_subject_name."</td>";
                if ($new_simmed->student_group_id == $prev->student_group_id)
                    $rowek2.='<td></td><td></td>';
                else
                    $rowek2.='<td>'.$prev->student_group_id.'</td><td>'.$prev->student_group()->student_group_name."</td>";
                if ($new_simmed->student_subgroup_id == $prev->student_subgroup_id)
                    $rowek2.='<td></td><td></td>';
                else
                    $rowek2.='<td>'.$prev->student_subgroup_id.'</td><td>'.$prev->student_subgroup()->subgroup_name."</td>";
                if ($new_simmed->simmed_technician_id == $prev->simmed_technician_id)
                    $rowek2.='<td></td><td></td>';
                elseif ($prev->simmed_technician_id > 0)
                    $rowek2.='<td>'.$prev->simmed_technician_id.'</td><td>'.$prev->technician()->name."</td>";
                else 
                    $rowek2.='<td></td><td>- - -</td>';
                if ($new_simmed->simmed_technician_character_id == $prev->simmed_technician_character_id)
                    $rowek2.='<td></td>';
                else
                    $rowek2.='<td>'.$prev->technician_character()->character_short."</td>";
                if ($new_simmed->simmed_alternative_title == $prev->simmed_alternative_title)
                    $rowek2.='<td></td>';
                else
                    $rowek2.='<td>'.$prev->simmed_alternative_title."</td>";
                
                $rowek2.='<td></td>'; //simmed_id
                $rowek2.='<td></td>'; //status_tmp
                $rowek2.='<td></td>'; //simmed_merge
                $rowek2.="</tr>";
                $rowek2.="<tr><td>&nbsp;</td></tr>";
            }
        else
            {
            $class="";
            $rowek2='';
            }
        
        ?>
        <tr id="{{$new_simmed->id}}"<?php echo $class;?>>
        
            <td>{{$new_simmed->simmed_date}}</td>
            <td>{{$new_simmed->time}}</td>
            <td class="bg-info">{{$new_simmed->room_id}}</td>
            <td>{{$new_simmed->room_number}}</td>
            <td class="bg-info">{{$new_simmed->leader_id}}</td>
            <td>{{$new_simmed->leader}}</td>
            <td class="bg-info">{{$new_simmed->student_subject_id}}</td>
            <td>{{$new_simmed->student_subject_name}}</td>
            <td class="bg-info">{{$new_simmed->student_group_id}}</td>
            <td>{{$new_simmed->student_group_name}}</td>
            <td class="bg-info">{{$new_simmed->student_subgroup_id}}</td>
            <td>{{$new_simmed->subgroup_name}}</td>
            <td class="bg-info">{{$new_simmed->simmed_technician_id}}</td>
            <td>{{$new_simmed->technician_name}}</td>
            <td>{{$new_simmed->character_short}}</td>
            <td>{{$new_simmed->simmed_alternative_title}}</td>
            <td>{{$new_simmed->simmed_id}}</td>
            <td>{{App\SimmedTemp::status_name($new_simmed->tmp_status)}}</td>
            <td>{{$new_simmed->simmed_merge}}</td>
        </tr>
        <?php
        echo $rowek2;
    }
    ?>
@endif
        @extends('layouts.app')

@section('title', " Analiza importu")

@section('content')
<h1>Import zajęć z pliku tekstowego</h1>



@if ($step_code==101)
sprawdź przed importem:
    <table width="100%">
        <?php rowEK_head(""); ?>
    @foreach ($data_return as $row_data)
        <?php rowEK($row_data); ?>
    @endforeach
    </table>


    <form action="{{ route('mansimmeds.markimport') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="step" value="markimport">
        <input class="btn btn-primary btn-lg" type="submit" value="zmień status pozostałym">
    </form>


    <form action="{{ route('mansimmeds.impanalyze') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="step" value="edit_import">
                <input class="btn btn-primary btn-lg" type="submit" value="analizuj dane">
    </form>

    <form action="{{ route('mansimmeds.import_append') }}" method="post">
        {{ csrf_field() }}
        <input class="btn btn-primary btn-lg" type="submit" value="przenieś dane do bazy symulacji">
    </form>

    <form action="{{ route('mansimmeds.impclear') }}" method="post">
        {{ csrf_field() }}
        <!--input type="hidden" name="step" value="import_analyze">
        <input type="hidden" name="step" value="import_autostatus">
        <input type="hidden" name="step" value="import_movetodatabase"-->
        <input type="hidden" name="step" value="import_clear">
        <input class="btn btn-primary btn-lg" type="submit" value="usuń import">
</form>


<ol>
<li>sprawdż, czy są jakieś wpisy do usunięcia</li>
<li>jeżeli nie ma, to importuj</li>
<li>w przeciwnym wypadku:
<ul>dla każdego wpisu do usunięcia:
    <li>sprawdż, czy w nowych wpisach niepowiązanych jest taki sam, tylko z inną salą lub instruktorem. Jeśli jest - to "powiąż je" ze sobą.</li>
    <li>sprawdż, czy w nowych wpisach niepowiązanych jest taki, który ma wspólnego instruktora, przedmiot, grupę i podgrupę. Jeśli jest - to "powiąż je" ze sobą. Jeśli nie - idź dalej</li>
    <li>sprawdż, czy w nowych wpisach niepowiązanych jest taki, który ma wspólny przedmiot, grupę i podgrupę. Jeśli jest - to "powiąż je" ze sobą. Jeśli nie - idź dalej</li>
</ul>
<li>wyświetl wszystkie wpisy z uwidoczenieniem proponowanych powiązań.<br>
Użytkownik powinien zaznaczyć, które woisy chce dodać, które zmodyfikować, a które usunąć.<br>
Wpisy nie zaznaczone będą "wisieć" w imporcie i nie zostaną zaimplementowane do rzeczywistej bazy do czasu ponownego uruchomienia analizy i wybrania działania.</li>
</ol>
@else
    @if ($step_code<13)

        <form action="{{ route('mansimmeds.impanalyze') }}" method="post">
                {{ csrf_field() }}
                <input class="btn btn-primary btn-lg" type="submit" value="krok {{$step_code}}">
                <input type="hidden" name="step_code" value="{{$step_code}}">
        </form>
    @endif
        <form action="{{ route('mansimmeds.impanalyze') }}" method="post">
                {{ csrf_field() }}
                <input class="btn btn-primary btn-lg" type="submit" value="pokaż dzieło :)">
                <input type="hidden" name="step_code" value="100">
        </form>
        <?php dd(); ?>
        
@endif
@endsection