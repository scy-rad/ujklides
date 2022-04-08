@extends('layouts.app')

@section('title', 'wykaz usuniętych pozycji')

@section('content')
<!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">



<h1>Wykaz usuniętych zajęć:</h1>
<hr>
<?php dump($simmeds_deleted); ?>    
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
    
@foreach ($simmeds_deleted as $simmed)
<tr>
    <td><a href="/scheduler/{{$simmed->simmed_date}}">
        {{ $simmed->simmed_date }}
        <span class="glyphicon glyphicon glyphicon-tasks" aria-hidden="true"></span>
        </a>
        <br>
    <?php 
    $dni_tygodnia = array( 'Ni', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'Sb' );
    $date = date( "w" );
    echo $dni_tygodnia[ date('w',strtotime($simmed->simmed_date)) ];
     ?>
        <a href="{{route('simmeds.show', [$simmed->id, 0])}}"> pokaż
            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
        </a>
    </td>
    <td>{{ substr($simmed->simmed_time_begin,0,5) }} - {{ substr($simmed->simmed_time_end,0,5) }}</td>
    <td>{{ $simmed->room_number }}</td>
    <td>{{ $simmed->leader }}</td>
    
    <td>{{ $simmed->technician_name }}</td>
    <td>{{ $simmed->student_group_name }} </td>
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


