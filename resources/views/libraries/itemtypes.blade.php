<?php
if (!Auth::user()->hasRoleCode('itemoperators'))
        return view('error',['head'=>'błąd wywołania widoku Libraries Itemtypes','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Zasobów']);
?>

@extends('layouts.app')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>

@section('title', " Zarządzaj typami zasobów...")

@section('content')

@include('layouts.success_error')


<h1>Zarządzenie typami zasobów:</h1>

<?php 
    function recursive_list($data)
    {
    if (!(is_null($data)))
        {
        ?>
            <div class="row" style="border-bottom: green 1px solid">
            <div class="col-sm-5">
                [{{$data['info']['level']}}:{{$data['info']['current']}}]
                {{$data['info']['name']}}
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn btn-sm btn-primary" onClick="javascript:showMyModalForm('{{$data['info']['current']}}','{{$data['info']['parent']}}','{{$data['info']['name']}}')">Edycja</span>
                <button type="button" class="btn btn-sm btn-info" onClick="javascript:showMyModalForm('0',0,'')">Dodaj nowy</span>
            </div>
            </div>
        <?php
        if (isset($data['data']))
            {
            echo '<ul>';
            foreach ($data['data'] as $data2)
            recursive_list($data2);
            echo '</ul>';
            }
        }
    }
?>

<ol>
    @foreach ($item_types_tab as $one_row)
        <?php recursive_list($one_row); ?>
    @endforeach
</ol>   


<div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja typu zasobu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_item_type') }}" method="post">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for"item_type_parent">typ nadrzędny:</label>
            @for ($i=1; $i <= $max_level; $i++)
            <select class="form-control form-select" name="item_type_parent{{$i}}" id="item_type_parent{{$i}}" onchange="select_changed(item_type_parent{{$i}})">
            </select>
            @endfor
    
            <label for"item_type_name">nazwa:</label>
            <input type="text" class="form-control" id="item_type_name" name="item_type_name">

            <label for"item_type_description">opis:</label>
            <input type="text" class="form-control" id="item_type_description" name="item_type_description">

            <label for"item_type_sort">sortowanie:</label>
            <input type="text" class="form-control" id="item_type_sort" name="item_type_sort">

            <label for"item_type_photo">zdjęcie:</label>
            <input type="text" class="form-control" id="item_type_photo" name="item_type_photo">

            <label for"item_type_code">kod (do menu):</label>
            <input type="text" class="form-control" id="item_type_code" name="item_type_code">

            <label for"item_type_status">status:</label>
            <input type="text" class="form-control" id="item_type_status" name="item_type_status">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">[ Anuluj ]</button>
        <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
        <input type="hidden" id="id" name="id">
        {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>
</div>


<script>

function reload_modal(id_current) {
    
    @for ($i=1; $i <= $max_level; $i++)
        $('#item_type_parent{{$i}}').empty();
        @endfor

        alert(id_current+' : '+document.getElementById("id").value);

        $.ajax({
                    url:"{{ route('libraries.ajx_item_types') }}",
                    type:"POST",
                    data: {
                        item_type_id: id_current
                        },
                    success:function (data) {
                        // alert(JSON.stringify(data, null, 4));
                        console.log(JSON.stringify(data, null, 4));
                        var liczba;
                        liczba = 1; 
                        $.each(data.select_tables,function(index,current_table){
                            $.each(current_table.table,function(index,current_row){
                                if ((current_row.id != document.getElementById("id").value)                                 // dodaj do tablicy wszystkie elementy, oprócz tego jednego, który jest obecnie edytowany
                                    || (document.getElementById("id").value=='0'))                                          // no chyba że jest to nowy element, to wtedy nie wycinaj wpisu "0"
                                {
                                if (current_row.id == current_table.value)
                                    text_select = '<option value="'+current_row.id+'" selected="selected">'+current_row.name+'</option>';
                                else
                                    text_select = '<option value="'+current_row.id+'">'+current_row.name+'</option>';
                                $('#item_type_parent'+liczba).append(text_select);
                                }
                                });
                        liczba++;
                        });
                    }
                })
}

function select_changed($alfa){
    reload_modal($alfa.value);
}

function showMyModalForm(id_current, id_parent, name) {
    $('#EditModal').modal('show');
    $('#id').val(id_current);
    $('#item_type_name').val(name);

    alert('trzeba jeszcze dodać uzupełnianie pozostałych parametrów typów i na koniec odblokowa zapisywanie (bo póki co jest wyłączone w kontrolerze). Nie działa uzupełnianie selectów przy wyborze najwyższego poziomu ');

    reload_modal(id_current);
}
</script>


<!-- scripts for Ajax working technology -->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- /scripts for Ajax working technology -->

@endsection