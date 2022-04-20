@extends('layouts.app')

@section('title', 'symulacje')

@section('content')
<!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">



<h1>Wykaz zajęć na pracowniach</h1>
<form action="{{ route('simmeds.index',['route' => 'now']) }}" method="get">
    <div class="row">
        <div class="col-sm-3">
            <label for"start">od-do:</label><br>
            <input type="date" name="start" value="{{$filtr['start']}}">
            <input type="date" name="stop" value="{{$filtr['stop']}}">
        </div>        
        <div class="col-sm-3">
            <label for"csm">grupa stud.:</label><br>
            <select class="form-control" name="csm">
                <option value="0" @if (0 == $filtr['csm']) selected="selected" @endif>wszystko</option>
                <option value="-1" @if (-1 == $filtr['csm']) selected="selected" @endif>nieokreślone</option>
                @foreach ($center_list as $center_one)
                <option value="{{$center_one->id}}" @if ($center_one->id == $filtr['csm']) selected="selected" @endif>{{$center_one->center_direct}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-sm-1">
            <br>
            <input class="btn btn-primary btn-sm" type="submit" value="pokaż">
        </div>

        <div class="col-sm-3">
            &nbsp;
        </div>
    </form>
        <div class="col-sm-1">
            <br>
            <a href="{{ route('simmeds.index', $filtr) }}" target="_blank">
                <input class="btn btn-success btn-sm" type="submit" value="pobierz CSV">
            </a>
        </div>
    </div>

<hr>

<!--table class="table table-hover"-->
<table id="exatwo" class="display" style="width:100%"
data-sort-priority='[{"sortName": "DaTe","sortOrder":"desc"},{"sortName":"FromTo","sortOrder":"desc"}]'
>
    <thead>
        <tr>
            <th data-field="DaTe" data-sortable="true">Data</th>
            <th data-field="FromTo" data-sortable="true">Od - do</th>
            <th>Sala</th>
            <th data-sortable="true">Instruktor</th>
            <th data-sortable="true">Technik</th>
            <th>Grupa</th>
            <th>Zajęcia</th>
            <th>Info</th>
        </tr>
    </thead>
    <tbody>

@foreach ($simmeds as $simmed)
<tr>
    <td><a href="/scheduler/{{$simmed->simmed_date}}">
        {{ $simmed->simmed_date }}
        <span class="glyphicon glyphicon glyphicon-tasks text-success" aria-hidden="true"></span>
        </a>
        <br>
    <?php 
    $dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );
    $date = date( "w" );
    echo $dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ];
     ?>
        <a href="{{route('simmeds.show', [$simmed->id, 0])}}"> pokaż
            <span class="glyphicon glyphicon-list-alt text-success" aria-hidden="true"></span>
        </a>
    </td>
    <td>{{ $simmed->time }}</td>
    <td>{{ $simmed->room_number }}</td>
    <td>{{ $simmed->leader }}</td>
    
    <td>{{ $simmed->technician_name }}</td>
    <td>{{ $simmed->student_group_code }} {{ $simmed->subgroup_name }}</td>
    <td>{{ $simmed->student_subject_name }}</td>
    <td>{{ $simmed->simmed_alternative_title }}</td>
</tr>
@endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Data</th>
            <th>Od - do</th>
            <th>Sala</th>
            <th>Instruktor</th>
            <th>Technik</th>
            <th>Grupa</th>
            <th>Zajęcia</th>
            <th>Info</th>
        </tr>
    </tfoot>
    </table>

  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/multiple-sort/bootstrap-table-multiple-sort.js"></script>

<script>
    $(document).ready(function() {
      $('#exatwo').DataTable(
          {
            "pageLength": 25
          }
      );
  } );
  </script>

@endsection