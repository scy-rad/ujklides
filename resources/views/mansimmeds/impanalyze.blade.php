<?php
if (!Auth::user()->hasRole('Operator Symulacji')) 
        return view('error',['head'=>'błąd wywołania widoku ManSimMeds impanalyze','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Symulacji']);
?>

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
        <th>od</th>
        <th>do</th>
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
    $rowek2='';
    if ($new_simmed->simmed_merge>0)
        if ($new_simmed->simmed_id==0)
            {
            $class=' class="bg-primary"';
            $rowek2='<tr><td colspan="5">&nbsp;</td></tr>';
            }
        else
            {
            $class=' class="bg-danger"';
            $class=' style="background-color: #66EEFF; margin-bottom:10px; padding-bottom:10px;"';
            }
    else
        {
        $class="";
        $rowek2='<tr><td colspan="5">&nbsp;</td></tr>';
        }
    ?>
    <tr id="{{$new_simmed['id']}}"<?php echo $class;?>>
    @if ($new_simmed['head']!='')
        <td>{{$new_simmed['head']}}</td>
    @endif
        <td>{{$new_simmed->simmed_date}}</td>
        <td>{{$new_simmed->simmed_time_begin}}</td>
        <td>{{$new_simmed->simmed_time_end}}</td>
        <td class="bg-info">{{$new_simmed->room_id}}</td>
        <td>{{$new_simmed->room()->room_number}}</td>
        <td class="bg-info">{{$new_simmed->simmed_leader_id}}</td>
        <td>@if ($new_simmed->simmed_leader_id>0) {{$new_simmed->leader()->full_name()}} @endif</td>
        <td class="bg-info">{{$new_simmed['student_subject_id']}}</td>
        <td>@if ($new_simmed['student_subject_id']>0) {{$new_simmed->student_subject()->student_subject_name}} @endif</td>
        <td class="bg-info">{{$new_simmed['student_group_id']}}</td>
        <td>@if ($new_simmed['student_group_id']>0) {{$new_simmed->student_group()->student_group_name}} @endif</td>
        <td class="bg-info">{{$new_simmed['student_subgroup_id']}}</td>
        <td>@if ($new_simmed['student_subgroup_id']>0) {{$new_simmed->student_subgroup()->subgroup_name}} @endif</td>
        <td>{{$new_simmed->simmed_alternative_title}}</td>
        <td>{{$new_simmed->simmed_id}}</td>
        <td>{{App\SimmedTemp::status_name($new_simmed->tmp_status)}}</td>
        <td>{{$new_simmed->simmed_merge}}</td>
    </tr>
    <?php
    echo $rowek2;
}
?>

        @extends('layouts.app')

@section('title', " Analiza importu")

@section('content')
<h1>Import zajęć z systemu uczelnia XP</h1>
<div class="text-right bg-danger">{{$step}}</div>




@switch ($step)

@case ('to_delete_analyze')
dane do usunięcia lub zastąpienia
{{dump($import_data)}}
@break

@case ('review_analyze')
sprawdź przed importem:
    <table width="100%">
        <?php rowEK_head(""); ?>
    @foreach ($import_data as $row_data) 
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

    <form action="{{ route('mansimmeds.import') }}" method="get">
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

    

@break

@case ('do_import')
dane zostały zaimportowane!
@break

@endswitch


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


@endsection        