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
            url: "{{ route('internship_students.updateapproval') }}",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                popup("Informasi", response.message, 'success', '{{Request::segment(1)."/".Request::segment(2)}}');
            },
            error: function(xhr, textStatus, error) {
                popup("Perhatian", xhr.responseJSON.message, 'warning', false, 5000);
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
