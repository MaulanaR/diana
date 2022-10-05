<?php

namespace App\Http\Controllers;

use App\Models\InternshipLocations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

const modulName = "Lokasi Magang";

class InternshipLocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = modulName;
        return view('internship.location.index', $data);
    }

    public function modal()
    {
        $data['title'] = 'Add ' . modulName;
        return view('internship.location.add', $data);
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
        } else {

            $arr = [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'pic_contact' => $request->input('pic_contact'),
                'pic_position' => $request->input('pic_position'),
                'phone' => $request->input('phone'),
            ];

            if ($request->hasFile('legal_file')) {
                $file = $request->file('legal_file');
                $dirUpload = 'legalfiles';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['legal_file'] = asset($dirUpload . '/' . $fileName);
            }


            InternshipLocations::create($arr);

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
        $data = InternshipLocations::query();
        if ($request->input('name')) {
            $data->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->input('address')) {
            $data->where('address', 'LIKE', '%' . $request->input('address') . '%');
        }
        if ($request->input('pic_contact')) {
            $data->where('pic_contact', 'LIKE', '%' . $request->input('pic_contact') . '%');
        }
        if ($request->input('pic_position')) {
            $data->where('pic_position', 'LIKE', '%' . $request->input('pic_position') . '%');
        }
        if ($request->input('phone')) {
            $data->where('phone', 'LIKE', '%' . $request->input('phone') . '%');
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
        $ada = InternshipLocations::findOrFail($id);
        $data['data'] = InternshipLocations::where('id', $id)->first();
        $data['title'] = "Edit " . modulName;
        return view('internship.location.edit', $data);
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
        } else {
            $arr = [
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'pic_contact' => $request->input('pic_contact'),
                'pic_position' => $request->input('pic_position'),
                'phone' => $request->input('phone')
            ];

            if ($request->hasFile('legal_file')) {
                $file = $request->file('legal_file');
                $dirUpload = 'legalfiles';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['legal_file'] = asset($dirUpload . '/' . $fileName);
            }

            InternshipLocations::where('id', $request->input('id'))->update($arr);

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
        InternshipLocations::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
