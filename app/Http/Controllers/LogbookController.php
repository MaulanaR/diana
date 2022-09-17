<?php

namespace App\Http\Controllers;

use App\Models\InternshipLocations;
use App\Models\InternshipsStudents;
use App\Models\Logbook;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Logbook";

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $is = InternshipsStudents::where('id', $id)->first();
        $st = Students::where('id', $is->student_id)->first();
        $il = InternshipLocations::where('id', $is->internship_location_id)->first();
        $data['title'] = modulName . " (" . $st->full_name . " - " . $il->name . ")";
        $data['id'] = $id;
        return view('internship.student.logbook.index', $data);
    }

    public function modal($id)
    {
        $data['id'] = $id;
        $data['title'] = 'Add ' . modulName;
        return view('internship.student.logbook.add', $data);
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
            'internships_students_id' => 'required',
            'date' => 'required',
            'file' => 'required',
            'description' => 'required',
        ], [], [
            'internships_students_id' => 'InternshipID',
            'date' => 'Tanggal',
            'file' => 'File',
            'description' => 'Deskripsi',
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

            $file = $request->file('file');
            $dirUpload = 'logbook_file';
            $fileName = $file->getClientOriginalName();
            $file->move($dirUpload, $fileName);
            $arr = [
                'internships_students_id' => $request->input('internships_students_id'),
                'date' => $request->input('date'),
                'description' => $request->input('description'),
                'file' => asset($dirUpload . '/' . $fileName),
            ];

            Logbook::create($arr);

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
    public function show(Request $request, $id)
    {
        $data = Logbook::query();
        if ($request->input('date')) {
            $data->where('date', 'LIKE', '%' . $request->input('date') . '%');
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
        $ada = Logbook::findOrFail($id);
        $data['data'] = Logbook::where('id', $id)->first();
        $data['title'] = "Edit " . modulName;
        return view('internship.student.logbook.edit', $data);
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
            'date' => 'required',
            'description' => 'required',
        ], [], [
            'date' => 'Tanggal',
            'file' => 'File',
            'description' => 'Deskripsi',
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
                'date' => $request->input('date'),
                'description' => $request->input('description'),
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $dirUpload = 'legalfiles';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['file'] = asset($dirUpload . '/' . $fileName);
            }

            Logbook::where('id', $request->input('id'))->update($arr);

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
        Logbook::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
