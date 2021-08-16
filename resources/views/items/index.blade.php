@extends('layouts.app')

<link href="{{ asset('css/device.css') }}" rel="stylesheet">
@section('title', " zasoby - ".$type_name )

@section('content')



@if (Auth::user()->hasRole('MagazynierX'))
    @if ( Auth::user()->CenterRole('Magazynier','CS Pielęgniarstwo') )
    echo "1,1";
    @endif
<a class="glyphicon glyphicon-plus-sign glyphiconbig pull-right" href="{{route('devices.create')}}"></a>
@endif

@if ($type_name!='wszystko')
<h1>{{$type_name}}: wykaz</h1>
     <?php /*@foreach($items_table as $item) */?>
     @foreach($Items as $item_row)
     <?php $item=App\Item::where('id',$item_row)->get()->first(); ?>
        <a href="{{route('items.show', $item->id)}}">
            <div class="tile">
                <img src="/storage/img/items/{{ $item->photo_OK() }}" class="tile">
                
                <div class="tiletitle">
                    {{ $item->group()->item_group_name }}
                </div>
            </div>
        </a>
    @endforeach
@else

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">



<!--table class="table table-hover"-->


    <h1>wykaz wszystkich elementów</h1>
    <div class="clearfix"></div>
    <table id="exatwo" class="display" style="width:100%"
data-sort-priority='[{"sortName": "lp","sortOrder":"desc"},{"sortName":"Room","sortOrder":"desc"}]'>

        <thead>
        <tr>
            <th data-field="lp" data-sortable="true">
                lp
            </th>
            <th data-sortable="false">
                fotka
            </th>
            <th>
                nazwa
            </th>
            <th>
                model
            </th>
            <th>
                opis
            </th>
            <th>
                nr inw.
            </th>
            <th data-field="Room" data-sortable="true">
                sala
            </th>
            <th>
                miejsce
            </th>
        </tr>
        </thead>
       
    {{$i=1}}
    @foreach($Items as $item)
        <tr>
        <td>
            {{$i++}}
        </td>
        <td>
            <a href="{{route('items.show', $item->id)}}" target="_new">
            <div class="tile">
                <img src="/storage/img/items/{{ $item->photo_OK() }}" class="tile">
            </a>
        </td>
        <td>
            {{ $item->group()->item_group_name }}
        </td>
        <td>
            {{ $item->group()->item_group_model }}
        </td>
        <td>
            {{ $item->item_description }}
        </td>
        <td>
            {{ $item->item_inventory_number }}
        </td>
        <td>
            
                        
                <strong>{{$item->storage()->room()->room_number}}</strong>
                {{$item->storage()->room()->room_name}}
  
            @if ($item->room_storage_id != $item->room_storage_current_id)
                <br><strong>pożyczony do:</strong><br>
                <strong>{{$item->current_storage()->room()->room_number}}</strong>
                {{$item->current_storage()->room()->room_name}}
            @endif
        </td>
        
        <td>
            @if ($item->room_storage_id == $item->room_storage_current_id)
                {{$item->storage()->room_storage_name}}
                @if (($item->storage()->room_storage_shelf_count)>1)
                    poziom {{$item->item_storage_shelf}}
                @endif
            @else
                {{$item->current_storage()->room_storage_name}}

                
            @endif
        
            
        </td>
        

    </tr>
    @endforeach
    <tfoot>
        <tr>
            <th>
                lp
            </th>
            <th>
                fotka
            </th>
            <th>
                nazwa
            </th>
            <th>
                model
            </th>
            <th>
                opis
            </th>
            <th>
                nr inw.
            </th>
            <th>
                sala
            </th>
            <th>
                miejsce
            </th>
        </tr>
        </tfoot>
</table>

<!-- scripts for Datatables -->
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/multiple-sort/bootstrap-table-multiple-sort.js"></script>
  <script>
    $(document).ready(function() {
      $('#exatwo').DataTable( {
        "language": {
            "lengthMenu": "Wyświetl _MENU_ wyników na stronę",
            "search": "Szukaj:",
            "paginate": {
        "first": "Pierwsza",
        "previous": "Poprzednia",
        "next": "Następna",
        "last": "Ostatnia"
    },
            "zeroRecords": "Niestety - nic nie znaleziono",
            "info": "Pokazuję stronę _PAGE_ z _PAGES_",
            "infoEmpty": "Żadne dane nie są dostęne",
            "infoFiltered": "(przefiltorwano z _MAX_ rekordów ogółem)"
        }
    } );
  } );
  </script>
<!-- /scripts for Datatables -->
@endif     


@endsection