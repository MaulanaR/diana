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
                    filter.academic_period_id = $("#academic_period_id").val()
                    filter.major_id = $("#major_id").val()
                    filter.class_id = $("#class_id").val()
                    console.log(filter)
                    return $.ajax({
                        type: "GET",
                        url: "{{ route('transkip.show') }}",
                        data: filter
                    });
                },

                insertItem: function(item) {},

                updateItem: function(item) {},

                deleteItem: function(item) {},
            },
            onItemInserted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemUpdated: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            onItemDeleting: function(args) {},
            onItemDeleted: function(args) {
                $("#jsGrid").jsGrid("loadData");
            },
            fields: [{
                    name: "id",
                    title: "id",
                    align: "center",
                    type: "text",
                    visible: false,
                    width: 20,
                },
                {
                    name: "academic_period_name",
                    title: "Tahun Akademik",
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
                    name: "class_name",
                    title: "Kelas",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                },
                {
                    name: "nim",
                    title: "NIM",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    name: "full_name",
                    title: "Nama",
                    align: "center",
                    type: "text",
                    width: 50,
                    readOnly: false,
                    validate: "required"
                },
                {
                    type: "control",
                    itemTemplate: function(value, item) {
                        let startDiv = '<div class="btn-group-vertical">'
                        let endDiv = '</div>'
                        var eButton = '';
                        var dButton = '';
                        eButton +=
                            '<button type="button" class="btn btn-sm btn-outline-primary text-left" title="CetakGanjil"><i class="fas fa-book"></i> Cetak Transkrip</button>'
                        // eButton += '<button type="button" class="btn btn-sm btn-outline-primary text-left" title="CetakGenap"><i class="fas fa-book"></i> Cetak Transkrip <br> Semester Genap</button>'

                        let editDeleteBtn = $(startDiv + eButton + dButton + endDiv)
                            .on('click', function(e) {
                                if (e.target.title == 'CetakGanjil') {
                                    e.stopPropagation();
                                    window.location.href = "{{ route('transkip.cetak') }}" + "/" + item
                                        .class_academic_period_id + "/" + item.class_id + "/" + item
                                        .student_id + "/1";
                                }
                            });

                        return editDeleteBtn; //
                    },
                }
            ]
        });
    </script>

    <script>
        function modal_edit(itemedit) {
            window.location.href = "{{ route('courses.edit') }}" + "/" + itemedit.id;
        }

        function modal_add() {
            window.location = "{{ route('courses.modal') }}";
        }

        function refs() {
            $("#jsGrid").jsGrid("loadData");
        }

        function getMajor(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('app/ajax/get_majors') }}/" + id,
                dataType: "json",
                success: function(response) {
                    $("#major_id").empty();
                    $("#class_id").empty();
                    $("#major_id").append('<option value="">-- SEMUA --</option>');
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        $("#major_id").append(
                            '<option value="' + valueOfElement.id + '">' + valueOfElement
                            .name + '</option>'
                        );
                    });
                    $("#major_id").selectpicker('refresh')
                    $("#class_id").selectpicker('refresh')
                },
                error: function(xhr, textStatus, error) {
                    popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
                }
            });
        }

        function getClass(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('app/ajax/get_classes') }}/" + $("#academic_period_id").val() + "/" + id,
                dataType: "json",
                success: function(response) {
                    $("#class_id").empty();
                    $("#class_id").append('<option value="">-- SEMUA --</option>');
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        $("#class_id").append(
                            '<option value="' + valueOfElement.id + '">' + valueOfElement
                            .name + '</option>'
                        );
                    });
                    $("#class_id").selectpicker('refresh')
                },
                error: function(xhr, textStatus, error) {
                    popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
                }
            });
        }
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-2">
                                <label class="control-label">Periode Akademik :</label>
                            </div>
                            <div class="col-3">
                                <select class="sel form-control" id="academic_period_id" onchange="getMajor(this.value)">
                                    <option value="">-- SEMUA --</option>
                                    @foreach ($periods as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (!$isStudent)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2">
                                    <label class="control-label">Jurusan :</label>
                                </div>
                                <div class="col-3">
                                    <select class="sel form-control" id="major_id" onchange="getClass(this.value)">
                                        <option value="">-- SEMUA --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2">
                                    <label class="control-label">Kelas :</label>
                                </div>
                                <div class="col-3">
                                    <select class="sel form-control" id="class_id">
                                        <option value="">-- SEMUA --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="row">
                            <div class="col-5 text-right">
                                <button type="button" class="btn btn-sm btn-primary" onclick="refs()"><i
                                        class="fas fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
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
