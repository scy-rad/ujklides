@extends('layouts.app')

@section('content')

<?php include(app_path().'/include/view_common.php'); ?>
<!--link href="{{ asset('css/ujklides.css') }}" rel="stylesheet"-->

<div class="clearfix">...</div>

    <form method="post" action="{{ route('scenarios.update',$scenario->id) }}">
    <input type="hidden" name="_method" value="PUT">

<?php
function input_form($pool_label, $pool_name, $pool_value, $errors)
	{
	?>
    <div class="form-group">
        <h2>{{$pool_label}}</h2>
        <input type="text" class="form-control {{ $errors->has($pool_name) ? 'error' : '' }}" name="{{$pool_name}}" id="{{$pool_name}}" value="{{$pool_value}}">

        @if ($errors->has($pool_name))
        <div class="error">
            {{ $errors->first($pool_name) }}
        </div>
        @endif
    </div>
	<?php
	}
    ?>

<?php    
    function input_list($pool_label, $pool_name, $pool_table, $pool_current, $errors)
    {
    ?>
    <div class="form-group">
        <h2>{{$pool_label}}</h2>
        <select type="text" class="form-control {{ $errors->has($pool_name) ? 'error' : '' }}" name="{{$pool_name}}" id="{{$pool_name}}">
            <?php echo make_option_list('list_type', $pool_table, $pool_current); ?>
        </select>

            
        @if ($errors->has($pool_name))
        <div class="error">
            {{ $errors->first($pool_name) }}
        </div>
        @endif
    </div>
    <?php
	}
    ?>


<?php input_form('nazwa', 'scenarios_name', $scenario->scenarios_name, $errors); ?>
<?php input_list('autor', 'scenarios_author_id', $scenario->get_user(0), $scenario->scenarios_author_id, $errors); ?>
<?php input_form('główny problem', 'scenarios_main_problem', $scenario->scenarios_main_problem, $errors); ?>

    <div class="form-group">
        <h2>opis przypadku</h2>
        <textarea class="form-control tinymce-editor {{ $errors->has('scenarios_description') ? 'error' : '' }}" name="scenarios_description" id="scenarios_description"
            rows="4"> {{$scenario->scenarios_description}}</textarea>

        @if ($errors->has('scenarios_description'))
        <div class="error">
            {{ $errors->first('scenarios_description') }}
        </div>
        @endif
    </div>

    {{ csrf_field() }}

    <input type="hidden" name="subaction" value="main">
    <input type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
</form>

<!--h1>{{ print_r($scenario) }}</h1-->


<script src="https://cdn.tiny.cloud/1/sibtmr4zcxb4i4bhqtixk6pvvx0eww5nty90bo71i9n19liq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

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


@endsection