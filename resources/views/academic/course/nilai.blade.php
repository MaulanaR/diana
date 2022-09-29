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
            url: "{{ route('courses.updatenilai') }}",
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

    function HitungFinal(id) {
        uts = Number($("#uts_"+id).val())
        uas = Number($("#uas_"+id).val())
        final = (uts + uas) / 2
        $("#final_"+id).val(final)

        //grade
        let grade = "E"
        if (final >= Number({{$bobot->a_min}}) && final <= Number({{$bobot->a_max}})){
            grade = "A"
        } else if (final >= Number({{$bobot->b_min}}) && final <= Number({{$bobot->b_max}})){
            grade = "B"
        } else if (final >= Number({{$bobot->bplus_min}}) && final <= Number({{$bobot->bplus_max}})){
            grade = "B+"
        } else if (final >= Number({{$bobot->c_min}}) && final <= Number({{$bobot->c_max}})){
            grade = "C"
        } else if (final >= Number({{$bobot->cplus_min}}) && final <= Number({{$bobot->cplus_max}})){
            grade = "C+"
        } else if (final >= Number({{$bobot->d_min}}) && final <= Number({{$bobot->d_max}})){
            grade = "D"
        } else if (final >= Number({{$bobot->e_min}}) && final <= Number({{$bobot->e_max}})){
            grade = "E"
        }
        $("#grade_"+id).val(grade)

    }
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                @if(!isset($bobot->id))
                <div class="card-body">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Peringatan!</h5>
                    Bobot belum ditentukan !
                    Klik <a href="{{route('courses.editbobot')}}/{{$data->id}}" class="btn btn-xs btn-primary">disini</a> untuk menentukan bobot 
                    
                    </div>
                </div>
                @endif
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="#" id="forminsert" class="form-horizontal" name="formnih" autocomplete="off">
                        <input type="hidden" name="course_id" value="{{$data->id}}">
                        <input type="hidden" name="modified_by" value="{{Auth::user()->id}}">

                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">Kelas</div>
                                    <div class="col-8 text-left">: {{$data->class_name}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">Mata Kuliah</div>
                                    <div class="col-8 text-left">: {{$data->name}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">Ketua Instruktur</div>
                                    <div class="col-8 text-left">: {{$data->instructor_name}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">Instruktur</div>
                                    <div class="col-8 text-left">: {{$data->instructor2_name}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-2">File</div>
                                    <div class="col-8">
                                        @if($data->file != "")
                                            <a href="{{$data->file}}" class="btn btn-xs btn-info"> Unduh File ({{basename($data->file)}})</a><br>
                                        @endif
                                        <input type="file" name="file" placeholder="" value="{{$data->file}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <small>Note : Gunakan titik(.) sebagai pemisah koma pada nilai</small>
                                <table class="table table-striped table-bordered" id="student_table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No.</th>
                                            <th width="30%">Nama</th>
                                            <th width="27%">NIM</th>
                                            <th width="10%">UTS</th>
                                            <th width="10%">UAS</th>
                                            <th width="10%">Hasil Akhir</th>
                                            <th width="8%">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($list_student as $no => $student)
                                        <tr>
                                            <td>{{$no+1}}
                                                <input type="hidden" name="student_id[]" value="{{$student->id}}">
                                            </td>
                                            <td>{{$student->full_name}}</td>
                                            <td>{{$student->nim}}</td>
                                            <td>
                                                <input type="number" class="form-control" name="uts[]" value="{{$student->uts}}" onchange="HitungFinal({{$no}})" id="uts_{{$no}}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="uas[]" value="{{$student->uas}}" onchange="HitungFinal({{$no}})" id="uas_{{$no}}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="final[]" value="{{$student->final}}" readonly="true" id="final_{{$no}}">                                                
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="grade[]" value="{{$student->grade}}" readonly="true" id="grade_{{$no}}">                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group float-right">
                                <button type="button" class="btn btn-default" onclick="window.history.back(-1)">Batal</button>
                                <button type="button" class="btn btn-primary" onclick="save()">Proses</button>
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
