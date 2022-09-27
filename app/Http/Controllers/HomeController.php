<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Instructors;
use App\Models\Majors;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\FileMetaData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $groups = Session::get('current_roles');
        $isAdmin = false;
        $isStudent = false;
        $isInstructor = false;
        foreach ($groups as $key => $value) {
            if ($value->name == "admin") {
                $isAdmin = true;
            } else if ($value->name == "student") {
                $isStudent = true;
            } else if ($value->name == "instructor") {
                $isInstructor = true;
            }
        }
        if ($isAdmin) {
            if ($request->has('filterDate')) {

                $buka = explode(' - ', $request->input('filterDate'));
                if (!array_key_exists(0, $buka) || !array_key_exists(1, $buka)) {
                    abort(403);
                }

                //format DD/MM/YYYY

                $filterAwal_code = Carbon::createFromFormat('d/m/Y', $buka[0])->format('Y-m-d 00:00:01');
                $filterAkhir_code = Carbon::createFromFormat('d/m/Y', $buka[1])->format('Y-m-d 23:59:59');

                $filterAwal  = $buka[0];
                $filterAkhir = $buka[1];

                //cari bulan dan tahun yg disediakan
                //tampilan untuk grafik, ambil data yang tersedia by bulan & tahun . so easy !!!            
                $lis = $this->getMonthListFromDate(Carbon::parse($filterAwal_code), Carbon::parse($filterAkhir_code));
                $a = Carbon::parse($filterAwal_code);
                $b = Carbon::parse($filterAkhir_code);

                $graph = array();
                $graph_info = array();
                foreach ($lis as $key_lis => $value_lis) {
                    $tahun = Carbon::createFromFormat('m-Y', $key_lis)->format('Y');
                    $bulan = Carbon::createFromFormat('m-Y', $key_lis)->format('m');

                    //cek apakah bulannya 
                    if ($a->format('m') == $bulan && $b->format('m') == $bulan && $a->format('Y') == $tahun && $b->format('Y') == $tahun) {
                        //maka tgl awal dan akhir ada di satu bulan
                        $total = DB::table('t_visitors')->select('ip')->whereBetween('date', [$filterAwal_code, $filterAkhir_code])->count();
                    } else if ($a->format('m') == $bulan && $a->format('Y') == $tahun) {
                        //maka tgl awal dari isian
                        $total = DB::table('t_visitors')->select('ip')->whereBetween('date', [$filterAwal_code, $a->endOfMonth()->format('Y-m-d H:i:s')])->count();
                    } else if ($b->format('m') == $bulan && $b->format('Y') == $tahun) {
                        //maka tanggal akhir dari isian
                        $total = DB::table('t_visitors')->select('ip')->whereBetween('date', [$b->startOfMonth()->format('Y-m-d H:i:s'), $filterAkhir_code])->count();
                    } else {
                        $total = DB::table('t_visitors')->select('ip')->whereYear('date', $tahun)->whereMonth('date', $bulan)->count();
                    }
                    array_push($graph, $total);
                    array_push($graph_info, $value_lis);
                }

                $send = array(
                    'graph' => $graph,
                    'list'  => $graph_info,
                    'title' => "Dashboard",
                    'filter' => true,
                    'awal'  => $filterAwal,
                    'akhir'  => $filterAkhir,
                    'isian' => $request->input('filterDate'),
                    'data' => []
                );

                $filtering = DB::table('t_visitors')
                    ->select('ip')
                    ->whereBetween('date', [$filterAwal_code, $filterAkhir_code])
                    ->count();

                // dd($filtering);
                $send['filtering'] = $filtering;
            } else {
                // $users = DB::table('users')->get();
                // $this->cek();
                $jan = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 1)->count();
                $feb = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 2)->count();
                $mar = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 3)->count();
                $apr = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 4)->count();
                $mei = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 5)->count();
                $jun = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 6)->count();
                $jul = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 7)->count();
                $ags = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 8)->count();
                $sep = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 9)->count();
                $okt = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 10)->count();
                $nov = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 11)->count();
                $des = DB::table('t_visitors')->select('ip')->whereYear('date', date('Y'))->whereMonth('date', 12)->count();

                $graph = [$jan, $feb, $mar, $apr, $mei, $jun, $jul, $ags, $sep, $okt, $nov, $des];

                Carbon::setWeekStartsAt(Carbon::MONDAY);
                Carbon::setWeekEndsAt(Carbon::SUNDAY);

                $send = array(
                    'graph' => $graph,
                    // 'user' => $users,
                    'filter' => false,
                    'title' => "Dashboard",
                );

                //aktifitas 3 menit terakhir , dikatakan sbg online
                $send['online'] = DB::table('t_visitors')
                    ->select('ip')
                    ->whereBetween('date', [now()->subMinutes(3), now()])
                    ->GroupBy('ip')
                    ->count();
                $send['hari_ini'] = DB::table('t_visitors')
                    ->select('ip')
                    ->whereDate('date', Carbon::today())
                    ->count();
                $send['minggu_ini'] = DB::table('t_visitors')
                    ->select('ip')
                    ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->count();
                $send['bulan_ini'] = DB::table('t_visitors')
                    ->select('ip')
                    ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->count();
                $send['tahun_ini'] = DB::table('t_visitors')
                    ->select('ip')
                    ->whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                    ->count();
            }
        }

        $send['isAdmin'] = $isAdmin;
        $send['isStudent'] = $isStudent;
        $send['isInstructor'] = $isInstructor;

        if ($isAdmin) {
            return view('home', $send);
        } else if ($isStudent) {
            $send['title'] = 'Biodata';
            $send['data'] = Students::where('id', Auth::user()->id)->first();
            return view('home_student', $send);
        } else if ($isInstructor) {
            $send['title'] = 'Biodata';
            $send['data'] = Instructors::where('id', Auth::user()->id)->first();
            return view('home_instructor', $send);
        }
    }

    public function getMonthListFromDate(Carbon $start, Carbon $end)
    {
        foreach (CarbonPeriod::create($start, $end) as $month) {
            $months[$month->format('m-Y')] = $month->format('F Y');
        }
        return $months;
    }

    public function ubah_role(Request $request)
    {
        $id_group = $request->input('id_group');
        if ($id_group) {
            //cek apakah id_group valid untuk user ini 
            $group = DB::table('alus_ug')->join('alus_g', 'alus_ug.group_id', '=', 'alus_g.id')->where('user_id', Auth::id())->where('group_id', $id_group)->limit(1)->get();
            if ($group) {
                //maka valid dan ada, lanjut proses
                //hapus sesi role sebelumnya
                $request->session()->remove('current_roles');

                //buat sesi baru 
                $request->session()->put('current_roles', $group);

                //return success
                return response()->json(['status' => true, 'statusText' => "Berhasil"]);
            } else {
                return response()->json([
                    'status' => false,
                    'statusText' => 'Id Group tidak sesuai!'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function get_majors(Request $request, $id = null)
    {
        $data = [];
        if ($id) {
            $data = Majors::where('academic_period_id', $id)->get();
        }
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function get_classes(Request $request, $period_id = null, $major_id = null)
    {
        $data = [];
        if ($period_id && $major_id) {
            $data = Classes::where('academic_period_id', $period_id)->where('major_id', $major_id)->get();
        }
        return response()->json(['status' => true, 'data' => $data]);
    }
}
