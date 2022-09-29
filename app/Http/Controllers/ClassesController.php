<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\Classes;
use App\Models\Majors;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Classes";

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = modulName;
        return view('academic.class.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        $data['students'] = Students::all();
        $data['academic_period'] = AcademicPeriods::all();
        $data['majors'] = Majors::all();
        return view('academic.class.add', $data);
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
            'code' => 'required',
            'name' => 'required',
            'students' => 'required|array',
            'academic_period_id' => 'required',
            'major_id' => 'required',
        ], [], [
            'code' => 'Code',
            'name' => 'Name',
            'academic_period_id' => 'Academic Period',
            'major_id' => 'Major',
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
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'academic_period_id' => $request->input('academic_period_id'),
                'major_id' => $request->input('major_id'),
            ];

            $cId = Classes::create($arr)->id;
            $sts = $request->input('students');
            foreach ($sts as $key => $student) {
                $input = [
                    'class_id' => $cId,
                    'student_id' => $student
                ];
                \DB::table('classes_students')->Insert($input);
            }

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
        $data = Classes::query();
        $data->select(
            'classes.*',
            \DB::raw("(SELECT COUNT(*) FROM classes_students WHERE class_id = classes.id) as total_student"),
            "academic_periods.name as academic_period_name",
            "majors.name as major_name"
        );
        $data->leftJoin('academic_periods', 'academic_periods.id', '=', 'classes.academic_period_id');
        $data->leftJoin('majors', 'majors.id', '=', 'classes.major_id');
        if ($request->input('academic_period_name')) {
            $data->where('academic_periods.name', 'LIKE', '%' . $request->input('academic_period_name') . '%');
        }
        if ($request->input('major_name')) {
            $data->where('majors.name', 'LIKE', '%' . $request->input('major_name') . '%');
        }
        if ($request->input('code')) {
            $data->where('code', 'LIKE', '%' . $request->input('code') . '%');
        }
        if ($request->input('name')) {
            $data->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->input('description')) {
            $data->where('description', 'LIKE', '%' . $request->input('description') . '%');
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
        $ada = Classes::findOrFail($id);
        $data['data'] = Classes::where('id', $id)->first();
        $data['students'] = \DB::table('classes_students')->where('class_id', $id)->get()->pluck('student_id')->toArray();
        $data['list_student'] = Students::where('major_id', $ada->major_id)->get();
        $data['academic_period'] = AcademicPeriods::all();
        $data['title'] = "Edit " . modulName;
        $data['majors'] = Majors::where('academic_period_id', $ada->academic_period_id)->get();
        return view('academic.class.edit', $data);
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
            'code' => 'required',
            'name' => 'required',
            'students' => 'required|array',
            'academic_period_id' => 'required',
            'major_id' => 'required',
        ], [], [
            'code' => 'Code',
            'name' => 'Name',
            'academic_period_id' => 'Academic Period',
            'major_id' => 'Major',
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
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'academic_period_id' => $request->input('academic_period_id'),
                'major_id' => $request->input('major_id'),
            ];

            Classes::where('id', $request->input('id'))->update($arr);

            \DB::table('classes_students')->where('class_id', $request->input('id'))->delete();
            $sts = $request->input('students');
            foreach ($sts as $key => $student) {
                $input = [
                    'class_id' => $request->input('id'),
                    'student_id' => $student
                ];
                \DB::table('classes_students')->Insert($input);
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
        Classes::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
