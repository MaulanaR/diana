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
                                <select class="sel form-control" name="student_id">
                                    @foreach ($students as $student)
                                        <option value="{{$student->id}}"
                                        @if($data->student_id == $student->id)
                                        selected="true"
                                        @endif
                                        >{{$student->full_name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Location</label>
                                <select class="sel form-control" name="internship_location_id">
                                    @foreach ($locations as $location)
                                        <option value="{{$location->id}}"
                                        @if($data->internship_location_id == $location->id)
                                        selected="true"
                                        @endif
                                        >{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Period</label>
                                <select class="sel form-control" name="internship_period_id">
                                    @foreach ($periods as $period)
                                        <option value="{{$period->id}}"
                                        @if($data->internship_period_id == $period->id)
                                        selected="true"
                                        @endif
                                        >{{$period->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="sel control-label">Personal Choice</label>
                                <select class="sel form-control" name="personal_choice">
                                    <option value="1"
                                    @if($data->personal_choice == "1")
                                        selected="true"
                                    @endif
                                    >Ya</option>
                                    <option value="0"
                                    @if($data->personal_choice == "0")
                                        selected="true"
                                    @endif
                                    >Tidak</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Approval Status</label>
                                <select class="sel form-control" name="approval_status">
                                    <option value="Menunggu Persetujuan"
                                    @if($data->approval_status == "Menunggu Persetujuan")
                                        selected="true"
                                    @endif
                                    >Menunggu Persetujuan</option>
                                    <option value="Disetujui"
                                    @if($data->approval_status == "Disetujui")
                                        selected="true"
                                    @endif
                                    >Disetujui</option>
                                    <option value="Ditolak"
                                    @if($data->approval_status == "Ditolak")
                                        selected="true"
                                    @endif
                                    >Ditolak</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select class="sel form-control" name="status">
                                    <option value="Aktif"
                                    @if($data->status == "Aktif")
                                        selected="true"
                                    @endif
                                    >Aktif</option>
                                    <option value="Selesai"
                                    @if($data->status == "Selesai")
                                        selected="true"
                                    @endif
                                    >Selesai</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Mentor Instructor</label>
                                <select class="sel form-control" name="mentor_instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}"
                                        @if($data->mentor_instructor_id == $instructor->id)
                                        selected="true"
                                        @endif
                                        >{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Examiner Instructor</label>
                                <select class="sel form-control" name="examiner_instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}"
                                        @if($data->examiner_instructor_id == $instructor->id)
                                        selected="true"
                                        @endif
                                        >{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Intership File</label>
                                @if($data->intership_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->intership_file}}" class="btn btn-xs btn-info"> Intership File ({{basename($data->intership_file)}})</a>
                                </div>
                                @endif
                                <input type="file" name="intership_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Report File</label>
                                @if($data->final_report_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->final_report_file}}" class="btn btn-xs btn-info"> Final Report File ({{basename($data->final_report_file)}})</a>
                                </div>
                                @endif
                                <input type="file" name="final_report_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Certification File</label>
                                @if($data->internship_certification_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->internship_certification_file}}" class="btn btn-xs btn-info"> Internship Certification File ({{basename($data->internship_certification_file)}})</a>
                                </div>
                                @endif
                                <input type="file" name="internship_certification_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Project File</label>
                                @if($data->final_project_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->final_project_file}}" class="btn btn-xs btn-info"> Final Project File ({{basename($data->final_project_file)}})</a>
                                </div>
                                @endif
                                <input type="file" name="final_project_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Evaluation</label>
                                <input type="text" name="evaluation" class="form-control" placeholder="" value="{{$data->evaluation}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group float-right">
                                <button type="button" class="btn btn-default" onclick="window.history.back(-1)">Batal</button>
                                <button type="button" class="btn btn-primary" onclick="save()">Update</button>
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
