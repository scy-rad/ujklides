
<!-- Body Modal change localization-->
<div class="modal fade" id="changeLocalizationModal" tabindex="-1" role="dialog" aria-labelledby="changeLocalizationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeLocalizationModalLabel">zmiana domyślnej lokalizacji</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
        <h2>Trwała zmiana lokalizacji: </h2>


        <div class="bg-primary">poprzednia lokalizacja:</div>
        <strong>{{ $item->storage()->room()->room_number }}</strong>
        {{ $item->storage()->room()->room_name }} <br>
        {{   $item->storage()->room_storage_number }} 
        {{ $item->storage()->room_storage_name }}

        

        <div class="bg-primary">nowa lokalizacja:</div>

            <strong>{{ $item->current_storage()->room()->room_number }}</strong>
            {{ $item->current_storage()->room()->room_name }} <br>
            {{ $item->current_storage()->room_storage_number }} 
            {{ $item->current_storage()->room_storage_name }}

        <hr>

            <form method="post" action="{{ route('item.save_loc', [$item->id]) }}">
            {{ csrf_field() }}
            <input type="hidden" name="item_id" value="{{$item->id}}">
            <fieldset>
            <label for="item_storage_shelf">numer poziomu/półki:</label><br>
                <input type="text" id="item_storage_shelf" name="item_storage_shelf" value="{{ $item->item_storage_shelf }}"><br>
            </fieldset>
      </div> <!-- modal-body -->

      <div class="modal-footer">
          <input type="hidden" id="room_storage_id" name="room_storage_id" value="{{$item->room_storage_current_id}}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>    <!-- modal-footer -->
        </form>
    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal change localization-->