@extends('layouts.app')

@section('content')

<h2>Edycja zajęć</h2>


    <form method="POST" action="{{ route('simmeds.update') }}">
    {{ method_field('PUT') }}
     <div class="form-group">

        <label>data</label>
        <input type="text" class="form-control" name="simmed_date" id="simmed_date" placeholder="podaj datę symulacji" value="{{$simmed->simmed_date}}">

        <label>od</label>
        <input type="text" class="form-control" name="simmed_time_begin" id="simmed_time_begin" placeholder="podaj czas rozpoczęcia symulacji" value="{{$simmed->simmed_time_begin}}">

        <label>do</label>
        <input type="text" class="form-control" name="simmed_time_end" id="simmed_time_end" placeholder="podaj czas zakończenia symulacji" value="{{$simmed->simmed_time_end}}">

        <label>informacje</label>
        <textarea type="text" class="form-control" name="simmed_alternative_title" id="simmed_alternative_title" placeholder="najważniejsze informacje o symulacji">{{$simmed->simmed_alternative_title}}</textarea>

        <label for="simmed_technician_id">Technik:</label>
        <select name="simmed_technician_id" id="simmed_technician_id">
            @foreach ($technicians_list as $technician_one)
            <option value="{{$technician_one->id}}"<?php if ($technician_one->id == $simmed->simmed_technician_id) echo 'selected="selected"'; ?>>{{$technician_one->lastname}} {{$technician_one->firstname}}</option>
            @endforeach
        </select>

        <br>
        <label for="simmed_technician_character_id">Charakter:</label>
        <select name="simmed_technician_character_id" id="simmed_technician_character_id">
            @foreach ($technician_characters_list as $character_one)
            <option value="{{$character_one->id}}"<?php if ($character_one->id == $simmed->simmed_technician_character_id) echo 'selected="selected"'; ?>>{{$character_one->character_short}}: {{$character_one->character_name}}</option>
            @endforeach
        </select>

        

        <br>
        <label for="simmed_leader_id">Instruktor:</label>
        <select name="simmed_leader_id" id="simmed_leader_id">
            @foreach ($leaders_list as $leader_one)
            <option value="{{$leader_one->id}}"<?php if ($leader_one->id == $simmed->simmed_leader_id) echo 'selected="selected"'; ?>>{{$leader_one->lastname}} {{$leader_one->firstname}}</option>
            @endforeach
        </select>
        <br>
        <label for="student_subject_id">Temat:</label>
        <select name="student_subject_id" id="student_subject_id">
            <option value="0"<?php if (0 == $simmed->student_subject_id) echo 'selected="selected"'; ?>> !! NIC !! </option>
            @foreach ($subjects_list as $subject_one)
            <option value="{{$subject_one->id}}"<?php if ($subject_one->id == $simmed->student_subject_id) echo 'selected="selected"'; ?>> {{$subject_one->student_subject_name}} </option>
            @endforeach
        </select>
        <br>

        <label for="student_subgroup_id">rok/kier/gr:</label>
        <br>
        <strong>obecnie nieedytowalna</strong>

        <br>
        <label for="room_id">Sala:</label>
        <select name="room_id" id="room_id">
            @foreach ($rooms_list as $room_one)
            <option value="{{$room_one->id}}"<?php if ($room_one->id == $simmed->room_id) echo 'selected="selected"'; ?>> {{$room_one->room_number}}: {{$room_one->room_name}} </option>
            @endforeach
        </select>

        <br>
        <label for="simmed_status">Status:</label>
        <select name="simmed_status" id="simmed_status">
            @foreach ($status_list as $status_one)
            <option value="{{$status_one['id']}}"<?php if ($status_one['id'] == $simmed->simmed_status) echo 'selected="selected"'; ?>> {{$status_one['name']}} </option>
            @endforeach
        </select>

        <br>

<?php /*

    "simmed_technician_character_id" => 5
    "simmed_status" => 1
    */
    ?>
    </div>

    {{ csrf_field() }}

    <input type="hidden" name="id" value="{{$simmed->id}}">
    <input type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
</form>

@endsection