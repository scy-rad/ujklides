@extends('layouts.app')

@section('content')

<h2>dodawanie nowej symulacji</h2>


    <form method="post" action="{{ route('simmeds.store') }}">
     <div class="form-group">
        <label>data i godzina</label>
        <input type="text" class="form-control {{ $errors->has('nr_seryjny') ? 'error' : '' }}" name="nr_seryjny" id="nr_seryjny" placeholder="podaj numer seryjny">

        <!-- Error -->
        @if ($errors->has('nr_seryjny'))
        <div class="error">
            {{ $errors->first('nr_seryjny') }}
        </div>
        @endif
    </div>

    <div class="form-group">
        <label>Pokój</label>
        
        <select type="text" class="form-control {{ $errors->has('room_id') ? 'error' : '' }}" name="room_id" id="room_id">
            <?php echo make_option_list('room_id', $simmed->get_rooms(), $simmed->room_id); ?>
        </select>

        @if ($errors->has('lib_group_id'))
        <div class="error">
            {{ $errors->first('lib_group_id') }}
        </div>
        @endif
    </div>

    <div class="form-group">
        <label>Status</label>
        <input type="text" class="form-control {{ $errors->has('status') ? 'error' : '' }}" name="status" id="status"  value="1">

        @if ($errors->has('status'))
        <div class="error">
            {{ $errors->first('status') }}
        </div>
        @endif
    </div>
    <div class="form-group">
        <label>opis</label>
        <textarea class="form-control tinymce-editor {{ $errors->has('opis') ? 'error' : '' }}" name="opis" id="opis"
            rows="4"  placeholder="napisz opis urządzenia"> </textarea>

        @if ($errors->has('opis'))
        <div class="error">
            {{ $errors->first('opis') }}
        </div>
        @endif
    </div>

    {{ csrf_field() }}

    <input type="hidden" name="subaction" value="main">
    <input type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
</form>


@endsection