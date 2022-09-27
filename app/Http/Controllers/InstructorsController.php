<?php

namespace App\Http\Controllers;

use App\Models\Instructors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class InstructorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructors  $instructors
     * @return \Illuminate\Http\Response
     */
    public function show(Instructors $instructors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructors  $instructors
     * @return \Illuminate\Http\Response
     */
    public function edit(Instructors $instructors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instructors  $instructors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructors $instructors)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
        ], [], [
            'name' => 'Full Name',
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
                'gender' => $request->input('gender'),
                'ttl' => $request->input('ttl'),
                'nuptk' => $request->input('nuptk'),
                'status_perkawinan' => $request->input('status_perkawinan'),
                'provinsi' => $request->input('provinsi'),
                'kecamatan' => $request->input('kecamatan'),
                'kota' => $request->input('kota'),
                'kelurahan' => $request->input('kelurahan'),
                'address' => $request->input('address'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'kode_pos' => $request->input('kode_pos'),
                'telp_rumah' => $request->input('telp_rumah'),
                'hp' => $request->input('hp'),
                'email' => $request->input('email'),
                'npwp' => $request->input('npwp'),
                'status_kepegawaian' => $request->input('status_kepegawaian'),
                'nip' => $request->input('nip'),
                'pangkat' => $request->input('pangkat'),
                'tmt_pns' => $request->input('tmt_pns'),
                'nama_pasangan' => $request->input('nama_pasangan'),
                'pekerjaan' => $request->input('pekerjaan'),
                'nip_pasangan' => $request->input('nip_pasangan'),
            ];

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $dirUpload = 'avatar';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['avatar'] = $fileName;
            }

            if ($request->input('name') != "") {
                $user = [
                    'name' => $request->input('name'),
                ];
                if (isset($arr['avatar'])) {
                    $user['picture'] = $arr['avatar'];
                }
                User::where('id', $request->input('id'))->update($user);
            }

            if ($request->input('email') != "") {
                $user = [
                    'email' => $request->input('email'),
                ];
                if (isset($arr['avatar'])) {
                    $user['picture'] = $arr['avatar'];
                }
                User::where('id', $request->input('id'))->update($user);
            }

            Instructors::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructors  $instructors
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructors $instructors)
    {
        //
    }
}
