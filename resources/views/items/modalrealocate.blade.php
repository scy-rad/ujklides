<!-- Modal realocate-->
<div class="modal fade" id="realocateModal" tabindex="-1" role="dialog" aria-labelledby="realocateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="realocateModalLabel">tymczasowe przekazanie sprzętu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
        miejsce<br>
        <strong>{{ $item->current_storage()->room()->room_number }}</strong>
        {{ $item->current_storage()->room()->room_name }} <br>
        {{ $item->current_storage()->room_storage_number }} 
        {{ $item->current_storage()->room_storage_name }}
        <hr>
        <form method="post" action="{{ route('item.update', $item->id) }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="action" value="loan">
            {{ csrf_field() }}
            <fieldset>
            @if ($item->room_storage_current_id == $item->room_storage_id )
                <legend>miejsce wypożyczenia:</legend>
                <select name="new_room_storage">
                    @foreach (App\RoomStorage::all()->where('room_storage_sort',0)->where('room_id','<>',$item->storage()->room_id) as $stor_one)
                        <option value="{{ $stor_one->id }}">{{ $stor_one->room()->room_number.' '.$stor_one->room()->room_name }} ({{ $stor_one->room_storage_name }})</option>
                    @endforeach
                </select>
            @else
                <legend>zwrot do:</legend>
                <select name="new_room_storage">
                    <option value="{{$item->room_storage_id}}">{{$item->storage()->room()->room_number}} {{$item->storage()->room()->room_name}}</option>
                </select>
            @endif
            </fieldset>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>
        </form>




        <form action="select.php" id="formularz" action="post">
	<fieldset>
		<legend>Kategorie</legend>
		<select id="kategorie">
			<option value="1">Kategoria 1</option>
			<option value="2">Kategoria 2</option>
			<option value="3">Kategoria 3</option>
			<option value="4">Kategoria 4</option>
		</select>
	</fieldset>
</form>



    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal realocate-->