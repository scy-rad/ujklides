<?php
// variables in modal:
// $action            = route('item.update', $item->id)
// $photo_old         = asset($item->item_photo)
// $picture_name      = name of database field
// $picture_name_img  = name of img element (preview new image)
?>
<!-- Body Modal change picture -->
<div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="pictureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pictureModalLabel">Zmiana obrazka...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>    <!-- modal-header -->
      <div class="modal-body">
      <form method="post" action="{{$action}}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="update" value="picture">
            {{ csrf_field() }}
            <input type="hidden" name="{{$picture_name}}" id="{{$picture_name}}" value="" >

            <div style="width:200px; float: left">
                <label for="picture_old">aktualne zdjęcie:</label><br>
                <img id="picture_old" width="200px" height="150px" src="{{$photo_old}}">
            </div>
            <div style="width:45%; float: right">
                <label for="{{$picture_name_img}}">nowe zdjęcie:</label><br>
                <a href="javascript:open_popup('/js/filemanager/filemanager/dialog.php?type=1&popup=1&field_id={{$picture_name}}&fldr=images')" class="btn btn-secondary" type="button" margin="0px">
                    <img id="{{$picture_name_img}}" width="200px" height="150px" src="">
                </a>
            </div>
            <div style="clear: both;">
      </div>



      </div> <!-- modal-body -->

      <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
            <button type="submit" class="btn btn-primary">zapisz</button>
      </div>    <!-- modal-footer -->
        </form>
    </div>  <!-- /modal-content -->
  </div>    <!-- /modal-dialog -->
</div>      <!-- /modal fade -->
<!-- /Modal change picture-->



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
