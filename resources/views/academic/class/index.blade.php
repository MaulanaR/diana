@extends('layouts.backend')

@section('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('jsgrid/dist/jsgrid.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('jsgrid/dist/jsgrid-theme.min.css') }}" />
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('jsgrid/dist/jsgrid.min.js') }}"></script>
    <script>
        $("#jsGrid").jsGrid({
            width: "100%",
            height: "auto",
            sorting: true,
            filtering: true,
            selecting: true,
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
                        url: "{{ route('classes.show') }}",
                        data: filter
                    });
                },

                insertItem: function(item) {},

                updateItem: function(item) {},

                deleteItem: function(item) {
                    return $.ajax({
                        type: "DELETE",
                        url: "{{ route('classes.destroy') }}",
                        data: item
                    }).done(function(response) {
                        popup("Berhasil", response.message, 'success');
                    }).fail(function(xhr, status, error) {
                        popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
                    });
                },
            },
            onItemInserted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemUpdated: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemDeleting: function(args) {
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
                            $("#jsGrid").jsGrid("deleteItem", args.item);
                        }
                    })
                }
            },
            onItemDeleted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            fields: [{
                    name: "id",
                    title: "ID",
                    align: "center",
                    type: "text",
                    visible: false,
                    width: 20,
                },
                {
                    name: "academic_period_name",
                    title: "Periode Akademik",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                },
                {
                    name: "major_name",
                    title: "Jurusan",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                },
                {
                    name: "code",
                    title: "Code",
                    align: "center",
                    type: "text",
                    width: 30,
                    readOnly: false,
                },
                {
                    name: "name",
                    title: "Nama",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "description",
                    title: "Deskripsi",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "total_student",
                    title: "Total Mahasiswa",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: true,
                    validate: "required"
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        var ebuton = '';
                        var dbuton = '';
                        @if (session('can_edit'))
                            ebuton = '<i class="fas fa-pen" style="color:blue" title="Edit"></i>'
                        @endif
                        @if (session('can_delete'))
                            dbuton = ' <i class="fas fa-trash" style="color:red" title="Delete"></i> '
                        @endif
                        var editDeleteBtn = $(ebuton + dbuton)
                            .on('click', function(e) {
                                if (e.target.title == 'Edit') {
                                    e.stopPropagation();
                                    modal_edit(item);
                                } else if (e.target.title == 'Delete') {
                                    $("#jsGrid").jsGrid("deleteItem", item);
                                }
                            });

                        return editDeleteBtn; //
                    },
                    @if (session('can_add'))
                        headerTemplate: function(value, item) {
                            var editDeleteBtn = $(
                                    '<button type="button" class="btn btn-sm btn-success">+ Tambah</button>'
                                    )
                                .on('click', function(e) {
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
        function modal_edit(itemedit) {
            window.location.href = "{{ route('classes.edit') }}" + "/" + itemedit.id;
        }

        function modal_add() {
            window.location = "{{ route('classes.modal') }}";
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
                            <button onclick="exportToCsv('jsGrid','{{ isset($title) ? $title . ' | ' : 'Export File' }}')"
                                class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Export to CSV</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

    <div class="modal fade" id="modal_edit" tabindex="" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="body_edit">

            </div>
        </div>
    </div>
@endsection
