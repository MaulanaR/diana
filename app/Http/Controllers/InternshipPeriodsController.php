<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\InternshipPeriods;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Internship Periods";

class InternshipPeriodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = modulName;
        return view('internship.period.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        $data['academic_period'] = AcademicPeriods::all();
        return view('internship.period.add', $data);
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
            'academic_period_id' => 'required'
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
            InternshipPeriods::create($request->all());

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
        $data = InternshipPeriods::query();
        $data->select('*', \DB::raw("(
            SELECT
                COUNT(*)
            FROM
                interships_students
            WHERE
                internship_period_id = id
            AND personal_choice = 1
        ) as personal_choice"), \DB::raw("(
            SELECT
                COUNT(*)
            FROM
                interships_students
            WHERE
                internship_period_id = id
            AND personal_choice = 0) as academic_choice"));

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
        $ada = InternshipPeriods::findOrFail($id);
        $data['data'] = InternshipPeriods::where('id', $id)->first();
        $data['title'] = "Edit " . modulName;
        $data['academic_period'] = AcademicPeriods::all();
        return view('internship.period.edit', $data);
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
            'academic_period_id' => 'required'
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
            InternshipPeriods::where('id', $request->input('id'))->update($request->all());

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
        InternshipPeriods::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
