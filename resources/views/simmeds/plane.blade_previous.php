@extends('layouts.app')

@section('title', 'symulacje')

@section('content')

<?php

// to próbowałem, ale ajax mi nie zadziałał... :( https://gijgo.com/grid/demos/bootstrap-grid-inline-edit
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.css" rel="stylesheet" type="text/css" />
?>

<?php
// będę próbpwał teraz tego: https://vitalets.github.io/x-editable/index.html
//<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
//<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
?>




<div class="container-fluid">
    <div class="row">
        <div class="col-sm-5">
            <form class="form-inline">
                <input id="txttechnician_name" type="text" placeholder="technik..." class="form-control mb-2 mr-sm-2 mb-sm-0" />
                <input id="txtroom_number" type="text" placeholder="sala..." class="form-control mb-2 mr-sm-2 mb-sm-0" />
                
                <button id="btnSearch" type="button" class="btn btn-default">Search</button> &nbsp;
                <button id="btnClear" type="button" class="btn btn-default">Clear</button>
            </form>
        </div>
        <div class="col-sm-4">
            <?php
            /* ten fragment wykorzystywałem jak był okres miesięczny planowania
            if (date('d',strtotime($sch_date))=='01')
                $date_prev=date('Y-m-d',strtotime("$sch_date - 1 month"));
            else
                $date_prev=date('Y-m',strtotime("$sch_date")).'-01';
            $date_next=date('Y-m',strtotime("$sch_date + 1 month")).'-01';
            */
            $date_prev=date('Y-m-d',strtotime("$sch_date - 7 day"));
            $date_next=date('Y-m-d',strtotime("$sch_date + 7 day"));
            ?>

            <a href="{{ route('simmeds.plane',$date_prev) }}"><button type="button" class="btn btn-default">poprzedni okres od {{$date_prev}}</button></a>
            <strong>{{$sch_date}}</strong>
            <a href="{{ route('simmeds.plane',$date_next) }}"><button type="button" class="btn btn-default">następny okres od {{$date_next}}</button></a>

        </div>
        <div class="col-sm-3">
            <button id="btnAdd" type="button" class="btn btn-default pull-right">Add New Record</button>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-12">
            <table id="grid"></table>
        </div>
    </div>
</div>



<div id="dialog" style="display: none">
    <input type="hidden" id="id" />
    <form>
        <div class="form-row">
            <input type="text" id="simmed_date" placeholder="Data zajęć..." width="100%" />
        </div>
        <div class="form-row">
            <input type="text" id="simmed_time_begin" placeholder="od godziny" width="100%" />
        </div>
        <div class="form-row">
            <input type="text" id="simmed_time_end" placeholder="do godziny" width="100%" />
        </div>

        <div class="form-group">
            <select id="room_id" width="100%" placeholder="sala..."></select>
        </div>
        <div class="form-group">
            <select id="simmed_leader_id" width="100%" placeholder="instruktor..."></select>
        </div>
        <div class="form-group">
            <select id="simmed_technician_id" width="100%" placeholder="technik..."></select>
        </div>
        <div class="form-group">
            <select id="student_group_id" width="100%" placeholder="grupa..."></select>
        </div>
        <div class="form-group">
            <label for="technician_name">uwagi - nie zapisywane jeszcze</label>
            <input type="text" class="form-control" id="simmed_comments" />
        </div>

        

        <button type="button" id="btnSave" class="btn btn-default">Save</button>
        <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
    </form>
</div>



    <script type="text/javascript">
        $(document).ready(function () {
            var grid, countries;
            data = {!!$rows_plane!!};
            list_tech = {!! App\User::json_role_users('technicians', 1, null) !!};
            list_inst = {!! App\User::json_role_users('instructors', 1, null) !!};
            list_room = {!! App\Room::json_room_education() !!}
            
            grid = $('#grid').grid({
                dataSource: data,
                uiLibrary: 'bootstrap4',
                primaryKey: 'id',
                inlineEditing: { mode: 'command' },
                columns: [
                    { field: 'id', width: 44 },
                    { field: 'simmed_date', title: 'data', type: 'date', format: 'yyyy-mm-dd', editor: true },
                    { field: 'simmed_time_begin', title: 'od', type: 'time', format: 'HH:MM', editor: true },
                    { field: 'simmed_time_end', title: 'do', type: 'time', format: 'HH:MM', editor: true },
                    { field: 'room_number', title: 'Numer sali', type: 'dropdown', editField: 'room_id', editor: { dataSource: list_room, valueField: 'id' } },
                    { field: 'technician_name', title: 'technik', type: 'dropdown', editField: 'simmed_technician_id', editor: { dataSource: list_tech, valueField: 'id' } },
                    { field: 'leader_name', title: 'instruktor', type: 'dropdown', editField: 'simmed_leader_id', editor: { dataSource: list_inst, valueField: 'id' } },
                    
                    { field: 'subject', title: 'temat', editor: false },
                    { field: 'group', title: 'grupa', editor: false },
                    { field: 'simmed_status', title: 'Statusik', type: 'checkbox', editor: true, width: 90, align: 'center' }
                ],
                pager: { limit: 10 }
            });
            grid.on('rowDataChanged', function (e, id, record) {
                // Clone the record in new object where you can format the data to format that is supported by the backend.
                let _token   = '{{csrf_token()}}';
                var data = $.extend(true, {}, record);
                
                // Format the date to format that is supported by the backend.
                //data.simmed_date = gj.core.parseDate(record.simmed_date, 'yyyy-mm-dd').toISOString();
                // Post the data to the server

                /*
                data._token = _token;
                $.ajax({ url: 'simmed/ajaxsaveplane', data: { record: data }, method: 'POST' })
                    .fail(function () {
                        console.log(data);
                        //console.log(ajax);
                        alert("Failed to save... Sorry"+data);
                    });
                */
                //alert(data.id);
                $.ajax({
                url: "/simmed/ajaxsaveplane",
                type:"POST",
                data:{
                    //record: data
                    id : data.id,
                    simmed_technician_id : '7',
                    _token: _token
                },
                success:function(response){
                    console.log(data);
                    ///if(response) {
                    ///$('.success').text(response.success);
                    //$("#ajaxform")[0].reset();
                    alert(response.tescik);
                    ///}
                },
                error:function(){
                    console.log(data);
                    alert("Failed to save... Sorry"+data);
                },
                });
                

                
            });
            grid.on('rowRemoving', function (e, $row, id, record) {            
                if (confirm('Are you sure?')) {
                    $.ajax({ url: '/Players/Delete', data: { id: id }, method: 'POST' })
                        .done(function () {
                            grid.reload();
                        })
                        .fail(function () {
                            alert('Failed to delete.');
                        });
                }
            });



            dialog = $('#dialog').dialog({
                uiLibrary: 'bootstrap4',
                iconsLibrary: 'fontawesome',
                autoOpen: false,
                resizable: false,
                modal: true
            });
            //for dialog only
            simmed_date = $('#simmed_date').datepicker();
            simmed_time_begin = $('#simmed_time_begin').timepicker();
            simmed_time_end = $('#simmed_time_end').timepicker();
            room_id = $('#room_id').dropdown({ dataSource: list_room, valueField: 'id' });
            simmed_leader_id = $('#simmed_leader_id').dropdown({ dataSource: list_inst, valueField: 'id' });
            simmed_technician_id = $('#simmed_technician_id').dropdown({ dataSource: list_tech, valueField: 'id' });
            
            $('#btnAdd').on('click', function () {
                $('#id').val('');
                $('#technician_name').val('');
                $('#room_id').val('');
                dialog.open('Add Player');
            });
            $('#btnCancel').on('click', function () {
                dialog.close();
            });


            $('#btnSearch').on('click', function () {
                grid.reload({ technician_name: $('#txttechnician_name').val(), room_number: $('#txtroom_number').val() });
            });
            $('#btnClear').on('click', function () {
                $('#txttechnician_name').val('');
                $('#txtroom_number').val('');
                grid.reload({ technician_name: '', room_number: '' });
            });


        });
    </script>

@endsection


