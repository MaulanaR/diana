@extends('layouts.backend')

@section('css')
@endsection

@section('js')
    <script>
        function save() {
            var formData = new FormData($('#forminsert')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('majors.store') }}",
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
                                <label class="control-label">Akademik Periode *</label>
                                <select class="sel form-control" name="academic_period_id">
                                    @foreach ($academic_period as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                    @endforeach
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
