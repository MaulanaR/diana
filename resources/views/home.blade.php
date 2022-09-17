@extends('layouts.backend')


@section('css')
    {{-- tempat memasukan/Load file CSS --}}
    <link type="text/css" rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
    <style>
        .styletable {
                border-collapse: separate; 
                border-spacing: 0 10px; 
                margin-top: -10px; /* correct offset on first border spacing if desired */
        }
    </style>
@endsection

@section('js')
    {{-- tempat memasukan/Load file JS atau fungsi <script> --}}
    <script src="{{ asset('daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        Highcharts.chart('containerx', {

            title: {
                @if(!$filter)
                text: 'Grafik Kunjungan Bulanan Tahun {{date('Y')}}'
                @else
                text: 'Grafik Kunjungan Bulanan ({{$awal}} - {{$akhir}})'
                @endif
            },


            yAxis: {
                title: {
                    text: 'Kunjungan'
                }
            },

            xAxis: {
                accessibility: {
                    @if(!$filter)
                        rangeDescription: 'Bulan Januari - Desember'
                    @else
                        rangeDescription: '{{$awal}} - {{$akhir}}'
                    @endif
                },
                @if(!$filter)
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                @else
                    categories: <?php echo json_encode($list, true); ?>
                @endif
            },


            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            series: [{
                name: 'Jumlah Kunjungan',
                data: <?php echo json_encode($graph, true); ?>
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });

        //datetime picker
        $('#filterDate').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
            }
        });

        $('#filterDate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#filterDate').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endsection

@section('content')
    @if ($isAdmin) 
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('homelogin') }}" method="GET">
                        <div class="row">
                            <div class="col-3">
                                <h5><i class="fas fa-filter"></i> Filter Berdasarkan Range:</h5>
                            </div>
                            <div class="col-7">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-md btn-outline-primary"><i
                                                class="fas fa-calendar"></i></button>
                                    </div>
                                    <input type="text" class="form-control" id="filterDate" required=""
                                        placeholder="Filter by Range Date" autocomplete="off" name="filterDate" @if ($filter)
                                    value="{{ $isian }}"
                                    @endif
                                    >
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <button type="submit" class="btn btn-sm btn-outline-primary" alt="Filter"><i class="fas fa-filter"></i> Filter</button>
                                <a href="{{route('homelogin')}}" class="btn btn-sm btn-outline-success" alt="Reset"><i class="fas fa-undo"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- /.card-header -->
                <div class="card-body row" style="min-height: 25em !important">
                    <div class="col-12 col-md-5">
                            <div class="card card-default" style="position: relative; left: 0px; top: 0px;box-shadow:none">
                                <div class="card-header border-0 ">
                                    <h3 class="card-title">
                                        <i class="fas fa-user mr-1"></i>
                                        User Profile
                                    </h3>
                                </div>
                                <div class="card-body" style="display: block;max-height:25em; overflow-y:scroll">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>Nama</td>
                                                <td>
                                                    {{ Auth::user()->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td>
                                                    {{ Auth::user()->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Roles</td>
                                                <td>
                                                    @php
                                                        $group = DB::table('alus_ug')
                                                            ->where('user_id', Auth::user()->id)
                                                            ->join('alus_g', 'alus_ug.group_id', '=', 'alus_g.id')
                                                            ->get();
                                                    @endphp
                                                    <ol>
                                                        @foreach ($group as $value_group)
                                                            <li>{{ $value_group->name }}</li>
                                                        @endforeach
                                                    </ol>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Login Terakhir</td>
                                                <td>{{ date('d-m-Y H:i', strtotime(Auth::user()->before_last_login_at)) }}</td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-12 col-md-7" id="containerx">

                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    @endif
@endsection
