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
    //datetime picker
    $('#filterDate').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
        cancelLabel: 'Clear',
        format: 'YYYY-MM-DD'
    }
    });
    $('#closedDate').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    showDropdowns: true,
    locale: {
        cancelLabel: 'Clear',
        format: 'YYYY-MM-DD'
    }
    });
    $('#closedDate').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('#closedDate').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    function save() {
        var formData = new FormData($('#forminsert')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('internship_periods.update') }}",
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
                                <label class="control-label">Academic Period *</label>
                                <select class="sel form-control" name="academic_period_id">
                                    @foreach ($academic_period as $period)
                                        <option value="{{$period->id}}" 
                                        @if($data->academic_period_id == $period->id)
                                        selected="true"
                                        @endif
                                        >{{$period->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Nama *</label>
                                <input type="text" name="name" class="form-control" placeholder="2021/2022 - Ganjil" value="{{$data->name}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Tanggal dibuka *</label>
                                <input type="text" name="start_date" class="form-control" id="filterDate" placeholder="Start Date" value="{{$data->start_date}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Tanggal ditutup *</label>
                                <input type="text" name="end_date" class="form-control" id="closedDate" placeholder="Closed Date" value="{{$data->end_date}}">
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
