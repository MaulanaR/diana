<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\Classes;
use App\Models\CourseBobot;
use App\Models\Courses;
use App\Models\Instructors;
use App\Models\Majors;
use App\Models\StudentCourseGrade;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\Auth;

const modulName = "Courses";

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['periods'] = AcademicPeriods::all();
        $data['majors'] = Majors::all();
        $data['classes'] = Classes::all();
        $data['title'] = modulName;

        $groups = Session::get('current_roles');
        $data['isStudent'] = false;
        $data['isInstructor'] = false;
        foreach ($groups as $key => $value) {
            if ($value->name == "student") {
                $data['isStudent'] = true;
            } else if ($value->name == "instructor") {
                $data['isInstructor'] = true;
            }
        }

        return view('academic.course.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        $data['majors'] = Majors::all();
        $data['periods'] = AcademicPeriods::all();
        $data['instructors'] = Instructors::all();
        $data['classes'] = Classes::all();
        return view('academic.course.add', $data);
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
            'categories' => 'required',
            'semester' => 'required',
            'major_id' => 'required',
            'class_id' => 'required',
            'academic_period_id' => 'required',
            'instructor_id' => 'required',
            'head_instructor_id' => 'required',
            'name' => 'required',
            'sks' => 'required',
            'total_unit' => '',
            'description_unit' => '',
        ], [], [
            'categories' => 'Categories',
            'semester' => 'Semester',
            'major_id' => 'Major',
            'class_id' => 'Class',
            'instructor_id' => 'Instructor',
            'head_instructor_id' => 'Head Instructor',
            'name' => 'Name',
            'sks' => 'Sks',
            'total_unit' => 'Total Unit',
            'description_unit' => 'Description Unit',
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
                'categories' => $request->input('categories'),
                'semester' => $request->input('semester'),
                'major_id' => $request->input('major_id'),
                'academic_period_id' => $request->input('academic_period_id'),
                'class_id' => $request->input('class_id'),
                'instructor_id' => $request->input('instructor_id'),
                'head_instructor_id' => $request->input('head_instructor_id'),
                'name' => $request->input('name'),
                'sks' => $request->input('sks'),
                'total_unit' => $request->input('total_unit'),
                'description_unit' => $request->input('description_unit'),
            ];

            Courses::create($arr);

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

        $data = Courses::query();
        $data->select(
            'courses.*',
            'instructors.name as instructor_name',
            'instructors2.name as instructor2_name',
            "academic_periods.name as academic_period_name",
            "majors.name as major_name",
            "classes.name as class_name"
        );
        $data->leftJoin('academic_periods', 'academic_periods.id', '=', 'courses.academic_period_id');
        $data->leftJoin('majors', 'majors.id', '=', 'courses.major_id');
        $data->leftJoin('classes', 'classes.id', '=', 'courses.class_id');
        $data->leftJoin('instructors', 'courses.head_instructor_id', '=', 'instructors.id');
        $data->leftJoin('instructors as instructors2', 'courses.instructor_id', '=', 'instructors2.id');
        if ($isInstructor) {
            $data->where(function ($query) {
                $query->where('courses.instructor_id', Auth::user()->id)
                    ->orWhere('courses.head_instructor_id', Auth::user()->id);
            });
        }
        if ($isStudent) {
            $data->where(function ($query) {
                $class = \DB::table('classes_students')->where('student_id', Auth::user()->id)->get();
                foreach ($class as $key => $valueClass) {
                    $query->where('courses.class_id', $valueClass->class_id);
                    if ($key == 0) {
                    } else {
                        $query->orWhere('courses.class_id', $valueClass->class_id);
                    }
                }
            });
        }
        if ($request->input('academic_period_name')) {
            $data->where('academic_periods.name', 'LIKE', '%' . $request->input('academic_period_name') . '%');
        }
        if ($request->input('major_name')) {
            $data->where('majors.name', 'LIKE', '%' . $request->input('major_name') . '%');
        }
        if ($request->input('class_name')) {
            $data->where('classes.name', 'LIKE', '%' . $request->input('class_name') . '%');
        }
        if ($request->input('academic_period_id')) {
            $data->where('courses.academic_period_id', $request->input('academic_period_id'));
        }
        if ($request->input('major_id')) {
            $data->where('courses.major_id', $request->input('major_id'));
        }
        if ($request->input('class_id')) {
            $data->where('courses.class_id', $request->input('class_id'));
        }
        if ($request->input('semester')) {
            $data->where('semester', 'LIKE', '%' . $request->input('semester') . '%');
        }
        if ($request->input('categories')) {
            $data->where('categories', 'LIKE', '%' . $request->input('categories') . '%');
        }
        if ($request->input('name')) {
            $data->where('courses.name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->input('sks')) {
            $data->where('sks', 'LIKE', '%' . $request->input('sks') . '%');
        }
        if ($request->input('instructor_name')) {
            $data->where('instructors.name', 'LIKE', '%' . $request->input('instructor_name') . '%');
        }
        if ($request->input('instructor2_name')) {
            $data->where('instructors2.name', 'LIKE', '%' . $request->input('instructor2_name') . '%');
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
        $ada = Courses::findOrFail($id);
        $data['data'] = Courses::where('id', $id)->first();
        $data['instructors'] = Instructors::all();
        $data['periods'] = AcademicPeriods::all();
        $data['majors'] = Majors::where('academic_period_id', $ada->academic_period_id)->get();
        $data['classes'] = Classes::where('academic_period_id', $ada->academic_period_id)->where('major_id', $ada->major_id)->get();
        $data['title'] = "Edit " . modulName;
        return view('academic.course.edit', $data);
    }

    public function editbobot(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = Courses::findOrFail($id);
        $bobot = CourseBobot::where('course_id', $id)->first();
        if ($bobot) {
            $data['data'] = $bobot;
        } else {
            $data['data'] = CourseBobot::create(['course_id' => $id]);
        }
        $data['title'] = "Bobot - " . $ada->name;
        return view('academic.course.bobot', $data);
    }

    public function editnilai(Request $request, $id)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = Courses::findOrFail($id);
        $data['data'] = Courses::where('courses.id', $id)
            ->select(
                'courses.*',
                'instructors.name as instructor_name',
                'instructors2.name as instructor2_name',
                "classes.name as class_name"
            )
            ->leftJoin('classes', 'classes.id', '=', 'courses.class_id')
            ->leftJoin('instructors', 'instructors.id', '=', 'courses.head_instructor_id')
            ->leftJoin('instructors as instructors2', 'instructors2.id', '=', 'courses.instructor_id')
            ->first();
        $data['bobot'] = CourseBobot::where('course_id', $id)->first();

        $data['list_student'] = \DB::table('classes_students')
            ->select(
                "student_details.*",
                "student_course_grade.course_id",
                "student_course_grade.student_id",
                "student_course_grade.uts",
                "student_course_grade.uas",
                "student_course_grade.final",
                "student_course_grade.grade",
            )
            ->where('class_id', $ada->class_id)
            ->leftJoin('student_details', 'student_details.id', '=', 'classes_students.student_id')
            ->leftJoin('student_course_grade', function ($join) use ($ada) {
                $join->on('student_course_grade.student_id', '=', 'classes_students.student_id');
                $join->on('student_course_grade.course_id', '=', \DB::raw($ada->id));
            })
            ->get();
        $data['title'] = "Penilaian - " . $ada->name;
        return view('academic.course.nilai', $data);
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
            'categories' => 'required',
            'semester' => 'required',
            'major_id' => 'required',
            'class_id' => 'required',
            'academic_period_id' => 'required',
            'instructor_id' => 'required',
            'head_instructor_id' => 'required',
            'name' => 'required',
            'sks' => 'required',
            'total_unit' => 'required',
            'description_unit' => '',
        ], [], [
            'categories' => 'Categories',
            'semester' => 'Semester',
            'major_id' => 'Major',
            'class_id' => 'Class',
            'instructor_id' => 'Instructor',
            'head_instructor_id' => 'Head Instructor',
            'name' => 'Name',
            'sks' => 'Sks',
            'total_unit' => 'Total Unit',
            'description_unit' => 'Description Unit',
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
                'categories' => $request->input('categories'),
                'semester' => $request->input('semester'),
                'major_id' => $request->input('major_id'),
                'academic_period_id' => $request->input('academic_period_id'),
                'class_id' => $request->input('class_id'),
                'instructor_id' => $request->input('instructor_id'),
                'head_instructor_id' => $request->input('head_instructor_id'),
                'name' => $request->input('name'),
                'sks' => $request->input('sks'),
                'total_unit' => $request->input('total_unit'),
                'description_unit' => $request->input('description_unit'),
            ];

            Courses::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updatebobot(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required',
            'a_min' => 'required',
            'a_max' => 'required',
            'b_min' => 'required',
            'b_max' => 'required',
            'bplus_min' => 'required',
            'bplus_max' => 'required',
            'c_min' => 'required',
            'c_max' => 'required',
            'cplus_min' => 'required',
            'cplus_max' => 'required',
            'd_min' => 'required',
            'd_max' => 'required',
            'e_min' => 'required',
            'e_max' => 'required',
        ], [], [
            'course_id' => 'Mata Kuliah',
            'a_min' => 'A Min',
            'a_max' => 'A Max',
            'b_min' => 'B Min',
            'b_max' => 'B Max',
            'bplus_min' => 'B Min',
            'bplus_max' => 'B Max',
            'c_min' => 'C Min',
            'c_max' => 'C Max',
            'cplus_min' => 'C Min',
            'cplus_max' => 'C Max',
            'd_min' => 'D Min',
            'd_max' => 'D Max',
            'e_min' => 'E Min',
            'e_max' => 'E Max',
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
                'course_id' => $request->input('course_id'),
                'a_min' => $request->input('a_min'),
                'a_max' => $request->input('a_max'),
                'b_min' => $request->input('b_min'),
                'b_max' => $request->input('b_max'),
                'bplus_min' => $request->input('bplus_min'),
                'bplus_max' => $request->input('bplus_max'),
                'c_min' => $request->input('c_min'),
                'c_max' => $request->input('c_max'),
                'cplus_min' => $request->input('cplus_min'),
                'cplus_max' => $request->input('cplus_max'),
                'd_min' => $request->input('d_min'),
                'd_max' => $request->input('d_max'),
                'e_min' => $request->input('e_min'),
                'e_max' => $request->input('e_max'),
            ];

            CourseBobot::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function updatenilai(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'course_id' => 'required',
            'file' => 'file',
            'student_id' => 'required|array',
        ], [], [
            'course_id' => 'Mata Kuliah',

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
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $dirUpload = 'nilai';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['file'] = asset($dirUpload . '/' . $fileName);
                Courses::where('id', $request->input('course_id'))->update($arr);
            }

            StudentCourseGrade::where('course_id', $request->input('course_id'))->delete();

            $sts = $request->input('student_id');
            foreach ($sts as $key => $student) {
                $input = [
                    'course_id' => $request->input('course_id'),
                    'student_id' => $student,
                    'uts' => $request->input('uts')[$key],
                    'uas' => $request->input('uas')[$key],
                    'final' => $request->input('final')[$key],
                    'grade' => $request->input('grade')[$key],
                ];
                StudentCourseGrade::create($input);
            }

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
        Courses::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }

    //=======TRANSKIP=========
    public function indextranskip()
    {
        $data['periods'] = AcademicPeriods::all();
        $data['majors'] = Majors::all();
        $data['classes'] = Classes::all();
        $data['title'] = "Transkip Nilai";

        $groups = Session::get('current_roles');
        $data['isStudent'] = false;
        $data['isInstructor'] = false;
        foreach ($groups as $key => $value) {
            if ($value->name == "student") {
                $data['isStudent'] = true;
            } else if ($value->name == "instructor") {
                $data['isInstructor'] = true;
            }
        }

        return view('academic.course.listtranskip', $data);
    }

    public function showtranskip(Request $request)
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

        $data = \DB::table('classes_students');
        $data->select(
            'classes_students.*',

            'classes.id as class_id',
            'classes.code as class_code',
            'classes.name as class_name',
            'classes.description as class_description',
            'classes.major_id as class_major_id',
            'classes.academic_period_id as class_academic_period_id',

            'student_details.*',
            "academic_periods.name as academic_period_name",
            "majors.name as major_name",
        );
        $data->leftJoin('student_details', 'student_details.id', '=', 'classes_students.student_id');
        $data->leftJoin('classes', 'classes.id', '=', 'classes_students.class_id');
        $data->leftJoin('academic_periods', 'academic_periods.id', '=', 'classes.academic_period_id');
        $data->leftJoin('majors', 'majors.id', '=', 'classes.major_id');
        if ($isStudent) {
            $data->where('classes_students.student_id', Auth::user()->id);
        }
        if ($request->input('academic_period_name')) {
            $data->where('academic_periods.name', 'LIKE', '%' . $request->input('academic_period_name') . '%');
        }
        if ($request->input('major_name')) {
            $data->where('majors.name', 'LIKE', '%' . $request->input('major_name') . '%');
        }
        if ($request->input('academic_period_id')) {
            $data->where('classes.academic_period_id', $request->input('academic_period_id'));
        }
        if ($request->input('major_id')) {
            $data->where('classes.major_id', $request->input('major_id'));
        }
        if ($request->input('class_id')) {
            $data->where('classes_students.class_id', $request->input('class_id'));
        }
        if ($request->input('nim')) {
            $data->where('student_details.nim', 'LIKE', '%' . $request->input('nim') . '%');
        }
        if ($request->input('full_name')) {
            $data->where('student_details.full_name', 'LIKE', '%' . $request->input('full_name') . '%');
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

    public function cetaktranskip(Request $request, $period_id, $class_id, $student_id, $semester)
    {
        if ($semester == 1) {
            $fSemester = "Ganjil";
            $data['semester'] = "I";
        } else {
            $fSemester = "Genap";
            $data['semester'] = "II";
        }
        $data['student'] = Students::where('id', $student_id)->first();
        $data['class'] = Classes::select("classes.*", "majors.name as major_name")
            ->where('classes.id', $class_id)
            ->leftJoin('majors', 'majors.id', '=', 'classes.major_id')
            ->first();
        $data['period'] = AcademicPeriods::where('id', $period_id)->first();
        $courses = Courses::where('academic_period_id', $period_id)
            ->where('class_id', $class_id)
            // ->where('semester', $fSemester)
            ->leftJoin('student_course_grade', function ($join) use ($student_id) {
                $join->on('student_course_grade.course_id', '=', 'courses.id');
                $join->on('student_course_grade.student_id', '=', \DB::raw($student_id));
            })
            ->get();
        $data['courses'] = $courses;
        $totalSks = 0;
        $totalSksxNilai = 0;
        $ipk = 0;
        foreach ($courses as $course) {
            $totalSks += $course->sks;
            $totalSksxNilai += ($course->sks * $course->final);
        }
        if ($totalSks != 0) {
            $ipk = $totalSksxNilai / $totalSks;
        }

        $data['totalsks'] = $totalSks;
        $data['totalsksxnilai'] = $totalSksxNilai;
        $data['ipk'] = $ipk;
        $data['title'] = "Cetak Transkip Nilai";
        return view('academic.course.cetaktranskip', $data);
    }
}
