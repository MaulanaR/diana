<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Periode Akademik";

class AcademicPeriodsController extends Controller
{
    public function index()
    {
        $data['title'] = modulName;
        return view('academic.period.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        $data['academic_period'] = AcademicPeriods::all();
        return view('academic.period.add', $data);
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
            'name' => 'required|min:2',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ], [], [
            'name' => 'Nama',
            'start_date' => 'Tanggal dibuka',
            'end_date' => 'Tanggal ditutup',
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
            AcademicPeriods::create($request->all());

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
        $data = AcademicPeriods::query();
        $data->select('*');

        if ($request->input('name')) {
            $data->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->input('start_date')) {
            $data->where('start_date', 'LIKE', '%' . $request->input('start_date') . '%');
        }
        if ($request->input('end_date')) {
            $data->where('end_date', 'LIKE', '%' . $request->input('end_date') . '%');
        }

        if ($request->input('sortField') != '') {
            $data->orderBy($request->input('sortField'), $request->input('sortOrder'));
        }
        $paging = $data->count();

        $request->input('pageIndex') != 1 ? $data->offset($request->input('pageSize') * ($request->input('pageIndex') - 1)) : 0;
        $data->limit($request->input('pageSize'));
        $hasil = $data->get();

        foreach ($hasil as $key => $data) {
            $allClass = Classes::query();
            $allClass->select(
                'classes.*',
                \DB::raw("(SELECT COUNT(*) FROM classes_students WHERE class_id = classes.id) as total_student"),
                \DB::raw("(SELECT COUNT(*) FROM classes_students LEFT JOIN student_details ON student_details.id = classes_students.student_id WHERE class_id = classes.id AND student_details.gender='male') as total_student_male"),
                \DB::raw("(SELECT COUNT(*) FROM classes_students LEFT JOIN student_details ON student_details.id = classes_students.student_id WHERE class_id = classes.id AND student_details.gender='female') as total_student_female")
            );
            $allClass->where('academic_period_id', $data->id);
            $h = $allClass->first();
            $hasil[$key]['total_student'] = isset($h->total_student) ? $h->total_student : 0;
            $hasil[$key]['total_student_male'] = isset($h->total_student_male) ? $h->total_student_male : 0;
            $hasil[$key]['total_student_female'] = isset($h->total_student_female) ? $h->total_student_female : 0;
        }
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
        $ada = AcademicPeriods::findOrFail($id);
        $data['data'] = AcademicPeriods::where('id', $id)->first();
        $data['title'] = "Edit " . modulName;
        return view('academic.period.edit', $data);
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
            'name' => 'required|min:2',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ], [], [
            'name' => 'Nama',
            'start_date' => 'Tanggal dibuka',
            'end_date' => 'Tanggal ditutup',
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
            AcademicPeriods::where('id', $request->input('id'))->update($request->all());

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
        AcademicPeriods::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
