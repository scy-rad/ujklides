<?php
if (!( (Auth::user()->hasRoleCode('coordinators'))
|| (Auth::user()->hasRoleCode('itemoperators'))
|| (Auth::user()->hasRoleCode('technicians')) ))
        return view('error',['head'=>'błąd wywołania widoku Libraries Galleries','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Koordynatorem, Operatorem Zasobów lub Technikiem']);
?>

@extends('layouts.app')

<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>

@section('title', " Zarządzaj galerią...")

@section('content')

@include('layouts.success_error')

<h4>Zarządzenie galerią:</h4>
<h1>{{$gallery->gallery_name}}</h1>
<h3>{{$gallery->gallery_description}}</h3>


<h4>galeria przypisana do grup:</h4>
<ul>
@foreach ($gallery->forgroups()->get() as $gal_one)
  <li>{{$gal_one->item_group_id}}</li>
@endforeach
</ul>

<h4>galeria przypisana do egzemplarzy:</h4>
<ul>
@foreach ($gallery->foritems()->get() as $gal_one)
  <li>{{$gal_one->item_id}}</li>
@endforeach
</ul>

<h4>galeria przypisana do sali:</h4>
<ul>
@foreach ($gallery->forrooms()->get() as $gal_one)
  <li>{{$gal_one->room_id}}</li>
@endforeach
</ul>


@if (Auth::user()->hasRoleCode('itemoperators')) <?php // edycja tylko dla Operatora Zasobów ?>
<button class="btn btn-primary" data-toggle="modal" data-target="#EditModal">Edycja galerii</button>
<button type="button" class="btn btn-info" onClick="javascript:showEditPhotoModalForm('0')">dodaj zdjęcie</button>
@endif

<?php $gallery->make_wonderfull_gallery('with_edit'); ?>

@if (Auth::user()->hasRoleCode('itemoperators')) <?php // edycja tylko dla Operatora Zasobów ?>


<div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EditModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja galerii</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_gallery') }}" method="post">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for"gallery_name">nazwa:</label>
            <input type="text" class="form-control" id="gallery_name" name="gallery_name" value="{{$gallery->gallery_name}}" required>

            <label for"gallery_description">opis:</label>
            <input type="text" class="form-control" id="gallery_description" name="gallery_description" value="{{$gallery->gallery_description}}" required>

            <label for"gallery_sort">sortowanie:</label>
            <input type="number" min="1" max="250" class="form-control" id="gallery_sort" name="gallery_sort" value="{{$gallery->gallery_sort}}" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">[ Anuluj ]</button>
        <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
        <input type="hidden" id="action" name="action" value="basic">
        <input type="hidden" id="id" name="id" value="{{$gallery->id}}">
        {{ csrf_field() }}
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Body Modal PhotoModal -->
<div id="PhotoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="EPhotoModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalTitle">edycja zdjęcia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('libraries.save_gallery') }}" method="post">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <img id="photo_look" width="300px" height="175px" src="">
            <input type="text" class="form-control" id="directory_file" name="directory_file" value="" required>
            <a href="javascript:open_popup('/js/filemanager/filemanager/dialog.php?type=1&popup=1&field_id=directory_file&fldr=images')" class="btn btn-secondary" type="button" margin="0px">
                <button type="button" class="btn btn-success col-sm-12">zmień zdjęcie</button>
            </a>
            <div class="clearboth"></div>
            <label for"gallery_photo_title">tytuł:</label>
            <input type="text" class="form-control" id="gallery_photo_title" name="gallery_photo_title" value="" required>

            <label for"gallery_photo_description">opis:</label>
            <input type="text" class="form-control" id="gallery_photo_description" name="gallery_photo_description" value="" required>

            <label for"gallery_photo_sort">sortowanie:</label>
            <input type="number" min="1" max="250" class="form-control" id="gallery_photo_sort" name="gallery_photo_sort" value="1" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">[ Anuluj ]</button>
        <button type="submit" class="btn btn-primary">[ Zapisz ]</button>
        <input type="hidden" id="action" name="action" value="photo">
        <input type="hidden" id="gallery_id" name="gallery_id" value="{{$gallery->id}}">
        <input type="hidden" id="photo_id" name="photo_id" value="">
        {{ csrf_field() }}
        </form>
      </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /Modal PhotoModal -->

<!-- Body Modal Responsive File Manager -->
<div class="modal fade" id="myModal" style="width:700px; margin-left: -350px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Przeglądarka plików</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 700px;">
                <iframe width="700" height="400" src="/js/filemanager/filemanager/dialog.php?type=2&field_id=fieldID4'&fldr=" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /Modal Responsive File Manager -->

<?php   //zmienne do modalpicture 
        // $action    = route('item.update', $item->id);
        // $photo_old = asset($item->item_photo);
        $picture_name = 'directory_file';
        $picture_name_img = 'photo_look';
    ?>
@include('pliks.modaljs')

<script>

function showEditPhotoModalForm(photo_id) {
    $.ajax({
        url:"{{ route('libraries.ajx_photo') }}",
        type:"POST",
        data: {
            photo_id: photo_id
            },
        success:function (data) {
            // alert(JSON.stringify(data, null, 4));
            // console.log(JSON.stringify(data, null, 4));
            // $('#item_storage_shelf').empty();
            document.getElementById("photo_look").src=data.gallery_photo_directory+'/'+data.gallery_photo_name;
            $('#directory_file').val(data.gallery_photo_directory+'/'+data.gallery_photo_name);
            $('#gallery_photo_title').val(data.gallery_photo_title);
            $('#gallery_photo_description').val(data.gallery_photo_description);
            $('#gallery_photo_sort').val(data.gallery_photo_sort);
            $('#photo_id').val(photo_id);
    
        }
    })
    $('#PhotoModal').modal('show');   
}
</script>

<!-- scripts for Ajax working technology -->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<!-- /scripts for Ajax working technology -->

@endif

@endsection