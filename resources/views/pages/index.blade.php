@extends('layouts.app')


@section('content')
<h1>TRZECI wygląd kontrolera</h1>

<table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>TITLE</th>
            <th>EDIT</th>
            <th>DELETE</th>
        </tr>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->title  }}</td>
                <td><a class="btn btn-info" href="{{route('pages.edit', $page)}}">Edit</a>
				<a class="btn btn-danger" href="#">Delete</a>
                              
			   <td>
                    
			   </td>
            </tr>
        @endforeach
    </table>
	
	{{ $pages->links() }}			<!-- dodanie paginatora -->

@endsection