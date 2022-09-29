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
            url: "{{ route('courses.updatebobot') }}",
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
                        <input type="hidden" name="course_id" value="{{$data->course_id}}">
                        <input type="hidden" name="modified_by" value="{{Auth::user()->id}}">

                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label">Grade A</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="a_min" class="form-control" placeholder="" value="{{$data->a_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="a_max" class="form-control" placeholder="" value="{{$data->a_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade B</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="b_min" class="form-control" placeholder="" value="{{$data->b_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="b_max" class="form-control" placeholder="" value="{{$data->b_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade B+</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="bplus_min" class="form-control" placeholder="" value="{{$data->bplus_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="bplus_max" class="form-control" placeholder="" value="{{$data->bplus_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade C</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="c_min" class="form-control" placeholder="" value="{{$data->c_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="c_max" class="form-control" placeholder="" value="{{$data->c_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade C+</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="cplus_min" class="form-control" placeholder="" value="{{$data->cplus_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="cplus_max" class="form-control" placeholder="" value="{{$data->cplus_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade D</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="d_min" class="form-control" placeholder="" value="{{$data->d_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="d_max" class="form-control" placeholder="" value="{{$data->d_max}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Grade E</label>
                                <div class="row">
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="e_min" class="form-control" placeholder="" value="{{$data->e_min}}">
                                    </div>
                                    <div class="col-1 text-center">
                                        <label class="control-label"><small>S/d</small></label>
                                    </div>
                                    <div class="col-1">
                                        <input type="number" autocomplete="false" name="e_max" class="form-control" placeholder="" value="{{$data->e_max}}">
                                    </div>
                                </div>
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
