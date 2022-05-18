<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="./favicon.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--title>{{ config('app.name', 'UJKlides by SD') }}</title-->
    <title>UJKlides: @yield('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!--Bootshape-->
    <link href="{{ asset('css/bootshape.css') }}" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link href="{{ asset('css/ujklides.css') }}" rel="stylesheet">

    @yield('add_styles')

    <!--Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Belgrano|Courgette&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


    <!-- Scripts -->
    <!-- rozwala menu script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script><!-- added 2021-05-09 -->
    <!-- bo wcześniejsze wyciąłem... script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script-->
    <script src="{{ asset('js/app.js') }}"></script>




</head>
<body>
    <div id="app">
        @guest
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
        @else
        @if (Auth::user()->hasRole('Administrator'))
        <nav class="navbar navbar-default navbar-static-top" style="background: red">
            <div class="container" style="background: yellow">
        @else
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
        @endif
        @endguest
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('img/cmscsm/csm_logo.svg') }}" width="30%">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @guest
                            &nbsp;
                        @else
                            <!--li class="active"><a href="#">Start</a></li-->
                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Zasoby <b class="caret"></b></a>   
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('rooms.index') }}">Sale</a></li>
                                    <br>
                                    <li><a href="{{ route('itemtypes.index',0) }}">Sprzęt</a></li>
                                    <br>


                                    <?php /* @foreach (App\ItemType::MenuTypes() as $MenuType)
                                        <li><a href="{{ route('items.index',['item_group' => $MenuType->item_type_code]) }}">{{$MenuType->item_type_name}}</a></li>
                                    @endforeach
                                        <li><a href="/items/wszystko">wszystko</a></li>
                                    */ ?>
                                    <?php /* @if (Auth::user()->CenterRole('Operator Zasobów','CSM-Lek')) */ ?>
                                    @if (Auth::user()->hasRole('Operator Zasobów'))
                                        <br>
                                        <li style="background-color: #FF0;"><a href="/ManItem">zarządzanie</a></li>
                                    @endif

                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Symulacje <b class="caret"></b></a>   
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('simmeds.index') }}">Bieżące (+7d)</a></li>
                                    <!--li><a href="{{ route('simmeds.index',['route' => 'month']) }}">Bieżący miesiąc</a></li-->
                                    @if ( (Auth::user()->hasRole('Technik')) || (Auth::user()->hasRole('koordynator'))|| (Auth::user()->hasRole('Operator Kadr')) || (Auth::user()->hasRole('Operator Symulacji')) )
                                    <!--li><a href="{{ route('simmeds.index',['route' => 'all']) }}">Wszystkie</a></li-->
                                    <li><a href="{{ route('simmeds.scheduler',date('Y-m-d')) }}">Dziś (no free)</a></li>
                                    <!--li><a href="{{ route('simmeds.timetable') }}">Terminarz</a></li-->
                                    @endif
                                    @if ( (Auth::user()->hasRole('Technik')) || (Auth::user()->hasRole('Operator Symulacji')) )
                                    <li><a href="{{ route('simmeds.plane') }}">Planowanie</a></li>
                                    @endif
                                    @if ( (Auth::user()->hasRole('Technik')) || (Auth::user()->hasRole('Operator Kadr'))  || (Auth::user()->hasRole('Koordynator')) )
                                    <br>
                                    <li><a href="{{ route('worktime.month') }}">Czas pracy</a></li>
                                    <li><a href="{{ route('worktime.statistics') }}">Statystyki</a></li>
                                    @endif
                                    <br>
                                    <li><a href="{{ route('scenarios.index') }}">Scenariusze</a></li>


                                    <?php /* @if (Auth::user()->CenterRole('Operator Symulacji','CSM-Piel')) */ ?>
                                    @if (Auth::user()->hasRole('Operator Symulacji'))
                                    <br>
                                    <li style="background-color: #FF0;"><a href="{{ route('mansimmeds.index') }}">zarządzanie</a></li>
                                    @endif
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Personel <b class="caret"></b></a>   
                                <ul class="dropdown-menu">

                                    @foreach (App\Roles::get() as $row)
                                    @if ($row->roles_code!='')
                                    <li><a href="/users/{{$row->roles_code}}">{{$row->roles_names}}</a></li>
                                    @endif
                                    @endforeach
                                    @if (Auth::user()->hasRole('Operator Kadr'))
                                    <br>
                                    <div style="background-color: #FF0;">
                                    <li><a href="/users/everybody">Wszyscy</a></li>
                                    <li><a href="{{ route('libraries.workmonths') }}">ustal czas pracy </a></li>
                                    <li><a href="#">zarządzanie</a></li>
                                    </div>
                                    @endif
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Dokumenty <b class="caret"></b></a>   
                                <ul class="dropdown-menu">

                                    @foreach (App\PlikType::get() as $row)
                                    @if ($row->plik_type_menu_code!='')
                                    <li><a href="/pliki/{{$row->plik_type_menu_code}}">{{$row->plik_type_menu}}</a></li>
                                    @endif
                                    @endforeach
                                    <?php /*
                                    wysypało się po przejściu z hostingu na local

                                    @if (Auth::user()->CenterRole('Operator Dokumentacji','CSM-Piel'))
                                    <br>
                                    <li style="background-color: #FF0;"><a href="#">zarządzanie</a></li>
                                    @endif

                                    */ ?>
                                </ul>
                            </li>



                            <li class="dropdown">
                                <a data-toggle="dropdown" href="#" class="dropdown-toggle">Administracja <b class="caret"></b></a>   
                                <ul class="dropdown-menu">

                                    @if (Auth::user()->hasRole('Operator Symulacji'))
                                    <li><a href="{{ route('libraries.student_groups') }}"> Grupy studendckie </a></li>
                                    <li><a href="{{ route('libraries.subjects') }}"> Tematy </a></li>
                                    <li><a href="{{ route('libraries.rooms') }}"> Sale </a></li>
                                    @endif
                                    @if (Auth::user()->hasRole('Operator Kadr'))
                                    <li><a href="{{ route('libraries.user_titles') }}"> Tytuły naukowe </a></li>
                                    <li><a href="{{ route('libraries.workmonths') }}"> Miesięczny czas pracy </a></li>
                                    <li><a href="{{ route('worktime.show_attendances') }}"> Listy obecności </a></li>
                                    
                                    @endif
                                    @if ( (Auth::user()->hasRole('Operator Symulacji'))
                                        || (Auth::user()->hasRole('Operator Kadr'))
                                        || (Auth::user()->hasRole('Administrator'))
                                    )
                                    <li><a href="{{ route('libraries.params_show') }}"> Parametry </a></li>
                                    @endif
                                </ul>
                            </li>


                            

                            <li><a href="#">Kontakt</a></li>

                            <li><a href="http://csmpiel.dudek.net.pl" target="_new">CSM piel</a></li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Logowanie</a></li>
                            <li><a href="{{ route('register') }}">Rejestracja</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                <li>

                                <a href="/userprofile">
                                    profil
                                </a>
                                </li>

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                      @endguest
                    </ul>
                </div>
            </div>
        </nav>


        @yield('content')

    </div>

    <div class="clearfix"> </div>
    <div class="footer">© MMXX <a href="http://dudek.net.pl">dudek.net.pl</a> &nbsp; &nbsp; </div>

</body>
</html>
