<div class="row">
    <div class="col-sm-10">
        <h2>{{$plik->plik_title}}</h2>
        <h4>{{$plik->plik_directory}} <strong>{{$plik->plik_name}}</strong></h4>
        <p>{{$plik->plik_description}}</p>
    </div>
    <div class="col-sm-2">
        @if ( (Auth::user()->hasRoleCode('itemoperators')) )
            <button type="button" class="btn btn-success btn-outline-primary btn btn-block" data-toggle="modal" data-target="#fileModal">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                edytuj plik
            </button>
        @endif
    </div>
</div>

<iframe src="{{asset($plik->plik_directory.$plik->plik_name)}}" style="width: 100%; box-sizing: border-box;  height: calc(100% - 55px);border: 1px solid #000;">Wystąpił błąd</iframe>
<?php // UPDATE `plik_for_groupitems` SET `plik_directory` = concat('/storage/pliki/',`plik_directory`) WHERE `plik_for_groupitems`.`id` > 0; ?>