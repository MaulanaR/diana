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
            url: "{{ route('internship_students.updateberkas') }}",
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
