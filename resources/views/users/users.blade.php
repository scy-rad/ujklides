@extends('layouts.app')

<link href="{{ asset('css/users.css') }}" rel="stylesheet">

@section('content')

<h1>użytkownicy</h1>
@if ($users->count()>0)
<?php /*
@foreach ($users as $user)
<?php if ($user->user_status!=1) $able="danger"; else $able=""; ?>

<div>
      <div class="col-xlg-1 col-lg-2 col-md-3 col-sm-4 col-6 main-section text-center">
            <div class="profile-header">
            </div>
          <div class="user-detail bg-{{$able}}">
                <a href="{{route('user.profile', $user->id)}}">
                  <img src="/storage/avatars/{{ $user->user_fotka }}" class="xrounded-circle ximg-thumbnail">
                </a>
                  <!--p><i class="fa fa-map-marker" aria-hidden="true"></i> UJK Kielce</p-->
                  <h2 class="text-{{$able}}"> {{$user->title->user_title_short}} {{$user->firstname}} {{$user->lastname}}</h5>
                  <!--hr>
                  <a href="#" class="btn btn-success btn-sm">Follow</a>
                  <a href="#" class="btn btn-info btn-sm">Send Messege</a-->
            </div>
            <div class="profile-footer">
                  <hr>
                  @foreach ($user->roles as $row)
                    <p class="btn btn-{{$row->roles_color}} btn-sm user-button">{{$row->roles_name}}</p>                   
                  @endforeach
          </div>
          <!--div class="user-social-detail">
              <div class="col-lg-12 col-sm-12 col-12">
                  <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
              </div>
          </div-->
      </div>

</div>
@endforeach

{{$users->links()}}

*/ ?>

<ol>
@foreach ($users as $user)
<?php if ($user->user_status!=1) $able="danger"; else $able=""; ?>

  <li><span class="bg-{{$able}}"><a href="{{route('user.profile', $user->id)}}"> {{$user->title->user_title_short}} {{$user->firstname}} {{$user->lastname}} </a> </span>
      @foreach ($user->roles as $row)
        [ {{$row->roles_name}} ]                   
      @endforeach
</li>
@endforeach
</ol>

@endif



@endsection