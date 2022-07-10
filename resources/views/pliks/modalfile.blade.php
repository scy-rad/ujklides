<?php
// variables in modal:
// $plik
// $group_name
// $item_id
// $item_group_id
// $all_items
?>
<!-- Body Modal edit file -->
<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileModalLabel">Zmiana pliku...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->

      <form method="post" action="{{ route('plikfor.update', $plik->id) }}">
        <div class="modal-body">
                <fieldset>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="update_action" value="itemgroup">
                    <input type="hidden" name="id" value="{{$plik->id}}">
                    <input type="hidden" name="item_group_id" value="{{$item_group_id}}">
                    {{ csrf_field() }}
            
                    <h4>grupa {{$item_group_id}}:</h4>
                    {{$group_name}}
                    <h4>egzemplarz:</h4>
                    <select class="form-control form-select" name="item_id">
                        <option value="0" @if ($item_id==0) selected="selected" @endif>dla wszystkich egzemplarzy</option>
                        @foreach ($all_items as $item_one)
                            <option value="{{$item_one->id}}"@if ($item_one->id==$item_id) selected="selected" @endif>{{$item_one->id}}: {{$item_one->item_inventory_number}} (S/N: {{$item_one->item_serial_number}})</option>
                        @endforeach
                    </select>

                    <h4>ścieżka pliku:</h4>
                    <input type="text" class="form-control" name="plik_dir_name" id="plik_dir_name" value="{{$plik->plik_directory.$plik->plik_name}}" >
                    <a href="javascript:open_popup('/js/filemanager/filemanager/dialog.php?type=2&popup=1&field_id=plik_dir_name')" class="form-control btn btn-secondary" style="background:#ffd" role="button" margin="0px">
                        wybierz plik
                    </a>
                    <h4>Rodzaj pliku:</h4>
                    <select class="form-control form-select" name="plik_type_id">
                        @foreach (App\PlikType::all()->sortBy('plik_type_sort') as $type_one)
                            <option value="{{$type_one->id}}"@if ($plik->plik_type_id==$type_one->id) selected="selected" @endif>{{$type_one->plik_type_name}}</option>
                        @endforeach
                    </select>
                    <h4>Tytuł pliku:</h4>
                    <input type="text" class="form-control" name="plik_title" value="{{$plik->plik_title}}">
                    <h4>Opis pliku (opcjonalnie):</h4>
                    <textarea class="form-control" name="plik_description" id="plik_description" rows="4" cols="75">{!! $plik->plik_description !!}</textarea>
                </fieldset>
        </div> <!-- modal-body -->
        <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
                <button type="submit" class="btn btn-primary">zapisz</button>
        </div>    <!-- modal-footer -->
      </form>

        @if ($plik->id>0)
            <form method="post" action="{{ route('plikfor.delete', $plik->id) }}">
                <fieldset>
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="update_action" value="itemgroup">
                    <input type="hidden" name="id" value="{{$plik->id}}">
                    <input type="hidden" name="item_id" value="{{$item_id}}">
                    <input type="hidden" name="item_group_id" value="{{$item_group_id}}">
                    {{ csrf_field() }}
                <input type="checkbox" class="form-control form-check-inline" style="display:inline-flex !important;" name="agree">
                
                <button type="submit" class="btn btn-danger" style="display:inline-flex !important;">usuń</button>
                </fieldset>
            </form>
        @endif


    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal edit file-->



<!-- Body Modal Responsive File Manager -->
<div class="modal fade" id="myModal" style="width:700px; margin-left: -350px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Przeglądarka plików</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 700px;">
                <iframe width="700" height="400" src="/js/filemanager/filemanager/dialog.php?type=2&field_id=plik_dir_name&fldr=pliki" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /Modal Responsive File Manager -->
