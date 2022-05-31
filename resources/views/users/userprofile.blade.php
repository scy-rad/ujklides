@extends('layouts.app')


<?php
//$isAdmin=( Auth::user()->hasRole('Administrator') ); // - może edytować role, maila, telefony, opis i zmieniać zdjęcie
$isAdmin=( Auth::user()->hasRole('Operator Kadr') || Auth::user()->hasRole('Administrator'));
if (Auth::user()->id == $user->id)
    $isOwn = True;
else
    $isOwn = False;

?>

@section('content')



<style>
    /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

    <div class="container">
        <div class="row">
            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Tadaaaaaaaaaaa!!</strong><br>
                    {{ $message }}

                </div>

            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Uuuups!</strong> Przecież to nie powinno się wydarzyć!<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>



    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-3">
                <div class="profile-header-container">
                    <div class="profile-header-img">
                        <img class="col-sm-12 rounded-circle userphoto" src="/storage/avatars/{{ $user->user_fotka }}" >
                        <!-- badge -->
                        <!--div class="rank-label-container">
                            <span class="label label-default rank-label">{{$user->name}}</span>
                        </div-->
                    </div>
                </div>
                @if ($isAdmin || $isOwn)
                <div class="rowEK justify-content-center">
                    <form action="/userprofile" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <input type="file" class="form-control" name="fotka" id="fotkaFile" aria-describedby="fileHelp">
                            <button type="submit" class="form-control btn btn-primary">Prześlij</button>
                            <small id="fileHelp" class="form-text text-muted">Przekaż plik o właściwych proporcjach (pionowy, 800x600). Nie może być większy niż 2MB.</small>
                        </div>

                    </form>

                    <h2>widok startowy</h2>
                    <form action="{{ route('user.change_home_view') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label for="sel1">ilość dni pracy:</label><br>
                            <select class="form-control" name="home_own_days">
                            @for ($i = 0; $i <= 7; $i++)
                                <option value="{{ $i }}" <?php if ($user->home_own_days==$i) echo ' selected="selected"'; ?>>{{ $i }}</option>
                            @endfor
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label for="sel1">II moduł:</label><br>
                            <select class="form-control" name="home_second_module">
                            
                                <option value="0" <?php if ($user->home_second_module==0) echo ' selected="selected"'; ?>>brak modułu</option>
                                <option value="1" <?php if ($user->home_second_module==1) echo ' selected="selected"'; ?>>bieżący dzień (scheduler)</option>
                            
                            </select>
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <hr>
                        <button type="submit" class="col-sm-12 btn btn-primary">Zmień widok</button>
                    </div>
                </form>

                </div>
                @endif
            </div>
            <div class="col-sm-9">
                <h4> {{$user->title->user_title_short}}</h4>
                <h2> {{$user->firstname}} {{$user->lastname}}</h2>
                @if ($isAdmin)
                        <div style="float:right">
                            <button class="btn btn-primary btn-sm" onClick="openPersonalModal()">Edytuj dane</button>
                        </div>
                        <hr>
                @endif
                <ul>
                    @foreach ($user->roles as $row)
                    <li>{{$row->roles_name}}<br>
                        <i>{{$row->roles_description}}</i>
                        @if ($isAdmin)
                          @if (!($row->roles_name=='Administrator' && Auth::user()->hasRole('Administrator')))
                            <div style="float:right">
                            <form action="{{ route('user.remove_role') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="roles_id" value="{{$row->pivot->roles_has_users_roles_id}}">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <button type="submit" class="btn btn-danger btn-sm">usuń rolę</button>
                            </form>
                            </div>
                            <hr>
                          @endif
                        @endif
                    </li>
                    @endforeach
                </ul>
                <hr>
                <span class="glyphicon glyphicon-envelope"></span> <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                    @if ($isAdmin || $isOwn)
                        <div style="float:right">
                        <button class="btn btn-primary btn-sm" onClick="openMailModal()">Edytuj e-mail</button>
                        </div>
                        <hr>
                    @endif
                <br>
                @foreach ($user->phones as $row)
                    @if (
                            (Auth::user()->hasRole('Administrator'))
                        || ((Auth::user()->hasRole('Koordynator')) && ($row->phone_for_coordinators==1))
                        || ((Auth::user()->hasRole('Instruktor')) && ($row->phone_for_trainers==1))
                        || ((Auth::user()->hasRole('Technik')) && ($row->phone_for_technicians==1))
                        || ($row->phone_for_guests==1)
                        )
                        <abbr title="{{$row->type->user_phone_type_name}}">
                        <span class="glyphicon {{$row->type->user_phone_type_glyphicon}}"></span>
                        </abbr>
                        {{$row->phone_number}}<br>
                    @endif
                    @if ($isAdmin || $isOwn)
                        <div style="float:right">
                        <button class="btn btn-primary btn-sm" onClick="openPhoneModal('{{$row->id}}','{{$row->type->id}}','{{$row->phone_number}}','{{$row->phone_for_coordinators}}','{{$row->phone_for_technicians}}','{{$row->phone_for_trainers}}','{{$row->phone_for_guests}}','{{$row->phone_for_anonymouse}}')">Edytuj numer</button>
                        </div>
                        <hr>
                    @endif
                @endforeach

                @if ($isAdmin || $isOwn)
                    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
                    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
                    otrzymywanie powiadomień mailowych o zajęciach
                    <div style="float:right" onClick="change_notify()">
                        <input value="1" class="simmed_notify" type="checkbox" <?php if ($user->simmed_notify==1) echo "checked"; ?> data-toggle="toggle" data-on="wysyłaj" data-off="nie wysyłaj">
                    </div>
                    <hr>
                @endif
                {!!$user->about!!}
                @if ($isAdmin || $isOwn)
                    <div style="float:right">
                    <button class="btn btn-primary btn-sm" onClick="openAboutModal()">Edytuj opis</button>
                    </div>
                    <hr>
                @endif
                <hr>

            </div>
        </div>
        @if ($isAdmin || $isOwn)
        <div class="row alert alert-danger">
            <h2>Panel administracyjny</h2>
            <h5> id: <strong>{{$user->id}}</strong></h5>
            <h5> login: <strong>{{$user->name}}</strong></h5>
            <hr>
            <div class="form-group">

            <div class="col-sm-8">
                &nbsp;
                </div>
                <a href="{{ route('changePasswordForm') }}"><button class="col-sm-4 btn btn-primary">Zmień hasło</button></a>
            </div>
            <div class="col-sm-8">
                &nbsp;
                </div>
                <button class="col-sm-4 btn btn-primary" onClick="openPhoneModal('0','0','','1','1','1','0','0')">Dodaj numer telefonu</button>
            </div>
            @if ($isAdmin)
            
            <form action="{{ route('user.change_password') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <label for="password">nowe hasło:</label><br>
                        <div class="col-sm-8">
                        <input type="password" name="password" value="">
                        <input type="password" name="passwordre" value="">
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="col-sm-4 btn btn-primary">Zmień hasło</button>
                    </div>
                </form>
                <hr>


                <form action="{{ route('user.change_status') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="sel1">status:</label><br>
                        <div class="col-sm-8">
                            <select class="form-control" name="user_status">
                                <option value="0" <?php if ($user->user_status==0) echo ' selected="selected"'; ?>> nieaktywny </option>
                                <option value="1" <?php if ($user->user_status==1) echo ' selected="selected"'; ?>> aktywny </option>
                            </select>
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="col-sm-4 btn btn-primary">Zmień status</button>
                    </div>
                </form>
                <hr>

                <form action="{{ route('user.add_role') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="sel1">lista ról:</label><br>
                        <div class="col-sm-8">
                            <select class="form-control" name="roles_id">
                            @foreach (App\Roles::get() as $row)
                                @if (!($user->hasRole($row->roles_name)))
                                    @if (!($row->roles_name=='Administrator' && Auth::user()->hasRole('Administrator')))
                                        <option value="{{$row->id}}"> {{$row->roles_name}} </option>
                                    @endif
                                @endif
                            @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="col-sm-4 btn btn-primary">Dodaj rolę</button>
                    </div>
                </form>
            @endif
        </div>

    <!-- The Modal -->
    <div id="phoneModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <span class="close">&times;</span>
            </div>
            <form action="{{ route('user.change_phone') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="user_phone_type_id">rodzaj telefonu:</label><br>
                            <select class="form-control" id="user_phone_type_id" name="user_phone_type_id">
                            @foreach (App\UserPhoneType::get() as $row)
                                <option value="{{$row->id}}"> {{$row->user_phone_type_name}} </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="phone_number">numer telefonu:</label><br>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="">
                        </div>
                        <div class="col-sm-3">
                            <label for="xyz">prezentowanie telefonu:</label><br>
                            <input type="checkbox" class="form-check-input" id="phone_for_coordinators" name="phone_for_coordinators">koordynatorom<br>
                            <input type="checkbox" class="form-check-input" id="phone_for_technicians" name="phone_for_technicians">technikom<br>
                            <input type="checkbox" class="form-check-input" id="phone_for_trainers" name="phone_for_trainers">instruktorom<br>
                            <input type="checkbox" class="form-check-input" id="phone_for_guests" name="phone_for_guests" checked>gościom<br>
                            <input type="checkbox" class="form-check-input" id="phone_for_anonymouse" name="phone_for_anonymouse" checked>wszystkim<br>
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="id_phone" id="id_phone" value="">
                        <button type="submit" class="col-sm-3 btn btn-primary">Zapisz</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- The Modal -->
    <div id="mailModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <span class="close">&times;</span>
            </div>
            <div class="row">
                <form action="{{ route('user.change_email') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="form-group">
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="email" name="email" value="{{$user->email}}">
                        </div>

                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <button type="submit" class="col-sm-4 btn btn-primary">Zapisz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- The TEXT editor Modal -->
    <div id="aboutModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <span class="close">&times;</span>
            </div>
            <div class="row">
                <form method="post" action="{{ route('user.change_about') }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label>opis</label>
                        <textarea class="form-control tinymce-editor {{ $errors->has('about') ? 'error' : '' }}" name="about" id="about"
                            rows="4">
                            {!! $user->about !!}
                        </textarea>
                        @if ($errors->has('about'))
                        <div class="error">
                            {{ $errors->first('about') }}
                        </div>
                        @endif
                    </div>
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <input type="submit" name="send" value="Zapisz" class="btn btn-dark btn-block">
                </form>
            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div id="personalModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="row">
                <span class="close">&times;</span>
            </div>
            <div class="row">
                <form method="post" action="{{ route('user.update_personal') }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label for="user_title_id">tytuł:</label><br>
                            <select class="form-control" id="user_title_id" name="user_title_id">
                            @foreach (App\UserTitle::get() as $row)
                                <option value="{{$row->id}}" <?php if ($row->id == $user->user_title_id) echo 'selected="selected"'; ?>> {{$row->user_title_short}} </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="name">login:</label><br>
                            <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
                        </div>
                        <div class="col-sm-3">
                            <label for="firstname">imię:</label><br>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="{{$user->firstname}}">
                        </div>
                        <div class="col-sm-3">
                            <label for="lastname">nazwisko:</label><br>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="{{$user->lastname}}">
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="col-sm-1 btn btn-primary">Zapisz</button>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of The Modal -->
    <script>

    var change_notify = function() {
        var modal = document.getElementById("simmed_notify");
        checkedValue = $('.simmed_notify:checked').val();
        if (checkedValue)
            to_check=0;
        else
            to_check=1;

        let _token   = '{{csrf_token()}}';
        $.ajax({
        url: "/ajax/user-notify",
        type:"POST",
        data:{
            simmed_notify:to_check,
            user_id:{{$user->id}},
            _token: _token
        },
        success:function(response){
            console.log(response);
            if(response) {
            $('.success').text(response.success);
            //$("#ajaxform")[0].reset();
            //alert(response.success);
            }
        },
        });

        };
    </script>

    <!-- for modals -->
    <script>
    var openPhoneModal = function(id_phone,type,phone,phone_for_coordinators,phone_for_technicians,phone_for_trainers,phone_for_guests,phone_for_anonymouse) {
        document.getElementById('id_phone').value=id_phone;
        document.getElementById('phone_number').value=phone;
        document.getElementById("phone_for_coordinators").checked =  Boolean(Number(phone_for_coordinators));
        document.getElementById("phone_for_technicians").checked =  Boolean(Number(phone_for_technicians));
        document.getElementById("phone_for_trainers").checked =  Boolean(Number(phone_for_trainers));
        document.getElementById("phone_for_guests").checked =  Boolean(Number(phone_for_guests));
        document.getElementById("phone_for_anonymouse").checked =  Boolean(Number(phone_for_anonymouse));
        modal.style.display = "block";
        var sel = document.getElementById('user_phone_type_id');
        var opts = sel.options;
            for (var opt, j = 0; opt = opts[j]; j++) {
                if (opt.value == type) {
                    sel.selectedIndex = j;
                break;
                }
            }
        };

    var openMailModal = function() {
        modal2.style.display = "block";
        };

    var openAboutModal = function() {
        modal3.style.display = "block";
        };

    var openPersonalModal = function() {
        modal4.style.display = "block";
        };

    var modal = document.getElementById("phoneModal");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    var modal2 = document.getElementById("mailModal");
    // Get the <span> element that closes the modal
    var span2 = document.getElementsByClassName("close")[1];
    // When the user clicks on <span> (x), close the modal
    span2.onclick = function() {
      modal2.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal2) {
        modal2.style.display = "none";
      }
    }

    var modal3 = document.getElementById("aboutModal");
    // Get the <span> element that closes the modal
    var span3 = document.getElementsByClassName("close")[2];
    // When the user clicks on <span> (x), close the modal
    span3.onclick = function() {
      modal3.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal3) {
        modal3.style.display = "none";
      }
    }

    var modal4 = document.getElementById("personalModal");
    // Get the <span> element that closes the modal
    var span4 = document.getElementsByClassName("close")[3];
    // When the user clicks on <span> (x), close the modal
    span4.onclick = function() {
      modal4.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal4) {
        modal4.style.display = "none";
      }
    }

    </script>


<!-- for txt editor -->
<script src="https://cdn.tiny.cloud/1/sibtmr4zcxb4i4bhqtixk6pvvx0eww5nty90bo71i9n19liq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script type="text/javascript">
  tinymce.init({
  selector: 'textarea.tinymce-editor',
  height: 500,
  menubar: true,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'charmap subscript superscript | ' +
  'removeformat | help',
  content_css: '//www.tiny.cloud/css/codepen.min.css'
});
    </script>

        @endif
    </div>




@endsection

