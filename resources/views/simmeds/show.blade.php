@extends('layouts.app')
<?php include(app_path().'/include/view_common.php'); ?>


@section('title', 'scenariusze '.$simmed->simmed_date.': ')

@section('content')
<link href="{{ asset('css/_cards.css') }}" rel="stylesheet">


<div class="container">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {{ $message }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Uuuups!</strong> Przecież to nie powinno się wydarzyć!<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>


<?php

    $Scen_Table='<ul>';

    foreach ($simmed->scenarios as $scenlist)
    {
    $Scen_Table.='<li><a href="'.asset('scenario/'.$scenlist->id).'">';
    $Scen_Table.=$scenlist->scenario_name;
    $Scen_Table.='</a></li>';
    }

    $Scen_Table.='</ul>';
?>

<div class="row">
    <div class="col-lg-2 mb-2">
    </div>
    {{ kafelek(2, 'sala', $simmed->room()->room_number,NULL) }}
    {{ kafelek(4, 'instruktor', $simmed->name_of_leader(),NULL) }}
    {{ kafelek(4, 'technik', $simmed->name_of_technician(),NULL) }}
</div>

<div class="row">
    {{ kafelek(2, 'data symulacji', $simmed->simmed_date,NULL) }}
    {{ kafelek(2, 'godziny', substr($simmed->simmed_time_begin,0,5).' - '.substr($simmed->simmed_time_end,0,5),NULL) }}
        
    {{ kafelek(4, 'dział', $simmed->name_of_student_subject(),NULL) }}
    {{ kafelek(2, 'grupa studencka', $simmed->name_of_student_group(),NULL) }}
    {{ kafelek(2, 'podgrupa ', $simmed->name_of_student_subgroup(),NULL) }}
</div>
<div class="row" id="simmed_show">
    {{ kafelek(12, 'informacje (max. 255 znaków)', $simmed->simmed_alternative_title,NULL) }}
</div>
<!--div class="row">
    {{ kafelek(12, 'scenariusze', $Scen_Table,NULL) }}
</div-->

<div class="row">
    <p>{!! $simmed->opis !!}</p>
</div>
<div class="row" id="descript_show">
    @if ($simmed_descript->id > 0) 
        <h1>opis</h1>
        @if ( Auth::user()->hasRole('Technik') )
            <span class="text-danger"><strong>{{$simmed_descript->simmed_secret}}</strong></span><br>
        @endif
        {!!$simmed_descript->simmed_descript!!}
    @endif
</div>
@if ( Auth::user()->hasRole('Technik') )
<div class="row" id="descript_edit" style="display: none;">
    <form method="POST" action="{{ route('simmeds.descript_update') }}">
        {{ method_field('PUT') }}
        <div class="form-group">
        <h3>info:</h3>
        <p><i>dostępne są dwa pola do edycji: <strong>poufna notatka</strong> oraz <strong>opis</strong>:<br>
            <strong>opis</strong>: Służy do zapisywania informacji o przeprowadzonych zajęciach. Co się działo, jakie scenariusze były wykorzystywane, gdzie ich szukać, na co zwracać uwagę, jak sobie ułatwić zajęcia itp.
            Jest pokazywana jako informacja historyczna przy przeglądaniu kolejnych, podobnych symulacji (taki sam przedmiot i prowadzący). Można ją zobaczyć tylko z poziomu szczegółów symulacji (konkretnej, lub podobnej z późniejszego okresu). Dostępna jest tylko dla techników.<br>
            <strong>poufna notatka</strong>: Służy do zapisywania dodatkowych ważnych informacji, które jednak nie mają być przekazywane jako informacja historyczna. Pojawia się na pierwszej stronie wcsm.pl pod daną symulacją. 
        </i></p>
            <label>poufna notatka </label>
            <input type="text" class="form-control" name="simmed_secret" placeholder="dodaj dyskretną notatkę" value="{{$simmed_descript->simmed_secret}}">

            <label>opis</label>
            <textarea class="form-control tinymce-editor {{ $errors->has('simmed_descript') ? 'error' : '' }}" name="simmed_descript" id="simmed_descript"
                            rows="4">
                            {!!$simmed_descript->simmed_descript!!}
                        </textarea>
                        @if ($errors->has('simmed_descript'))
                        <div class="error">
                            {{ $errors->first('simmed_descript') }}
                        </div>
                        @endif

            {{ csrf_field() }}

            <input type="hidden" name="id" value="{{$simmed_descript->id}}">
            <input type="hidden" name="simmed_id" value="{{$simmed->id}}">
            <input class="btn btn-success col-sm-1" type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
            <div class="float-right col-sm-1">
                <span class="btn btn-info" onclick="showhide('navigation','descript_edit','descript_show')">anuluj</span>
            </div>
        </div>
    </form>
</div>
<div class="row" id="simmed_edit" style="display: none;">
<form method="POST" action="{{ route('simmeds.update') }}">
    {{ method_field('PUT') }}
     <div class="form-group">
        <h3>info:</h3>
        <p><i>edytowana poniżej informacja jest przypisana do tej symulacji. Widać ją w mailu ze zmianami w symulacjach (dla technika i koordynatora), wyświetla się na pierwszej stronie wcsm.pl pod daną symulacją, nie jest pokazywana jako informacja historyczna przy przeglądaniu kolejnych, podobnych symulacji (taki sam przedmiot i prowadzący)</i></p>
        <label>informacje</label>
        <textarea type="text" class="form-control" name="simmed_alternative_title" id="simmed_alternative_title" placeholder="dodatkowe informacje o zajęciach">{{$simmed->simmed_alternative_title}}</textarea>

        <label for="simmed_technician_id">Technik:</label>
        <select class="form-control" name="simmed_technician_id" id="simmed_technician_id">
            <option value="0"<?php if (0 == $simmed->simmed_technician_id) echo 'selected="selected"'; ?>>!! brak wyboru !!</option>
            @foreach ($technicians_list as $technician_one)
            <option value="{{$technician_one->id}}"<?php if ($technician_one->id == $simmed->simmed_technician_id) echo 'selected="selected"'; ?>>{{$technician_one->lastname}} {{$technician_one->firstname}}</option>
            @endforeach
        </select>

    {{ csrf_field() }}

    <input type="hidden" name="id" value="{{$simmed->id}}">
    <div class="float-right col-sm-2">
        <input class="btn btn-success" type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
    </div>
    <div class="float-right col-sm-2">
        <span class="btn btn-info" onclick="showhide('navigation','simmed_edit','simmed_show')">anuluj</span>
    </div>


    </div>
</form>
</div>

@endif

<div class="row" id="navigation" style="display: show;">
    <div class="float-right col-sm-2">
        <a class="btn btn-success" href="/scheduler/{{$simmed->simmed_date}}">
        {{$simmed->simmed_date }}
        <?php $table_date['date']=$simmed->simmed_date; ?>
        <span class="glyphicon glyphicon glyphicon-tasks" aria-hidden="true"></span>
        </a>
    </div>
    <div class="float-right col-sm-2">
        <a class="btn btn-success" href="{{route('simmeds.plane',$table_date)}}"> planowanie
            <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
        </a>
    </div>

    @if ($show_edit_button==true)
        @if (Auth::user()->hasRole('Operator Symulacji'))
        <div class="float-right col-sm-2"><a class="btn btn-danger" href="{{route('simmeds.edit', $simmed)}}">Edytuj dane symulacji</a></div>
        @else
        <div class="float-right col-sm-2"><span class="btn btn-info" onclick="showhide('navigation','simmed_edit','simmed_show')">
            Edytuj dane symulacji
            </span></div>
        @endif
        @if ( Auth::user()->hasRole('Technik') )
            <div class="float-right col-sm-2"><span class="btn btn-info" onclick="showhide('navigation','descript_edit','descript_show')">
            @if ($simmed_descript->id == 0) Dodaj opis dla technika @else Edytuj opis dla technika @endif
            </span></div>
        @endif
        @if ( Auth::user()->hasRole('Operator Symulacji') )
            <div class="float-left col-sm-2"><a class="btn btn-danger" href="{{route('simmeds.copy', $simmed)}}">Kopiuj (bez grupy)</a></div>
        @endif
    @endif
</div>


@if ($simulation_info->count()>0)
    <hr>
    <div class="row bg-warning">
        <ol><h2>zebrane informacje o zajęciach:</h2>
            @foreach ($simulation_info as $simulation_row)
                <h3><li> <strong>{{$simulation_row->simmed()->room()->room_number}}</strong> -
                    {{$simulation_row->simmed()->simmed_date}}, godz. {{substr($simulation_row->simmed()->simmed_time_begin,0,5)}}-{{substr($simulation_row->simmed()->simmed_time_end,0,5)}}  
                </h3>
                @if ( Auth::user()->hasRole('Technik') )
                <span class="text-danger"><strong>{{$simulation_row->simmed_secret}}</strong></span><br>
                <br>
                @endif
                <div class="card-text" style="white-space: pre-line">{!! $simulation_row->simmed_descript !!}</div>
            @endforeach
        </ol>
    </div>
    <hr>
@endif


@if ($technician_history->count()>0)
    <hr>
    <div class="row bg-warning">
        <ol><h2>historia zmian techników:</h2>
        @foreach ($technician_history as $history_row)
            <li>{{$history_row->updated_at}}:  <strong> {{$history_row->name_of_technician()}} </strong> (przez: {{$history_row->name_of_changer()}}) </li>
        @endforeach
        </ol>
    </div>
    <hr>
@endif

<hr>
    <div class="row bg-warning">
        <ol><h2>historia edycji:</h2>
            @foreach ($simmed_history as $history_row)
                <li><strong>{{$history_row->updated_at}}:</strong>  {{print_r($history_row->datas())}} (zmiana <strong>{{$history_row->change_code()}}</strong> przez: <strong>{{$history_row->name_of_changer()}}</strong>) </li>
            @endforeach
        <li><strong>{{$simmed->updated_at}}:</strong> bieżacy wpis dokonany przez: <strong>{{$simmed->name_of_changer()}}</strong></li> 
        </ol>
    </div>
    <hr>


<script>
function showhide(f_nav,f_edit,f_show) {
  var x = document.getElementById(f_nav);
  var y = document.getElementById(f_edit);
  var z = document.getElementById(f_show);
  if (z.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
    z.style.display = "block";
  } else {
    x.style.display = "none";
    y.style.display = "block";
    z.style.display = "none";
  }
}
</script>


@if ( Auth::user()->hasRole('Technik') )
<!-- for txt editor -->
<script src="https://cdn.tiny.cloud/1/6ylsotqai3tcowhx675l8ua28xj37zvsamtqvf4r94plg389/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="text/javascript">
  tinymce.init({
  selector: 'textarea.tinymce-editor',
  height: 500,
  menubar: true,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'charmap subscript superscript | ' +
  'removeformat | help',
  content_css: '//www.tiny.cloud/css/codepen.min.css'
});
    </script>
<!-- end txt editor -->
@endif

@endsection