
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
        <form method="post" action="{{ route('item.update', $item->id) }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="update" value="invent_data">
            {{ csrf_field() }}

            <fieldset>
                <label for="item_group_id">grupa (nieedytowalne):</label>
                <input type="text" class="form-control" id="item_group_id" name="item_group_id" value="{{ $item->item_group_id }}" disabled="disabled">
                <label for="item_serial_number">numer seryjny:</label>
                <input type="text" class="form-control" id="item_serial_number" name="item_serial_number" value="{{ $item->item_serial_number }}">
                <label for="item_inventory_number">numer inwentarzowy:</label>
                <input type="text" class="form-control" id="item_inventory_number" name="item_inventory_number" value="{{ $item->item_inventory_number }}">
                <label for="item_purchase_date">data zakupu:</label>
                <input type="date" class="form-control" id="item_purchase_date" name="item_purchase_date" value="{{ $item->item_purchase_date }}">
                <label for="item_warranty_date">opis:</label>
                <input type="date" class="form-control" id="item_warranty_date" name="item_warranty_date" value="{{ $item->item_warranty_date }}">
                <label for="item_description">opis:</label>
                <textarea class="form-control" id="item_description" name="item_description" rows="4" cols="75">{!! $item->item_description !!}</textarea>
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