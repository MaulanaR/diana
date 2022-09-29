@extends('layouts.backend')

@section('css')
@endsection

@section('js')
    <script>
    function save() {
        var formData = new FormData($('#forminsert')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('internship_students.storeStudent') }}",
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

    $('input[type=radio][name=personal_choice]').change(function() {
    if (this.value == '1') {
        $("#pilih").show();
        $("#pilih2").hide();
    }
    else if (this.value == '0') {
        $("#pilih").hide();
        $("#pilih2").show();
    }
});
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
                            <input type="hidden" name="student_id" value="{{Auth::user()->id}}">
                            <div class="form-group">
                                <label class="control-label">Internship Period *</label>
                                <select class="sel form-control" name="internship_period_id">
                                    @foreach ($periods as $period)
                                        <option value="{{$period->id}}">{{$period->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Apakah Anda memilih tempat magang sendiri ?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="personal_choice" value="1">
                                    <label class="form-check-label">Ya, Tempat magang memilih sendiri</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="personal_choice" value="0">
                                    <label class="form-check-label">Tidak, tempat magang dipilihkan dari ELTBIZ</label>
                                </div>
                            </div>
                            <div class="card-body" id="pilih" style="display: none; background-color:#F0FFFF">
                                <div class="form-group">
                                    <label class="control-label">Nama Tempat Magang *</label>
                                    <input type="text" name="name" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Alamat *</label>
                                    <input type="text" name="address" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Kontak *</label>
                                    <input type="text" name="pic_contact" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Jabatan Kontak *</label>
                                    <input type="text" name="pic_position" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">No Telp Perusahaan *</label>
                                    <input type="text" name="phone" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Surat Legal *</label>
                                    <input type="file" name="legal_file" class="form-control" placeholder="" value="">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="card-body" id="pilih2" style="display: none; background-color:#F0FFFF">
                                <div class="form-group">
                                    <label class="control-label">Internship Location</label>
                                    <select class="sel form-control" name="internship_location_id">
                                        @foreach ($locations as $location)
                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group float-right mt-2">
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
