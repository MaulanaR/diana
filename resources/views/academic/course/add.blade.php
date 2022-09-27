@extends('layouts.backend')

@section('css')
@endsection

@section('js')
    <script>
    function save() {
        var formData = new FormData($('#forminsert')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('courses.store') }}",
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
    function getMajor(id) {
        $.ajax({
            type: "GET",
            url: "{{ url('app/ajax/get_majors') }}/"+id,
            dataType: "json",
            success: function(response) {
                $("#major_id").empty();
                $("#class_id").empty();
                $("#major_id").append('<option value="">-- SELECT --</option>');
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        $("#major_id").append(
                            '<option value="' + valueOfElement.id + '">' + valueOfElement
                            .name + '</option>'
                        );
                    });
                $("#major_id").selectpicker('refresh')
                $("#class_id").selectpicker('refresh')
            },
            error: function(xhr, textStatus, error) {
                popup("Perhatian", xhr.responseJSON.message, 'warning', false, 3000);
            }
        });
    }

    function getClass(id) {
        $.ajax({
            type: "GET",
            url: "{{ url('app/ajax/get_classes') }}/"+$("#academic_period_id").val()+"/"+id,
            dataType: "json",
            success: function(response) {
                $("#class_id").empty();
                $("#class_id").append('<option value="">-- SELECT --</option>');
                    $.each(response.data, function(indexInArray, valueOfElement) {
                        $("#class_id").append(
                            '<option value="' + valueOfElement.id + '">' + valueOfElement
                            .name + '</option>'
                        );
                    });
                $("#class_id").selectpicker('refresh')
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
                                <label class="control-label">Academic Period</label>
                                <select class="sel form-control" name="academic_period_id" id="academic_period_id" onchange="getMajor(this.value)">
                                    <option value="">-- ALL --</option>
                                    @foreach ($periods as $period)
                                        <option value="{{$period->id}}">{{$period->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Major</label>
                                <select class="sel form-control" name="major_id" id="major_id" onchange="getClass(this.value)">
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Class</label>
                                <select class="sel form-control" name="class_id" id="class_id">
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category</label>
                                <select class="sel form-control" name="categories">
                                    <option value="Kompetensi Khusus">Kompetensi Khusus</option>
                                    <option value="Umum">Umum</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Semester</label>
                                <select class="sel form-control" name="semester">
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Head Instructor</label>
                                <select class="sel form-control" name="head_instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Instructor</label>
                                <select class="sel form-control" name="instructor_id">
                                    @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->name}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">SKS</label>
                                <input type="number" name="sks" class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Total Unit</label>
                                <input type="number" name="total_unit" class="form-control" placeholder="" value="">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea name="description_unit" id="" rows="5" class="form-control"></textarea>
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
