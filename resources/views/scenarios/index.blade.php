@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">


<?php     if ( Auth::user()->hasRole('Magazynier') ) { ?>
<a class="glyphicon glyphicon-plus-sign glyphiconbig pull-right" href="route('devices.create')"></a>
<?php } ?>

<h1>Wykaz scenariuszy</h1>
	<table id="exatwo" class="display" style="width:100%">
    <thead>
        <tr>
            <th data-field="id" data-sortable="true">ID</th>
            <th data-field="scenarios_name" data-sortable="true">nazwa</th>
            <th data-sortable="true">Temat</th>
            <th data-sortable="true">problem</th>
			<th data-sortable="false">x</th>
        </tr>
    </thead>
    <tbody>

    @foreach($scenarios as $scenario)
		<tr>
			<td>{{ $scenario->id }}</td>
			<td>{{ $scenario->scenario_name }}</td>
			<td> 
            <ul>
                @foreach ($scenario->subjects as $subject)
                    <li>{{$subject->student_subject_name}}</li>
                @endforeach
            </ul>
             </td>
			<td>{!! $scenario->scenario_main_problem !!}</td>
			
            <td><li><a href="{{route('scenarios.show', $scenario)}}">
                pokaż
				</a></li></td>
		</tr>
    @endforeach
	</tbody>
	</table>
     

	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script>
    $(document).ready(function() {
      $('#exatwo').DataTable();
	} );
	</script>


@endsection


