@extends('layouts.app')

@section('title', 'symulacje')

@section('content')
<!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">



<h1>Wykaz zajęć na pracowniach</h1>
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
            <th data-sortable="false">x</th>
            
        </tr>
    </thead>
    <tbody>
    
@foreach ($simmeds as $simmed)
<tr>
    <td><a href="/scheduler/{{$simmed->simmed_date}}">
        {{ $simmed->simmed_date }}
</a>
        <br>
    <?php 
    $dni_tygodnia = array( 'Niedziela', 'Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek', 'Sobota' );
    $date = date( "w" );
    echo $dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ];
     ?>
    </td>
    <td>{{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}</td>
    <td>{{ $simmed->room()->room_number }}</td>
    <td>{{ $simmed->name_of_leader() }}</td>
    
    <td>{{ $simmed->name_of_technician() }}</td>
    <td>{{ $simmed->name_of_student_group() }} {{ $simmed->name_of_student_subgroup() }}</td>
    <td>{{ $simmed->name_of_student_subject() }}</td>
    <td>{{ $simmed->simmed_alternative_title }}</td>
    <td> <a href="{{route('simmeds.show', [$simmed, 0])}}">pokaż</a></td>
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
            <th>x</th>
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


