<?php

namespace App\Http\Controllers;

use App\Models\Instructors;
use App\Models\InternshipLocations;
use App\Models\InternshipPeriods;
use App\Models\InternshipsStudents;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

const modulName = "Mahasiswa Magang";

class InternshipsStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['isAdmin'] = false;
        $data['isInstructor'] = false;
        $data['isStudent'] = false;

        $groups = Session::get('current_roles');
        foreach ($groups as $key => $value) {
            if ($value->name == "admin") {
                $data['isAdmin'] = true;
            } else if ($value->name == "student") {
                $data['isStudent'] = true;
            } else if ($value->name == "instructor") {
                $data['isInstructor'] = true;
            }
        }
        $data['title'] = modulName;
        return view('internship.student.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Tambah ' . modulName;
        $data['students'] = Students::get();
        $data['locations'] = InternshipLocations::get();
        $data['periods'] = InternshipPeriods::get();
        $data['instructors'] = Instructors::get();

        $groups = Session::get('current_roles');
        foreach ($groups as $key => $value) {
            if ($value->name == "student") {
                return view('internship.student.addStudent', $data);
            }
        }

        return view('internship.student.add', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'student_id' => 'required',
            'internship_location_id' => 'required',
            'internship_period_id' => 'required',
            'approval_status' => 'required',
            'status' => 'required',
            'mentor_instructor_id' => 'required',
            'examiner_instructor_id' => 'required',
        ], [], [
            'student_id' => 'Student',
            'internship_location_id' => 'Internship Location',
            'internship_period_id' => 'Internship Period',
            'approval_status' => 'Approval Status',
            'status' => 'Status',
            'mentor_instructor_id' => 'Mentor Instructor',
            'examiner_instructor_id' => 'Examiner Instructor',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $arr = [
                'student_id' => $request->input('student_id'),
                'internship_location_id' => $request->input('internship_location_id'),
                'internship_period_id' => $request->input('internship_period_id'),
                'approval_status' => $request->input('approval_status'),
                'personal_choice' => $request->input('personal_choice'),
                'status' => $request->input('status'),
                'mentor_instructor_id' => $request->input('mentor_instructor_id'),
                'examiner_instructor_id' => $request->input('examiner_instructor_id'),
                'created_by' => Auth::user()->id,
            ];

            if ($request->hasFile('intership_file')) {
                $file = $request->file('intership_file');
                $dirUpload = 'intership_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['intership_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_report_file')) {
                $file = $request->file('final_report_file');
                $dirUpload = 'final_report_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_report_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('internship_certification_file')) {
                $file = $request->file('internship_certification_file');
                $dirUpload = 'internship_certification_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['internship_certification_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_project_file')) {
                $file = $request->file('final_project_file');
                $dirUpload = 'final_project_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_project_file'] = asset($dirUpload . '/' . $fileName);
            }

            InternshipsStudents::create($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function storeStudent(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'student_id' => 'required',
            'personal_choice' => 'required',
            'internship_period_id' => 'required',
        ], [], [
            'personal_choice' => 'Pilih magang sendiri ',
            'internship_period_id' => 'Period',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $arr = [
                'student_id' => $request->input('student_id'),
                'personal_choice' => $request->input('personal_choice'),
                'internship_period_id' => $request->input('internship_period_id'),
                'approval_status' => "Menunggu Persetujuan",
                'status' => "Aktif",
                'created_by' => Auth::user()->id,
            ];

            if ($request->input('personal_choice') == 1) {
                $validated = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                    'pic_contact' => 'required',
                    'pic_position' => 'required',
                    'phone' => 'required',
                    'legal_file' => 'file',
                ], [], [
                    'name' => 'Name',
                    'address' => 'Address',
                    'pic_contact' => 'Pic Contact',
                    'pic_position' => 'Pic Position',
                    'phone' => 'Phone',
                    'legal_file' => 'Legal File',
                ]);

                if ($validated->fails()) {
                    $err = array();
                    foreach ($validated->errors()->toArray() as $error) {
                        foreach ($error as $sub_error) {
                            array_push($err, $sub_error . ' <br>');
                        }
                    }

                    return response()->json([
                        'status' => 'failed',
                        'message' => $err,
                    ], Response::HTTP_BAD_REQUEST);
                }

                //add new data to internship location
                $loc = [
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'pic_contact' => $request->input('pic_contact'),
                    'pic_position' => $request->input('pic_position'),
                    'phone' => $request->input('phone'),
                    'delete' => now()
                ];
                if ($request->hasFile('legal_file')) {
                    $file = $request->file('legal_file');
                    $dirUpload = 'legalfiles';
                    $fileName = $file->getClientOriginalName();
                    $file->move($dirUpload, $fileName);
                    $loc['legal_file'] = asset($dirUpload . '/' . $fileName);
                }
                $il = InternshipLocations::create($loc)->id;
                $arr['internship_location_id'] = $il;
            } else {
                $arr['internship_location_id'] = $request->input('internship_location_id');
            }

            InternshipsStudents::create($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = InternshipsStudents::query();
        $data->select(
            'interships_students.*',

            'student_details.id as student_id',
            'student_details.full_name as student_full_name',
            'student_details.birth_place as student_birth_place',
            'student_details.birth_date as student_birth_date',
            'student_details.gender as student_gender',
            'student_details.religion as student_religion',
            'student_details.nim as student_nim',
            'student_details.nik as student_nik',
            'student_details.home_address as student_home_address',
            'student_details.phone as student_phone',
            'student_details.socmed_instagram as student_socmed_instagram',
            'student_details.socmed_twitter as student_socmed_twitter',
            'student_details.socmed_other as student_socmed_other',
            'student_details.biological_father_name as student_biological_father_name',
            'student_details.biological_mother_name as student_biological_mother_name',
            'student_details.biological_father_phone as student_biological_father_phone',
            'student_details.biological_mother_phone as student_biological_mother_phone',
            'student_details.origin_school as student_origin_school',

            'internship_locations.id as location_id',
            'internship_locations.name as location_name',
            'internship_locations.address as location_address',
            'internship_locations.pic_contact as location_pic_contact',
            'internship_locations.pic_position as location_pic_position',
            'internship_locations.phone as location_phone',
            'internship_locations.legal_file as location_legal_file',

            'internship_periods.id as period_id',
            'internship_periods.name as period_name',
            'internship_periods.start_date as period_start_date',
            'internship_periods.end_date as period_end_date',

            'mentor.id as mentor_id',
            'mentor.name as mentor_name',

            'examiner.id as examiner_id',
            'examiner.name as examiner_name',

            \DB::raw('(select count(*) from logbooks where logbooks.internships_students_id = interships_students.id) as total_logbook')
        );
        $data->join('student_details', 'interships_students.student_id', '=', 'student_details.id')
            ->leftJoin('internship_locations', 'interships_students.internship_location_id', '=', 'internship_locations.id')
            ->leftJoin('internship_periods', 'interships_students.internship_period_id', '=', 'internship_periods.id')
            ->leftJoin('instructors as mentor', 'interships_students.mentor_instructor_id', '=', 'mentor.id')
            ->leftJoin('instructors as examiner', 'interships_students.examiner_instructor_id', '=', 'examiner.id');

        $groups = Session::get('current_roles');
        foreach ($groups as $key => $value) {
            if ($value->name == "student") {
                $data->where('student_id', Auth::user()->id);
            } else if ($value->name == "instructor") {
                $data->where('approval_status', "Disetujui");
                $data->where(function ($query) {
                    $query->where('mentor_instructor_id', Auth::user()->id)
                        ->orWhere('examiner_instructor_id', Auth::user()->id);
                });
            }
        }

        if ($request->input('student_full_name')) {
            $data->where('student_full_name', 'LIKE', '%' . $request->input('student_full_name') . '%');
        }
        if ($request->input('student_nim')) {
            $data->where('student_nim', 'LIKE', '%' . $request->input('student_nim') . '%');
        }
        if ($request->input('location_name')) {
            $data->where('location_name', 'LIKE', '%' . $request->input('location_name') . '%');
        }
        if ($request->input('approval_status')) {
            $data->where('approval_status', 'LIKE', '%' . $request->input('approval_status') . '%');
        }
        if ($request->input('status')) {
            $data->where('status', 'LIKE', '%' . $request->input('status') . '%');
        }


        if ($request->input('sortField') != '') {
            $data->orderBy($request->input('sortField'), $request->input('sortOrder'));
        }
        $paging = $data->count();

        $request->input('pageIndex') != 1 ? $data->offset($request->input('pageSize') * ($request->input('pageIndex') - 1)) : 0;
        $data->limit($request->input('pageSize'));
        $hasil = $data->get();

        return response()->json([
            'data' => $hasil,
            'itemsCount' => $paging
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Edit " . modulName;
        $data['students'] = Students::get();
        $data['locations'] = InternshipLocations::get();
        if ($ada->personal_choice == 1) {
            $data['locations'] = InternshipLocations::withTrashed(true)->get();
        }
        $data['periods'] = InternshipPeriods::get();
        $data['instructors'] = Instructors::get();
        return view('internship.student.edit', $data);
    }

    public function approval(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Approval " . modulName;
        $data['students'] = Students::get();
        return view('internship.student.approval', $data);
    }

    public function info(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Infomation " . modulName;
        $data['students'] = Students::get();
        $data['locations'] = InternshipLocations::withTrashed(true)->get();
        $data['periods'] = InternshipPeriods::get();
        $data['instructors'] = Instructors::get();
        return view('internship.student.info', $data);
    }

    public function instruktur(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Instruktur " . modulName;
        $data['students'] = Students::get();
        $data['instructors'] = Instructors::get();
        return view('internship.student.instruktur', $data);
    }

    public function berkas(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Berkas " . modulName;
        return view('internship.student.berkas', $data);
    }

    public function nilai(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = InternshipsStudents::findOrFail($id);
        $data['data'] = InternshipsStudents::where('id', $id)->first();
        $data['title'] = "Nilai " . modulName;
        return view('internship.student.nilai', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'student_id' => 'required',
            'internship_location_id' => 'required',
            'internship_period_id' => 'required',
            // 'approval_status' => 'required',
            // 'status' => 'required',
            'mentor_instructor_id' => 'required',
            'examiner_instructor_id' => 'required',
        ], [], [
            'student_id' => 'Student',
            'internship_location_id' => 'Internship Location',
            'internship_period_id' => 'Internship Period',
            // 'approval_status' => 'Approval Status',
            // 'status' => 'Status',
            'mentor_instructor_id' => 'Mentor Instructor',
            'examiner_instructor_id' => 'Examiner Instructor',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $arr = [
                'student_id' => $request->input('student_id'),
                'internship_location_id' => $request->input('internship_location_id'),
                'internship_period_id' => $request->input('internship_period_id'),
                // 'approval_status' => $request->input('approval_status'),
                'personal_choice' => $request->input('personal_choice'),
                // 'status' => $request->input('status'),
                'mentor_instructor_id' => $request->input('mentor_instructor_id'),
                'examiner_instructor_id' => $request->input('examiner_instructor_id'),
                'created_by' => Auth::user()->id,
            ];

            if ($request->input('personal_choice') == 1) {
                if ($request->input('approval_status') == "Disetujui") {
                    InternshipLocations::withTrashed()->find($request->input('internship_location_id'))->restore();
                }
            }

            if ($request->hasFile('intership_file')) {
                $file = $request->file('intership_file');
                $dirUpload = 'intership_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['intership_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_report_file')) {
                $file = $request->file('final_report_file');
                $dirUpload = 'final_report_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_report_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('internship_certification_file')) {
                $file = $request->file('internship_certification_file');
                $dirUpload = 'internship_certification_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['internship_certification_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_project_file')) {
                $file = $request->file('final_project_file');
                $dirUpload = 'final_project_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_project_file'] = asset($dirUpload . '/' . $fileName);
            }

            InternshipsStudents::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updateapproval(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'approval_status' => 'required',
            'status' => 'required',
        ], [], [
            'approval_status' => 'Approval Status',
            'status' => 'Status',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            //Check jika data lain di periode yang sama sudah ada approval disetujui, maka data tidak daapt diproses
            $data = InternshipsStudents::where('id', $request->input('id'))->first();
            if ($data->approval_status != "Disetujui" && $request->input('approval_status') == "Disetujui") {
                $check = InternshipsStudents::where('internship_period_id', $data->internship_period_id)->where('student_id', $data->student_id)->where('approval_status', 'Disetujui')->count();
                if ($check >= 1) {
                    return response([
                        'status' => 'failed',
                        'message' => 'Tidak dapat diproses karena ada data intership lain yang telah disetujui di periode yang sama.'
                    ], 500);
                    die();
                }
            }
            $arr = [
                'approval_status' => $request->input('approval_status'),
                'status' => $request->input('status'),
                'modified_by' => Auth::user()->id,
            ];

            InternshipsStudents::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updateinstruktor(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'mentor_instructor_id' => 'required',
            'examiner_instructor_id' => 'required',
        ], [], [
            'mentor_instructor_id' => 'Mentor Instructor',
            'examiner_instructor_id' => 'Examiner Instructor',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $arr = [
                'mentor_instructor_id' => $request->input('mentor_instructor_id'),
                'examiner_instructor_id' => $request->input('examiner_instructor_id')
            ];

            InternshipsStudents::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updateberkas(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required',
        ], [], [
            'id' => 'ID',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            $arr['modified_by'] = Auth::user()->id;

            if ($request->hasFile('intership_file')) {
                $file = $request->file('intership_file');
                $dirUpload = 'intership_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['intership_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_report_file')) {
                $file = $request->file('final_report_file');
                $dirUpload = 'final_report_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_report_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('internship_certification_file')) {
                $file = $request->file('internship_certification_file');
                $dirUpload = 'internship_certification_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['internship_certification_file'] = asset($dirUpload . '/' . $fileName);
            }
            if ($request->hasFile('final_project_file')) {
                $file = $request->file('final_project_file');
                $dirUpload = 'final_project_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['final_project_file'] = asset($dirUpload . '/' . $fileName);
            }

            InternshipsStudents::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updateevaluation(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'evaluation' => 'required|numeric',
        ], [], [
            'evaluation' => 'Evaluation value',
        ]);

        if ($validated->fails()) {
            $err = array();
            foreach ($validated->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    array_push($err, $sub_error . ' <br>');
                }
            }

            return response()->json([
                'status' => 'failed',
                'message' => $err,
            ], Response::HTTP_BAD_REQUEST);
        } else {
            InternshipsStudents::where('id', $request->input('id'))->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        InternshipsStudents::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
