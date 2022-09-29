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
                url: "{{ route('instructor.update') }}",
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
                                            <input type="text" name="name" class="form-control" placeholder="" value="{{$data->name}}">
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group">
                                                <label class="control-label ">TTL</label>
                                                <input type="text" name="ttl" class="form-control" placeholder="" value="{{$data->ttl}}">
                                                <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                            <label class="control-label">Jenis Kelamin</label>
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
                                    <label class="control-label">Status Perkawinan</label>
                                    <select class="sel form-control" name="status_perkawinan">
                                        <option value="Menikah"
                                        @if($data->status_perkawinan == "Menikah")
                                            selected="true"
                                        @endif
                                        >Menikah</option>
                                        <option value="Belum Menikah"
                                        @if($data->status_perkawinan == "Belum Menikah")
                                            selected="true"
                                        @endif
                                        >Belum Menikah</option>
                                    </select>
                                <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Provinsi</label>
                                    <input type="text" name="provinsi" class="form-control" placeholder="" value="{{$data->provinsi}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Kecamatan</label>
                                    <input type="text" name="kecamatan" class="form-control" placeholder="" value="{{$data->kecamatan}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Kota</label>
                                    <input type="text" name="kota" class="form-control" placeholder="" value="{{$data->kota}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Kelurahan</label>
                                    <input type="text" name="kelurahan" class="form-control" placeholder="" value="{{$data->kelurahan}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Address</label>
                                    <input type="text" name="address" class="form-control" placeholder="" value="{{$data->address}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Rt</label>
                                    <input type="text" name="rt" class="form-control" placeholder="" value="{{$data->rt}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Rw</label>
                                    <input type="text" name="rw" class="form-control" placeholder="" value="{{$data->rw}}">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group">
                                    <label class="control-label ">Kode Pos</label>
                                    <input type="text" name="kode_pos" class="form-control" placeholder="" value="{{$data->kode_pos}}">
                                    <span class="help-block"></span>
                                </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label ">NUPTK</label>
                                <input type="text" name="nuptk" class="form-control" placeholder="" value="{{$data->nuptk}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Telp Rumah</label>
                                <input type="text" name="telp_rumah" class="form-control" placeholder="" value="{{$data->telp_rumah}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Hp</label>
                                <input type="text" name="hp" class="form-control" placeholder="" value="{{$data->hp}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Email *</label>
                                <input type="text" name="email" class="form-control" placeholder="" value="{{$data->email}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Npwp</label>
                                <input type="text" name="npwp" class="form-control" placeholder="" value="{{$data->npwp}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Status Kepegawaian</label>
                                <input type="text" name="status_kepegawaian" class="form-control" placeholder="" value="{{$data->status_kepegawaian}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Nip <small>isi jika PNS</small></label>
                                <input type="text" name="nip" class="form-control" placeholder="" value="{{$data->nip}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Pangkat <small>isi jika PNS</small></label>
                                <input type="text" name="pangkat" class="form-control" placeholder="" value="{{$data->pangkat}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Tmt PNS <small>isi jika PNS</small></label>
                                <input type="text" name="tmt_pns" class="form-control" placeholder="" value="{{$data->tmt_pns}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Nama Pasangan</label>
                                <input type="text" name="nama_pasangan" class="form-control" placeholder="" value="{{$data->nama_pasangan}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Pekerjaan Pasangan</label>
                                <input type="text" name="pekerjaan" class="form-control" placeholder="" value="{{$data->pekerjaan}}">
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label ">Nip Pasangan <small>isi jika PNS</small></label>
                                <input type="text" name="nip_pasangan" class="form-control" placeholder="" value="{{$data->nip_pasangan}}">
                                <span class="help-block"></span>
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
