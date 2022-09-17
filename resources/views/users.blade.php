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
                        url: "{{route('users.show')}}",
                        data: filter
                    });
                },

                insertItem: function(item) {
                },

                updateItem: function(item) {
                },

                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "{{route('users.destroy')}}",
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
            fields: [
                {
                    name: "id",
                    title: "ID User",
                    align : "center",
                    type: "text",
                    sorttype: 'int',
                    visible: true,
                    width: 20,
                },
                {
                    name: "name",
                    title: "Nama Lengkap",
                    align : "left",
                    type: "text",
                    visible: false,
                    width: 100,
                    readOnly: false
                },
                {
                    name: "username",
                    title: "Username",
                    align : "left",
                    type: "text",
                    width: 100,
                    readOnly: false
                },
                // {
                //     name: "email",
                //     title: "Email",
                //     align : "left",
                //     type: "text",
                //     width: 100,
                //     readOnly: false
                // },
                {
                    name: "group",
                    title: "Group",
                    align : "center",
                    type: "text",
                    width: 50,
                    validate: "required"
                },
                {
                    name: "picture",
                    title: "Avatar",
                    align : "center",
                    type: "text",
                    width: 30,
                    readOnly: true,
                    itemTemplate: function(value, item) {
                        if(item.picture){
                            return '<img src="{{asset('avatar')}}/'+ item.picture +'" "width="30" height="30">';
                        }
                        return '';
                    }
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        var ebuton = '';
                        var dbuton = '';
                        @if (session('can_edit'))
                            ebuton = '<input class="jsgrid-button jsgrid-edit-button" type="button" title="Edit">'
                        @endif
                        @if (session('can_delete'))
                            dbuton = '<input class="jsgrid-button jsgrid-delete-button" type="button" title="Delete">'
                        @endif
                        var editDeleteBtn = $(ebuton+dbuton)
                            .on('click', function (e) {
                                if (e.target.title == 'Edit') {
                                    e.stopPropagation();
                                    modal_edit(item);
                                } else if (e.target.title == 'Delete'){
                                    $("#jsGrid").jsGrid("deleteItem",item);
                                }
                            });
                        
                        return editDeleteBtn; //
                    },
                    @if (session('can_add'))
                    headerTemplate: function(value, item) {
                        var editDeleteBtn = $('<button type="button" class="btn btn-sm btn-success">+ Tambah</button>')
                            .on('click', function (e) {
                                modal_add();
                            });
                        return editDeleteBtn; //
                    },
                    @endif
                }
            ]
        });

    </script>

    <script>
    function modal_edit(itemedit)
    {
        $.ajax({
            type: "POST",
            url: "{{route('users.edit')}}",
            data: itemedit,
            dataType: "html",
        }).done(function (response) {
            $("#modal_edit").modal('show');
            $("#body_edit").html(response);
        }).fail(function(xhr, status, error) {
            popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
        });
    }

    function updatefun(bt) {
        var formData = new FormData($('#formupdate')[0]);

        $.ajax({
            type: "POST",
            url: "{{route('users.update')}}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
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
            bt.innerHTML = 'Save changes';
            bt.disabled = false;
            popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
        });
    }

    function modal_add(){
        $.ajax({
            type: "GET",
            url: "{{route('users.modal')}}",
            dataType: "html",
        }).done(function (response) {
            $("#modal_edit").modal('show');
            $("#body_edit").html(response);
        }).fail(function(xhr, status, error) {
            popup("Perhatian",xhr.responseJSON.message,'warning',false,3000);
        });
    }

    function insertfun(bt) {
        var formData = new FormData($('#forminsert')[0]);
        
        $.ajax({
            type: "POST",
            url: "{{route('users.store')}}",
            data: formData,
            dataType: "json",    
            contentType: false,
            processData: false,
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
            bt.innerHTML = 'Save';
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
