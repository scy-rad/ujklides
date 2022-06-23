@extends('layouts.app')


<meta name="csrf-token" content="{{ csrf_token() }}" />


@section('content')


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

<div class="row">
    <div class="col-sm-10">
        <h1> {{$plik->plik_title}} </h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ url()->previous() }}"><button> powrót </button></a>
    </div>
</div>

<div class="row">
<div class="col-sm-2">
<h3> {{$plik->plik_version}} </h3>
<p> {!!$plik->plik_description!!} </p>
<hr>
<ol><strong>przypisanie do egzemplarza</strong>
    @foreach ($plik->items()->get() as $plik_item_one) 
        <li> {{$plik_item_one->item()->first()->group()->item_group_name}}, S/N: {{$plik_item_one->item()->first()->item_serial_number}} </li>
    @endforeach
</ol>
<hr>
<ol><strong>przypisanie do grupy egz.</strong>
    @foreach ($plik->groups()->get() as $plik_group_one)
    <li onclick="OpenModal('groups','{{$plik_group_one->id}}','{{$plik_group_one->item_group_id}}')">
    {{$plik_group_one->group()->first()->item_group_name}} 
    </li> 
    @endforeach
</ol>
<button onclick="OpenModal('groups','0','0')">
    Dodaj nowy 
    </button>

<hr>
<ol><strong>przypisanie do pomieszczenia</strong>
    @foreach ($plik->rooms()->get() as $plik_room_one)
    <li> {{$plik_room_one->room()->first()->room_number}} </li> 
    @endforeach
</ol>

<hr>

</div>
<div class="col-sm-10">
<iframe src="{{asset('/storage/pliki/'.$plik->plik_directory.$plik->plik_name)}}#view=fitH" style="width: 100%; box-sizing: border-box;  height: calc(100% - 55px);border: 1px solid #000;">Wystąpił błąd</iframe>  
</div>
</div>


<?php $chose_tab=[]; ?>

<!-- Modal choose room / group / item -->
<div class="modal fade" id="chooseModal" tabindex="-1" role="dialog" aria-labelledby="chooseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chooseModalLabel">Wybór przypisania dokumentu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span id="choose_head">pomieszczenie</span>

        <form method="post" action="{{ route('plikfor.update', $plik->id) }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="update_action" id="update_action" value="nothing">
            <input type="hidden" name="plik_for_id" id="plik_for_id" value="0">
            {{ csrf_field() }}
            <fieldset>
                <select class="form-control form-select" name="choose_id" id="choose_id">
                    <option value='0'>błąd pobierania danych</option>
                </select>
            </fieldset>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>
        </form>



    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal choose room / group / item -->



<!-- scripts for Modal choose room / group / item -->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

function OpenModal(choose, plik_for_id, choose_current) {
    
    switch (choose) {
        case 'groups':
                choose_txt='grupa egzemplarzy';
                $.ajax({
                        url:"{{ route('plik.ajx_groups') }}",
                        type:"POST",
                        success:function (data) {
                            $('#choose_id').empty();
                            $.each(data.choose_table,function(index,choose_table){
                                if (choose_table.id == choose_current)
                                    {
                                    $('#choose_id').append('<option value="'+choose_table.id+'" selected="selected">'+choose_table.choose_value+'</option>');
                                    }
                                else
                                    {
                                    $('#choose_id').append('<option value="'+choose_table.id+'">'+choose_table.choose_value+'</option>');
                                    }
                                });
                            $('#choose_id').append('<option value="0">!! USUŃ WPIS !! </option>');
                            }
                        });
            break;
        }
    document.getElementById("choose_head").innerHTML=choose_txt;
    document.getElementById("update_action").value=choose;
    document.getElementById("plik_for_id").value=plik_for_id;

    $("#chooseModal").modal();
}
</script>

<!-- /scripts for choose room / group / item -->


@endsection

