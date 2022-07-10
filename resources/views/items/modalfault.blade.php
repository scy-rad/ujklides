<!-- Modal fault-->
<div class="modal fade" id="faultModal" tabindex="-1" role="dialog" aria-labelledby="faultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="faultModalLabel">zgłoszenie usterki</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
        <h2>nowe złoszenie </h2>

        <form method="post" action="{{ route('fault.store') }}">
            {{ csrf_field() }}
            <input type="hidden" name="item_id" value="{{$item->id}}">

            <fieldset>
                <label for="fault_title">tytuł:</label>
                <input type="text" class="form-control" id="fault_title" name="fault_title" value="">
                <label for="notification_description">opis:</label>
                <textarea class="form-control" id="notification_description" name="notification_description" rows="4" cols="75"></textarea>
            </fieldset>
      </div> <!-- modal-body -->

      <div class="modal-footer">
          <input type="hidden" id="fault_id" name="fault_id" value="0">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>    <!-- modal-footer -->
        </form>
    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal fault-->