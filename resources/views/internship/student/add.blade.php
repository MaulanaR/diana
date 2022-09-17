@extends('layouts.backend')

@section('css')
@endsection

@section('js')
    <script>
    function save() {
        var formData = new FormData($('#forminsert')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('internship_students.store') }}",
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
                        <div class="form-body">
                            <input type="hidden" name="created_by" value="{{Auth::user()->id}}">
                            <div class="form-group">
                                <label class="control-label">Student</label>
                                <select class="sel form-control" name="student_id">
                                    @foreach ($students as $student)
                                        <option value="{{$student->id}}">{{$student->full_name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Location</label>
                                <select class="sel form-control" name="internship_location_id">
                                    @foreach ($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Period</label>
                                <select class="sel form-control" name="internship_period_id">
                                    @foreach ($periods as $period)
                                        <option value="{{$period->id}}">{{$period->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group" style="display: none">
                                <label class="sel control-label">Personal Choice</label>
                                <input type="text" name="personal_choice" class="form-control" placeholder="" value="0">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Approval Status</label>
                                <select class="sel form-control" name="approval_status">
                                    <option value="Menunggu Persetujuan">Menunggu Persetujuan</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <select class="sel form-control" name="status">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Mentor Instructor</label>
                                <select class="sel form-control" name="mentor_instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Examiner Instructor</label>
                                <select class="sel form-control" name="examiner_instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Intership File</label>
                                <input type="file" name="intership_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Report File</label>
                                <input type="file" name="final_report_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Internship Certification File</label>
                                <input type="file" name="internship_certification_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Final Project File</label>
                                <input type="file" name="final_project_file"  class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group float-right">
                                <button type="button" class="btn btn-default" onclick="window.history.back(-1)">Batal</button>
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
