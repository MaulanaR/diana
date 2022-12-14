@extends('layouts.backend')

@section('css')
    <link type="text/css" rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" />
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            $('#student_table').DataTable();
        });

        function save() {
            var formData = new FormData($('#forminsert')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('classes.store') }}",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(response) {
                    popup("Informasi", response.message, 'success',
                        '{{ Request::segment(1) . '/' . Request::segment(2) }}');
                },
                error: function(xhr, textStatus, error) {
                    popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
                }
            });
        }

        function getMajor(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('app/ajax/get_majors') }}/" + id,
                dataType: "json",
                success: function(response) {
                    $("#major_id").empty();
                    $("#major_id").append('<option value="">-- Select Major --</option>')
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        $("#major_id").append(
                            '<option value="' + valueOfElement.id + '">' + valueOfElement
                            .name + '</option>'
                        );
                    });
                    $("#major_id").selectpicker('refresh')
                },
                error: function(xhr, textStatus, error) {
                    popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
                }
            });
        }

        function getStudents(id) {
            $.ajax({
                type: "GET",
                url: "{{ url('app/ajax/get_students') }}/" + id,
                dataType: "json",
                success: function(response) {
                    $("#listStudents").empty();
                    $.each(response.data, function(indexInArray, v) {
                        $("#listStudents").append(
                            `<tr>
                                <td class="text-center">
                                    <input type="checkbox" name="students[]" value="` + v.id + `">
                                </td>
                                <td>` + v.nik + `</td>
                                <td>` + v.nim + `</td>
                                <td>` + v.full_name + `</td>
                            </tr>`
                        );
                    });
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

                <!-- /.card-header -->
                <div class="card-body">
                    <form action="#" id="forminsert" class="form-horizontal" name="formnih" autocomplete="off">
                        <div class="form-body">
                            <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                            <div class="form-group">
                                <label class="control-label">Periode Akademik *</label>
                                <select class="sel form-control" name="academic_period_id" onchange="getMajor(this.value)">
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach ($academic_period as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Jurusan *</label>
                                <select class="sel form-control" name="major_id" id="major_id"
                                    onchange="getStudents(this.value)">

                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Code *</label>
                                <input type="text" name="code" class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Nama *</label>
                                <input type="text" name="name" class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Deskripsi</label>
                                <textarea name="description" id="" rows="5" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <div class="card-body" style="border:2px solid black">
                                    <label class="control-label">Daftar Mahasiswa</label>
                                    <table class="table table-striped table-bordered" id="student_table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>NIK</th>
                                                <th>NIM</th>
                                                <th>FullName</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listStudents">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <button type="button" class="btn btn-default"
                                    onclick="window.history.back(-1)">Batal</button>
                                <button type="button" class="btn btn-primary" onclick="save()">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection
