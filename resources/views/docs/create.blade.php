@extends('layouts.app')


@section('content')


<h2>dodawanie nowego dokumentu</h2>

    <form method="post" action="{{ route('docs.store') }}">

    <div class="form-group">
        <label>tytuł</label>
        <input type="text" class="form-control {{ $errors->has('docs_title') ? 'error' : '' }}" name="docs_title" id="docs_title" placeholder="wpisz tytuł">
        @if ($errors->has('docs_title'))
        <div class="error">
            {{ $errors->first('docs_title') }}
        </div>
        @endif
    </div>

    <div class="form-group">
        <label>podtytuł</label>
        <input type="text" class="form-control {{ $errors->has('docs_subtitle') ? 'error' : '' }}" name="docs_subtitle" id="docs_subtitle" placeholder="wpisz podtytuł">
        @if ($errors->has('docs_subtitle'))
        <div class="error">
            {{ $errors->first('docs_subtitle') }}
        </div>
        @endif
    </div>
    
    <!--div class="form-group">
        <label>data</label>
        <input type="text" class="form-control {{ $errors->has('docs_date') ? 'error' : '' }}" name="docs_date" id="docs_date" placeholder="wpisz datę">
        @if ($errors->has('docs_date'))
        <div class="error">
            {{ $errors->first('docs_date') }}
        </div>
        @endif
    -->
    </div>



    <div class="form-group">
        <label>Status</label>
        <input type="text" class="form-control {{ $errors->has('docs_status') ? 'error' : '' }}" name="docs_status" id="docs_status"  value="1">

        @if ($errors->has('docs_status'))
        <div class="error">
            {{ $errors->first('docs_status') }}
        </div>
        @endif
    </div>
    <div class="form-group">
        <label>opis</label>
        <textarea class="form-control tinymce-editor {{ $errors->has('docs_description') ? 'error' : '' }}" name="docs_description" id="docs_description"
            rows="4"  placeholder="wpisz treść"> </textarea>

        @if ($errors->has('docs_description'))
        <div class="error">
            {{ $errors->first('docs_description') }}
        </div>
        @endif
    </div>

    {{ csrf_field() }}
    
    <input type="hidden" name="docs_date" value="2020-09-19">
    <input type="hidden" name="ForTable" value="<?php echo $For_table; ?>">
    <input type="hidden" name="ForID" value="<?php echo $For_id; ?>">
    <input type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
</form>


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