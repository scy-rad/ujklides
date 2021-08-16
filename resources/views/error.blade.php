@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{$head}}</h2></div>

                <div class="panel-body bg-warning">
                    <strong>{{$title}}</strong>
                        <div class="alert alert-danger">
                            {{$description}}
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
