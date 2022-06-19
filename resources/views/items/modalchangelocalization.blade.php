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

        <div class="bg-primary">. aktualna stała lokalizacja:</div>
        <strong>{{ $item->storage()->room()->room_number }}</strong>
        {{ $item->storage()->room()->room_name }} <br>
        {{   $item->storage()->room_storage_number }} 
        {{ $item->storage()->room_storage_name }}

        @if ($item->room_storage_id != $item->room_storage_current_id)
        <div class="bg-primary">. aktualna tymczasowa lokalizacja:</div>

            <strong>{{ $item->current_storage()->room()->room_number }}</strong>
            {{ $item->current_storage()->room()->room_name }} <br>
            {{ $item->current_storage()->room_storage_number }} 
            {{ $item->current_storage()->room_storage_name }}

        <hr>
        @endif

        <form method="post" action="{{ route('item.update', $item->id) }}">
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="update" value="localization">   
          {{ csrf_field() }}
          <fieldset>
            <div class="row bg-success">
              <div class="col-sm-12">
                <h4>Pomieszczenie:</h4>
                <select class="form-control form-select" name="room" id="room">
                  <!--option selected>Select room</option-->
                  @foreach ($rooms as $room)
                    <option value="{{ $room->id }}"@if ($room->id == $item->current_storage()->room()->id) selected="selected" @endif>{{ $room->room_number }} [{{ $room->room_name }}]</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row bg-success" style="padding-bottom: 15px;">
              <div class="col-sm-6">
                <h4>miejsce:</h4>
                <select class="form-control form-select" name="roomstorage" id="roomstorage">
                  @foreach ($roomstorages as $roomstorage)
                    <option value="{{ $roomstorage->id }}"@if ($roomstorage->id == $item->room_storage_current_id) selected="selected" @endif>{{ $roomstorage->room_storage_name }} [{{ $roomstorage->room_storage_shelf_count }}]</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-6">
                <h4>poziom:</h4>
                <select class="form-control form-select" name="item_storage_shelf" id="item_storage_shelf">
                  @for ($i = 1; $i < ($roomstorages->first()->room_storage_shelf_count +1); $i++)
                    <option value="{{$i}}"@if ($i == $item->item_storage_shelf) selected="selected" @endif>poziom {{$i}}</option>
                  @endfor
                </select>
              </div>
            </div>
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
<!-- /Modal change localization-->

<!-- scripts for Modal change localization -->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $('#room').on('change',function(e) {
        var room_id = e.target.value;
            
            $.ajax({
                    url:"{{ route('testuj.ajx_roomstorages') }}",
                    type:"POST",
                    data: {
                        room_id: room_id
                        },
                    success:function (data) {
                        $('#roomstorage').empty();
                        // alert(JSON.stringify(data, null, 4));
                        $.each(data.roomstorages,function(index,roomstorage){
                            $('#roomstorage').append('<option value="'+roomstorage.id+'">'+roomstorage.room_storage_name+'</option>');
                            });
                        // alert(data.roomstorages[0].room_storage_shelf_count);
                        $('#item_storage_shelf').empty();
                        for (var i = 1; i < data.roomstorages[0].room_storage_shelf_count+1; i++) {
                            $('#item_storage_shelf').append('<option value="'+i+'">poziom '+i+'</option>');
                        }

                        }
                    })
        });
    });
    $(document).ready(function () {
        $('#roomstorage').on('change',function(e) {
        var room_storage_id = e.target.value;

            $.ajax({
                    url:"{{ route('testuj.ajx_shelf_count') }}",
                    type:"POST",
                    data: {
                        room_storage_id: room_storage_id
                        },
                    success:function (data) {
                        $('#item_storage_shelf').empty();
                        // alert(JSON.stringify(data, null, 4));
                        for (var i = 1; i < data.shelf_count+1; i++) {
                            $('#item_storage_shelf').append('<option value="'+i+'">poziom '+i+'</option>');
                        }
                        }
                    })
        });
});
</script>
<!-- /scripts for Modal change localization -->