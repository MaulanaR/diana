@extends('layouts.backend')

@section('css')
<style>
    @media print {
  body * {
    visibility: hidden;
  }
  #printx * {
    visibility: visible;
  }
  #printx {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
@endsection

@section('js')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table " style="border: 0px" id="printx">
                        <tr>
                            <td width="100%" class="text-left" colspan="2">
                                <h2 style="font-weight: 700">Lembaga Pendidikan Teknologi Informasi & Bisnis</h2>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/ea/ELTIBIZ_small.png" alt="" width="100%">
                            </td>
                            <td width="70%">
                                <h5 class="text-center">
                                    Kantor Pusat : Kampus Palangkaraya Jl. Cilik Riwut No 4 Km 1.5 Palangka Raya - Kalteng
                                </h5>
                                <h5 class="text-center">
                                    Telp 0536 - 32 26 5 20   Email : eltibiz.lpk@gmail.com
                                </h5>
                                <h5 class="text-center">
                                    Cabang : Kampus BAnjarmasin Jl. Balitung Laut No 8 Banjarmasin - Kalimantan Selatan
                                </h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center" style="background-color: #3e6590;color:white">
                                <h2>NILAI TRANSKRIP TAHUN AJARAN {{$period->name}}</h2>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-4">
                                        <h5>NAMA SISWA</h5>
                                        <h5>JURUSAN</h5>
                                        <h5>NOMOR INDUK PESERTA PELATIHAN</h5>
                                        <h5>TEMPAT, TANGGAL LAHIR</h5>
                                    </div>
                                    <div class="col-5">
                                        <h5>: {{$student->full_name}}</h5>
                                        <h5>: {{$class->major_name}}</h5>
                                        <h5>: {{$student->nim}}</h5>
                                        <h5>: {{$student->birth_place}}, {{$student->birth_date}}</h5>
                                    </div>
                                    <div class="col-3">
                                        <h1 class="text-center" style="height: 100%; background-color:grey;padding:30px 0px;font-size:6em">{{$ipk}}</h1>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h4 style="color:#3e6590">MATA KULIAH UMUM</h4>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row" style="background-color: #3e6590;color:white">
                                    <div class="col-1"><h5>No</h5></div>
                                    <div class="col-3"><h5>Mata Kuliah</h5></div>
                                    <div class="col-2"><h5>Nilai Huruf</h5></div>
                                    <div class="col-2"><h5>Nilai Angka</h5></div>
                                    <div class="col-2"><h5>SKS</h5></div>
                                    <div class="col-2"><h5>SKS x Nilai Angka</h5></div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $no = 0;
                        @endphp
                        @foreach($courses as $course)
                            @if($course->categories != "Kompetensi Khusus")
                            @php
                                $no++;
                            @endphp
                                <tr>
                                    <td colspan="2">
                                        <div class="row">
                                            <div class="col-1"><h5>{{$no}}</h5></div>
                                            <div class="col-3"><h5>{{$course->name}}</h5></div>
                                            <div class="col-2"><h5>{{$course->grade}}</h5></div>
                                            <div class="col-2"><h5>{{$course->final}}</h5></div>
                                            <div class="col-2"><h5>{{$course->sks}}</h5></div>
                                            <div class="col-2"><h5>{{$course->sks * $course->final}}</h5></div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">
                                <h4 style="color:#3e6590">MATA KULIAH KOMPETENSI KHUSUS</h4>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row" style="background-color: #3e6590;color:white">
                                    <div class="col-1"><h5>No</h5></div>
                                    <div class="col-3"><h5>Mata Kuliah</h5></div>
                                    <div class="col-2"><h5>Nilai Huruf</h5></div>
                                    <div class="col-2"><h5>Nilai Angka</h5></div>
                                    <div class="col-2"><h5>SKS</h5></div>
                                    <div class="col-2"><h5>SKS x Nilai Angka</h5></div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $no = 0;
                        @endphp
                        @foreach($courses as $course)
                            @if($course->categories == "Kompetensi Khusus")
                            @php
                                $no++;
                            @endphp
                                <tr>
                                    <td colspan="2">
                                        <div class="row">
                                            <div class="col-1"><h5>{{$no}}</h5></div>
                                            <div class="col-3"><h5>{{$course->name}}</h5></div>
                                            <div class="col-2"><h5>{{$course->grade}}</h5></div>
                                            <div class="col-2"><h5>{{$course->final}}</h5></div>
                                            <div class="col-2"><h5>{{$course->sks}}</h5></div>
                                            <div class="col-2"><h5>{{$course->sks * $course->final}}</h5></div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="2">
                                <h4 style="color:#3e6590">TOTAL NILAI DAN IPK</h4>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-6"><h5>Jumlah SKS</h5></div>
                                    <div class="col-6"><h5>{{$totalsks}}</h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-6"><h5>Jumlah Nilai x SKS</h5></div>
                                    <div class="col-6"><h5>{{$totalsksxnilai}}</h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-6"><h5>Indeks Prestasi Kumulatif (IPK)</h5></div>
                                    <div class="col-6">
                                        <h5>{{$ipk}}</h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-6"><h5>Predikat</h5></div>
                                    <div class="col-6"><h5></h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row" style="background-color: #3e6590;color:white">
                                    <div class="col-12"><h4>KETERANGAN NILAI</h4></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-3"><h5>A = Baik Sekali</h5></div>
                                    <div class="col-2"><h5>D = Kurang</h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-3"><h5>B = Baik</h5></div>
                                    <div class="col-2"><h5>E = Gagal</h5></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-3"><h5>C = Cukup</h5></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <br>
                <button class="btn btn-md btn-primary" onclick="window.print()">Print</button>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>

@endsection
