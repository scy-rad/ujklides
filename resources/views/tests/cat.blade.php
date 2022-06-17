@extends('layouts.app')

<meta name="csrf-token" content="{{ csrf_token() }}" />

@section('content')

<h1>to jest widok do testów cat</h1>

<div class="container" style="margin-top: 50px;">
    <div class="row bg-success">
        <div class="col-sm-12">
            <form action="">
                <h4>Pomieszczenie:</h4>
                <select class="form-control form-select" name="room" id="room">
                    <!--option selected>Select room</option-->
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->room_number }} [{{ $room->room_name }}]</option>
                    @endforeach
                </select>
        </div>
    </div>
    <div class="row bg-success" style="padding-bottom: 15px;">
        <div class="col-sm-6">
                <h4>miejsce:</h4>
                <select class="form-control form-select" name="roomstorage" id="roomstorage">
                    @foreach ($roomstorages as $roomstorage)
                        <option value="{{ $roomstorage->id }}">{{ $roomstorage->room_storage_name }} [{{ $roomstorage->room_storage_shelf_count }} ]</option>
                    @endforeach
                </select>
        </div>
        <div class="col-sm-6">
                <h4>poziom:</h4>
                <select class="form-control form-select" name="roomshelf" id="roomshelf">
                @for ($i = 1; $i < ($roomstorages->first()->room_storage_shelf_count +1); $i++)
                    <option value="1">poziom {{$i}}</option>
                @endfor
                </select>
            </form>
        </div>
    </div>
</div>
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
                        $('#roomshelf').empty();
                        for (var i = 1; i < data.roomstorages[0].room_storage_shelf_count+1; i++) {
                            $('#roomshelf').append('<option value="'+i+'">poziom '+i+'</option>');
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
                        $('#roomshelf').empty();
                        // alert(JSON.stringify(data, null, 4));
                        for (var i = 1; i < data.shelf_count+1; i++) {
                            $('#roomshelf').append('<option value="'+i+'">poziom '+i+'</option>');
                        }
                        }
                    })
        });
});
</script>



@endsection