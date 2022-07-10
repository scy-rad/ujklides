<?php /*
variables in modal:
$tyoes
$ItemGroup
*/ ?>
<!-- Body Modal edit invenarization data -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edycja...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
        <form method="post" action="{{ route('itemgroups.update', $ItemGroup->id) }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="update" value="basic_data">
            {{ csrf_field() }}

            <fieldset>
                <label for="item_type_id">typ:</label>
                <select class="form-control form-select" name="item_type_id" id="item_type_id">
                  @foreach ($item_types as $item_type)
                    <option value="{{$item_type->id}}"@if ($item_type->id == $ItemGroup->item_type_id) selected="selected" @endif>{{$item_type->item_type_name}}</option>
                  @endforeach
                </select>
                <label for="item_group_name">nazwa:</label>
                <input type="text" class="form-control" id="item_group_name" name="item_group_name" value="{{ $ItemGroup->item_group_name }}">
                <label for="item_group_producent">producent:</label>
                <input type="text" class="form-control" id="item_group_producent" name="item_group_producent" value="{{ $ItemGroup->item_group_producent }}">
                <label for="item_group_model">model:</label>
                <input type="text" class="form-control" id="item_group_model" name="item_group_model" value="{{ $ItemGroup->item_group_model }}">
                <label for="item_group_description">opis:</label>
                <textarea class="form-control" id="item_group_description" name="item_group_description" rows="4" cols="75">{!! $ItemGroup->item_group_description !!}</textarea>
                <label for="item_group_status">status:</label>
                <input type="text" class="form-control" id="item_group_status" name="item_group_status" value="{{ $ItemGroup->item_group_status }}">
            </fieldset>
      </div> <!-- modal-body -->

      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>    <!-- modal-footer -->
        </form>
    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal edit invenarization data-->