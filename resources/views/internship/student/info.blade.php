@extends('layouts.backend')

@section('css')
    <link type="text/css" rel="stylesheet" href="{{ asset('jsgrid/dist/jsgrid.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('jsgrid/dist/jsgrid-theme.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
@endsection

@section('js')
<script src="{{ asset('daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
    <script>
    function save() {
        var formData = new FormData($('#forminsert')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('internship_students.update') }}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                popup("Informasi", response.message, 'success', '{{Request::segment(1)."/".Request::segment(2)}}');
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
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="modified_by" value="{{Auth::user()->id}}">

                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label">Student</label>
                                @foreach ($students as $student)
                                @if($data->student_id == $student->id)
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" class="form-control" name="student_id" readonly="true" value="{{$student->full_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">NIM</label>
                                        <input type="text" class="form-control" name="student_id" readonly="true" value="{{$student->nim}}">
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group" style="display: true">
                                <label class="sel control-label">Personal Choice</label readonly="true">
                                    @if($data->personal_choice == "1")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Ya">
                                    @endif
                                    @if($data->personal_choice == "0")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Tidak">
                                    @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Location</label>
                                @foreach ($locations as $location)
                                    @if($data->internship_location_id == $location->id)
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="control-label">Nama Tempat</label>
                                            <input type="text" class="form-control" name="internship_location_id" readonly="true" value="{{$location->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Alamat</label>
                                            <input type="text" class="form-control" name="internship_location_id" readonly="true" value="{{$location->address}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Kontak</label>
                                            <input type="text" class="form-control" name="internship_location_id" readonly="true" value="{{$location->pic_contact}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Jabatan Kontak</label>
                                            <input type="text" class="form-control" name="internship_location_id" readonly="true" value="{{$location->pic_position}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">No Telp Perusahaan</label>
                                            <input type="text" class="form-control" name="internship_location_id" readonly="true" value="{{$location->phone}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Surat Legal Perusahaan</label>
                                            @if($location->legal_file != "")
                                            <div class="mb-2">
                                                <a href="{{$location->legal_file}}" class="btn btn-xs btn-info"> Internship Certification File ({{basename($location->legal_file)}})</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Period</label>
                                    @foreach ($periods as $period)
                                        @if($data->internship_period_id == $period->id)
                                            <input type="text" class="form-control" name="internship_period_id" readonly="true" value="{{$period->name}}">
                                        @endif
                                    @endforeach

                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Approval Status</label>
                                    @if($data->approval_status == "Menunggu Persetujuan")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Menunggu Persetujuan">
                                    @endif
                                    @if($data->approval_status == "Disetujui")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Disetujui">
                                    @endif
                                    @if($data->approval_status == "Ditolak")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Ditolak">
                                    @endif

                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                    @if($data->status == "Aktif")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Aktif">
                                    @endif
                                    @if($data->status == "Selesai")
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="Selesai">
                                    @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Mentor Instructor</label>
                                    @foreach ($instructors as $instructor)
                                        @if($data->mentor_instructor_id == $instructor->id)
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="{{$instructor->name}}">
                                        @endif
                                    @endforeach

                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Examiner Instructor</label>
                                    @foreach ($instructors as $instructor)
                                        @if($data->examiner_instructor_id == $instructor->id)
                                        <input type="text" class="form-control" name="personal_choice" readonly="true" value="{{$instructor->name}}">
                                        @endif
                                    @endforeach

                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Intership File</label>
                                @if($data->intership_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->intership_file}}" class="btn btn-xs btn-info"> Intership File ({{basename($data->intership_file)}})</a>
                                </div>
                                @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Report File</label>
                                @if($data->final_report_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->final_report_file}}" class="btn btn-xs btn-info"> Final Report File ({{basename($data->final_report_file)}})</a>
                                </div>
                                @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Certification File</label>
                                @if($data->internship_certification_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->internship_certification_file}}" class="btn btn-xs btn-info"> Internship Certification File ({{basename($data->internship_certification_file)}})</a>
                                </div>
                                @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Project File</label>
                                @if($data->final_project_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->final_project_file}}" class="btn btn-xs btn-info"> Final Project File ({{basename($data->final_project_file)}})</a>
                                </div>
                                @endif
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Evaluation</label>
                                <input type="text" name="evaluation" class="form-control" placeholder="100" value="{{$data->evaluation}}" readonly>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group float-right">
                                <button type="button" class="btn btn-primary" onclick="window.history.back(-1)"><i class="fas fa-angle-left"></i> Kembali</button>
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
