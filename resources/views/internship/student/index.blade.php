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
            pageIndex: 1,
            pageSize: 15,
            pageButtonCount: 5,
            pageLoading: true,
            confirmDeleting: false,
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "{{route('internship_students.show')}}",
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
                        url: "{{route('internship_students.destroy')}}",
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
                        title: 'Hapus Data?',
                        text: "Pastikan anda mengetahui apa yang anda lakukan.",
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
                    title: "ID",
                    align : "center",
                    type: "text",
                    visible: false,
                    width: 20,
                },
                {
                    name: "student_full_name",
                    title: "Nama",
                    align : "left",
                    type: "text",
                    width: 100,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "student_nim",
                    title: "NIM",
                    align : "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "location_name",
                    title: "Tempat Magang",
                    align : "center",
                    type: "text",
                    width: 50,
                    readOnly: true,
                    validate: "required"
                },
                {
                    name: "approval_status",
                    title: "Persetujuan",
                    align : "center",
                    type: "text",
                    width: 50,
                    filtering: true,
                    validate: "required",
                    itemTemplate: function(value, item) {
                        if(item.approval_status == "Menunggu Persetujuan") {
                            return '<span class="right badge badge-secondary">'+item.approval_status+'</span>'
                        } else if(item.approval_status == "Disetujui"){
                            return '<span class="right badge badge-success">'+item.approval_status+'</span>'
                        } else {
                            return '<span class="right badge badge-danger">'+item.approval_status+'</span>'
                        }
                    }
                },
                {
                    name: "status",
                    title: "Status",
                    align : "center",
                    type: "text",
                    width: 50,
                    filtering: true,
                    validate: "required",
                    itemTemplate: function(value, item) {
                        if(item.status == "Aktif") {
                            return '<span class="right badge badge-success">'+item.status+'</span>'
                        } else {
                            return '<span class="right badge badge-info">'+item.status+'</span>'
                        }
                    }
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        let startDiv = '<div class="btn-group-vertical">'
                        let endDiv = '</div>'
                        let eButton = '';
                        let dButton = '';
                        @if (session('can_edit'))
                            @if ($isAdmin)
                                eButton += '<button type="button" class="btn btn-sm btn-outline-info text-left" title="Info"><i class="fas fa-info-circle"></i> Informasi</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-info text-left" title="Edit"><i class="fas fa-edit"></i> Edit Data</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-warning text-left" title="Approval"><i class="fas fa-check"></i> Persetujuan</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-primary text-left" title="Instruktur"><i class="fas fa-user"></i> Instruktur</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Logbook"><i class="fas fa-book"></i> Log Book ('+item.total_logbook+')</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Berkas"><i class="fas fa-book"></i> Berkas Magang</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Nilai"><i class="fas fa-poll"></i> Penilaian</button>'
                            @endif
                            @if ($isInstructor)
                                eButton += '<button type="button" class="btn btn-sm btn-outline-info text-left" title="Info"><i class="fas fa-info-circle"></i> Informasi</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Nilai"><i class="fas fa-poll"></i> Penilaian</button>'
                            @endif
                            @if ($isStudent)
                                eButton += '<button type="button" class="btn btn-sm btn-outline-info text-left" title="Info"><i class="fas fa-info-circle"></i> Informasi</button>'
                                eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Logbook"><i class="fas fa-book"></i> Log Book ('+item.total_logbook+')</button>'
                                if(item.status == "Selesai") {
                                    eButton += '<button type="button" class="btn btn-sm btn-outline-success text-left" title="Berkas"><i class="fas fa-book"></i> Berkas Magang</button>'
                                }
                            @endif
                        @endif
                        @if (session('can_delete'))
                            @if ($isAdmin)
                            dButton += '<button type="button" class="btn btn-sm btn-outline-danger text-left" title="Delete"><i class="fas fa-trash"></i> Delete</button>'
                            @endif
                        @endif
                        let editDeleteBtn = $(startDiv+eButton+dButton+endDiv)
                            .on('click', function (e) {
                                if (e.target.title == 'Edit') {
                                    e.stopPropagation();
                                    modal_edit(item);
                                } else if(e.target.title == 'Info') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('internship_students.info')}}"+"/"+item.id;
                                } else if(e.target.title == 'Instruktur') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('internship_students.instruktur')}}"+"/"+item.id;
                                }else if(e.target.title == 'Berkas') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('internship_students.berkas')}}"+"/"+item.id;
                                }else if(e.target.title == 'Nilai') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('internship_students.nilai')}}"+"/"+item.id;
                                }else if(e.target.title == 'Logbook') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('logbook.index')}}"+"/"+item.id;
                                }else if (e.target.title == 'Delete'){
                                    $("#jsGrid").jsGrid("deleteItem",item);
                                }else if(e.target.title == 'Approval') {
                                    e.stopPropagation();
                                    window.location.href = "{{route('internship_students.approval')}}"+"/"+item.id;
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
        window.location.href = "{{route('internship_students.edit')}}"+"/"+itemedit.id;
    }

    function modal_add(){
        window.location = "{{route('internship_students.modal')}}";
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
@endsection
