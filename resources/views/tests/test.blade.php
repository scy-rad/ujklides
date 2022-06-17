@extends('layouts.app')



@section('content')

<h1>to jest widok do testów</h1>


    <div class="panel panel-default">
      <div class="panel-heading">Select State and get bellow Related City</div>
      <div class="panel-body">
            <div class="form-group">
                <label for="title">Select State:</label>
                <select name="state" class="form-control" style="width:350px">
                    <option value="">--- Select State ---</option>
                    @foreach ($states as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Select City:</label>
                <select name="city" class="form-control" style="width:350px">
                </select>
            </div>
      </div>
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="state"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: '/testuj/ajax/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
                        $('select[name="city"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="city"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });


                    }
                });
            }else{
                $('select[name="city"]').empty();
            }
        });
    });
</script>




@endsection
}