@extends('layouts.backend')

@section('css')
    <link type="text/css" rel="stylesheet" href="{{asset('jsgrid/dist/jsgrid.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('jsgrid/dist/jsgrid-theme.min.css')}}" />
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('jsgrid/dist/jsgrid.min.js')}}"></script>
    <script>
        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",

            @if (session('can_add'))
            inserting: true,
            @else
            inserting: false,
            @endif
            
            @if (session('can_edit'))
            editing: true,
            @else
            editing: false,
            @endif

            
            sorting: true,
            filtering: true,
            selecting:true,
            paging: true,
            autoload: true,
            pagerContainer: null,
            pageIndex: 1,
            pageSize: 20,
            pageButtonCount: 20,
            pageLoading: true,
            confirmDeleting: false,
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "{{route('group.show')}}",
                        data: filter
                    });
                },

                insertItem: function(item) {
                    return $.ajax({
                        type: "POST",
                        url: "{{route('group.store')}}",
                        data: item,
                        dataType: 'json',
                    }).done(function (response) {
                        popup("Berhasil",response.message,'success');
                    }).fail(function(xhr, status, error) {
                        popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
                    });
                },

                updateItem: function(item) {
                    return $.ajax({
                        type: "Put",
                        url: "{{route('group.update')}}",
                        data: item
                    }).done(function (response) {
                        popup("Berhasil",response.message,'success');
                    }).fail(function(xhr, status, error) {
                        popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
                    });
                },

                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "{{route('group.destroy')}}",
                        data: item
                    }).done(function (response) {
                        popup("Berhasil",response.message,'success');
                    }).fail(function(xhr, status, error) {
                        popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
                    });
                },
            },
            onItemInserted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemUpdated: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemDeleting: function (args) {
                if (!args.item.deleteConfirmed) { // custom property for confirmation
                    args.cancel = true;
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Anda tidak dapat mengembalikan item yang telah dihapus!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Lanjutkan Hapus.'
                        }).then((result) => {
                        if (result.value) {
                            args.item.deleteConfirmed = true;
                            $("#jsGrid").jsGrid("deleteItem",args.item);
                        }
                    })
                }
            },
            onItemDeleted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            fields: [{
                    name: "id",
                    title: "ID Roles",
                    align : "center",
                    type: "text",
                    width: 30,
                    readOnly: true
                },
                {
                    name: "name",
                    title: "Nama Roles",
                    align : "left",
                    type: "text",
                    width: 80,
                    validate: "required"
                },
                {
                    name: "description",
                    title: "Deskripsi Roles",
                    align : "left",
                    type: "textarea",
                    width: 200
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        var ebuton = '';
                        var dbuton = '';
                        @if (session('can_edit'))
                            ebuton = '<input class="jsgrid-button jsgrid-edit-button" type="button" title="Edit">&nbsp;&nbsp;<i class="fas fa-align-justify" style="color:green" title="Permission"></i>&nbsp;&nbsp;'
                        @endif
                        @if (session('can_delete'))
                            dbuton = '<input class="jsgrid-button jsgrid-delete-button" type="button" title="Delete">'
                        @endif
                        var editDeleteBtn = $(ebuton+dbuton)
                            .on('click', function (e) {
                                if (e.target.title == 'Edit') {
                                } else if (e.target.title == 'Delete'){
                                    e.stopPropagation();
                                    $("#jsGrid").jsGrid("deleteItem",item);
                                }else if(e.target.title == 'Permission')
                                {
                                    e.stopPropagation();
                                    modal_permission(item);
                                }
                            });
                        
                        return editDeleteBtn; //
                    },
                    
                }
            ]
        });

    </script>

    <script>
        function modal_permission(itemx)
        {
            $.ajax({
            type: "POST",
            url: "{{route('group.permission')}}",
            data: itemx,
            dataType: "html",
            }).done(function (response) {
                $("#modal_edit").modal('show');
                $("#body_edit").html(response);
            }).fail(function(xhr, status, error) {
                popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
            });
        }

        function insertfun(bt) {
        $.ajax({
            type: "POST",
            url: "{{route('group.updatepermission')}}",
            data: $("#forminsert").serialize(),
            dataType: "json",
            beforeSend: function(){
                bt.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                bt.disabled = true;
            }
        }).done(function (response) {
            $("#modal_edit").modal('hide');
            $("#body_edit").empty();

            popup("Berhasil",response.message,'success');
            $("#jsGrid").jsGrid("loadData");
        }).fail(function(xhr, status, error) {
            bt.innerHTML = 'Update';
            bt.disabled = false;
            popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
        });
    }
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="jsGrid"></div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <button onclick="exportToCsv('jsGrid','{{isset($title) ? $title.' | ' : 'Export File'}}')" class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Export to CSV</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <div class="modal fade" id="modal_edit" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="body_edit">
            
            </div>
        </div>
    </div>

@endsection
