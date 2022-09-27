<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriods;
use App\Models\Majors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Majors";

class MajorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = modulName;
        return view('academic.major.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        $data['academic_period'] = AcademicPeriods::all();
        return view('academic.major.add', $data);
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
            'academic_period_id' => 'required',
        ], [], [
            'code' => 'Code',
            'name' => 'Name',
            'academic_period_id' => 'Academic Period',
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
                'academic_period_id' => $request->input('academic_period_id'),
                'description' => $request->input('description'),
            ];

            Majors::create($arr);

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
        $data = Majors::query();
        $data->select('majors.*', 'academic_periods.name as academic_period_name');
        $data->leftJoin('academic_periods', 'academic_periods.id', '=', 'majors.academic_period_id');
        if ($request->input('academic_period_name')) {
            $data->where('academic_periods.name', 'LIKE', '%' . $request->input('academic_period_name') . '%');
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
        $ada = Majors::findOrFail($id);
        $data['data'] = Majors::where('id', $id)->first();
        $data['academic_period'] = AcademicPeriods::all();
        $data['title'] = "Edit " . modulName;
        return view('academic.major.edit', $data);
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
            'academic_period_id' => 'required',
        ], [], [
            'code' => 'Code',
            'name' => 'Name',
            'academic_period_id' => 'Academic Period',
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
            ];

            Majors::where('id', $request->input('id'))->update($arr);

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
        Majors::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
