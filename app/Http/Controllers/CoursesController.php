<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Instructors;
use App\Models\Majors;
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
}
