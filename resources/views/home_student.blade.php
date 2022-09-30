@extends('layouts.backend')


@section('css')
    {{-- tempat memasukan/Load file CSS --}}
    <link type="text/css" rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
@endsection

@section('js')
    {{-- tempat memasukan/Load file JS atau fungsi <script> --}}
    <script src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $('.dates').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
        cancelLabel: 'Clear',
        format: 'YYYY-MM-DD'
    }
    });
        function save() {
            var formData = new FormData($('#forminsert')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('students.update') }}",
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
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>65</h3>
                <p>Periode Akademik</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>150</h3>
                <p>Jumlah Mahasiswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>150</h3>
                <p>Jumlah Mahasiswa Laki-laki</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>150</h3>
                <p>Jumlah Mahasiswi Perempuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Jumlah Instruktur</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>44</h3>
                <p>Jumlah Jurusan</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white">
            <div class="inner">
                <h3>65</h3>
                <p>Jumlah Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-12">
            <h3>Biodata</h3>
            <div class="card">
                <div class="card-body">
                    <form action="#" id="forminsert" class="form-horizontal" name="formnih" autocomplete="off">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <input type="hidden" name="modified_by" value="{{Auth::user()->id}}">

                        <div class="form-body">
                            <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                                @if($data->avatar != "" && file_exists(public_path('avatar/'.$data->avatar))) 
                                                <img src="{{asset('avatar/'.$data->avatar)}}" class="img-thumbnail rounded mx-auto d-block" alt="User Image">
                                                @else
                                                <img src="{{asset('avatar/user.png')}}" class="img-thumbnail rounded mx-auto d-block" alt="User Image">
                                                @endif
                                                <input type="hidden" name="avatar_old" value="{{$data->avatar}}">
                                            </div>
                                        <div class="form-group">
                                            <input type="file" name="avatar" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="control-label ">Full Name *</label>
                                            <input type="text" name="full_name" class="form-control" placeholder="" value="{{$data->full_name}}">
                                            <span class="help-block"></span>
                                    </div>
                                    <div class="form-group">
                                            <label class="control-label ">Birth Place *</label>
                                            <input type="text" name="birth_place" class="form-control" placeholder="" value="{{$data->birth_place}}">
                                            <span class="help-block"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label ">Birth Date *</label>
                                        <input type="text" name="birth_date" class="dates form-control" placeholder="" value="{{$data->birth_date}}">
                                        <span class="help-block"></span>
                                    </div>
                                    </div>
                                </div>
                                
                            <div class="form-group">
                                        <label class="control-label">Gender *</label>
                                        <select class="sel form-control" name="gender">
                                            <option value="Male"
                                            @if($data->gender == "Male")
                                                selected="true"
                                            @endif
                                            >Male</option>
                                            <option value="Female"
                                            @if($data->gender == "Female")
                                                selected="true"
                                            @endif
                                            >Female</option>
                                        </select>
                                    <span class="help-block"></span>
                            </div>
                                <div class="form-group">
                                        <label class="control-label ">Religion *</label>
                                        <select class="sel form-control" name="religion">
                                            <option value="Islam"
                                            @if($data->religion == "Islam")
                                                selected="true"
                                            @endif
                                            >Islam</option>
                                            <option value="Protestan"
                                            @if($data->religion == "Protestan")
                                                selected="true"
                                            @endif
                                            >Protestan</option>
                                            <option value="Katolik"
                                            @if($data->religion == "Katolik")
                                                selected="true"
                                            @endif
                                            >Katolik</option>
                                            <option value="Hindu"
                                            @if($data->religion == "Hindu")
                                                selected="true"
                                            @endif
                                            >Hindu</option>
                                            <option value="Buddha"
                                            @if($data->religion == "Buddha")
                                                selected="true"
                                            @endif
                                            >Buddha</option>
                                            <option value="Khonghucu"
                                            @if($data->religion == "Khonghucu")
                                                selected="true"
                                            @endif
                                            >Khonghucu</option>
                                        </select>
                                        <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                        <label class="control-label">NIM *</label>
                                        <input type="text" name="nim" class="form-control" placeholder="" value="{{$data->nim}}">
                                        <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                        <label class="control-label">NIK *</label>
                                        <input type="text" name="nik" class="form-control" placeholder="" value="{{$data->nik}}">
                                        <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                        <label class="control-label ">Home Address *</label>
                                        <input type="text" name="home_address" class="form-control" placeholder="" value="{{$data->home_address}}">
                                        <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                        <label class="control-label ">Phone *</label>
                                        <input type="text" name="phone" class="form-control" placeholder="" value="{{$data->phone}}">
                                        <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Social Media</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="socmed" value="instagram" class="socmed_radio" id="socmed_instagram" 
                                            @if($data->socmed_instagram == '1') 
                                            checked="checked"
                                            @endif>
                                            <label class="form-check-label" for="socmed_instagram">
                                            Instagram
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="socmed" value="twitter" class="socmed_radio" id="socmed_twitter" 
                                            @if($data->socmed_twitter == '1') 
                                            checked="checked"
                                            @endif>
                                            <label class="form-check-label" for="socmed_twitter">
                                            Twitter
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="socmed" value="other" class="socmed_radio" id="socmed_other" 
                                            @if($data->socmed_other == '1') 
                                            checked="checked"
                                            @endif>
                                            <label class="form-check-label" for="socmed_other">
                                            Other
                                            </label>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Username Social Media</label>
                                    <input type="text" name="socmed_username" class="form-control" placeholder="" value="{{$data->socmed_username}}">
                                    <span class="help-block"></span>
                            </div>
                            </div>
        <div class="col-6">
                            <div class="form-group">
                                    <label class="control-label ">Biological Father Name *</label>
                                    <input type="text" name="biological_father_name" class="form-control" placeholder="" value="{{$data->biological_father_name}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Biological Mother Name *</label>
                                    <input type="text" name="biological_mother_name" class="form-control" placeholder="" value="{{$data->biological_mother_name}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Biological Father Phone *</label>
                                    <input type="phone" name="biological_father_phone" class="form-control" placeholder="" value="{{$data->biological_father_phone}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Biological Mother Phone *</label>
                                    <input type="text" name="biological_mother_phone" class="form-control" placeholder="" value="{{$data->biological_mother_phone}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Origin School *</label>
                                    <input type="phone" name="origin_school" class="form-control" placeholder="" value="{{$data->origin_school}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Major Origin School *</label>
                                    <input type="text" name="major_origin_school" class="form-control" placeholder="" value="{{$data->major_origin_school}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">District Origin School *</label>
                                    <input type="text" name="district_origin_school" class="form-control" placeholder="" value="{{$data->district_origin_school}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Province Origin School *</label>
                                    <input type="text" name="province_origin_school" class="form-control" placeholder="" value="{{$data->province_origin_school}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Family Card File</label>
                                    @if($data->family_card_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->family_card_file}}" class="btn btn-xs btn-info"> Family Card File ({{basename($data->family_card_file)}})</a>
                                </div>
                                @endif
                                    <input type="file" name="family_card_file" class="form-control" placeholder="" value="{{$data->family_card_file}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Id Card File</label>
                                    @if($data->id_card_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->id_card_file}}" class="btn btn-xs btn-info"> Id Card File ({{basename($data->id_card_file)}})</a>
                                </div>
                                @endif
                                    <input type="file" name="id_card_file" class="form-control" placeholder="" value="{{$data->id_card_file}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Statement Letter File</label>
                                    @if($data->statement_letter_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->statement_letter_file}}" class="btn btn-xs btn-info"> Statement Letter File ({{basename($data->statement_letter_file)}})</a>
                                </div>
                                @endif
                                    <input type="file" name="statement_letter_file" class="form-control" placeholder="" value="{{$data->statement_letter_file}}">
                                    <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                    <label class="control-label ">Certificate Last Education File</label>
                                    @if($data->certificate_last_education_file != "")
                                <div class="mb-2">
                                    <a href="{{$data->classcertificate_last_education_filebtn}}"  class="btn-xs btn-info"> Certificate Last Education File ({{basename($data->certificate_last_education_file)}})</a>
                                </div>
                                @endif
                                    <input type="file" name="certificate_last_education_file" class="form-control" placeholder="" value="{{$data->certificate_last_education_file}}">
                                    <span class="help-block"></span>
                            </div>
        </div>
                        </div>
        <div class="col-12">
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary" onclick="save()">Update Biodata</button>
                            </div>
        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
@endsection
