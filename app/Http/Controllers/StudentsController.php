<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
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
     * @param  \App\Models\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function show(Students $students)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function edit(Students $students)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Students $students)
    {
        $validated = Validator::make($request->all(), [
            'full_name' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'nim' => 'required',
            'nik' => 'required',
            'home_address' => 'required',
            'phone' => 'numeric',
            'biological_father_name' => 'required',
            'biological_mother_name' => 'required',
            'biological_father_phone' => 'numeric',
            'biological_mother_phone' => 'numeric',
            'origin_school' => 'required',
            'major_origin_school' => 'required',
            'district_origin_school' => 'required',
            'province_origin_school' => 'required',
            'avatar' => 'file',
            'family_card_file' => 'file',
            'id_card_file' => 'file',
            'statement_letter_file' => 'file',
            'certificate_last_education_file' => 'file',
        ], [], [
            'full_name' => 'Full Name',
            'birth_place' => 'Birth Place',
            'birth_date' => 'Birth Date',
            'gender' => 'Gender',
            'religion' => 'Religion',
            'nim' => 'NIM',
            'nik' => 'NIK',
            'home_address' => 'Home Address',
            'phone' => 'Phone',
            'biological_father_name' => 'Biological Father Name',
            'biological_mother_name' => 'Biological Mother Name',
            'biological_father_phone' => 'Biological Father Phone',
            'biological_mother_phone' => 'Biological Mother Phone',
            'origin_school' => 'Origin School',
            'major_origin_school' => 'Major Origin School',
            'district_origin_school' => 'District Origin School',
            'province_origin_school' => 'Province Origin School',
            'avatar' => 'Avatar',
            'family_card_file' => 'Family Card File',
            'id_card_file' => 'Id Card File',
            'statement_letter_file' => 'Statement Letter File',
            'certificate_last_education_file' => 'Certificate Last Education File',
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
                'full_name' => $request->input('full_name'),
                'birth_place' => $request->input('birth_place'),
                'birth_date' => $request->input('birth_date'),
                'gender' => $request->input('gender'),
                'religion' => $request->input('religion'),
                'nim' => $request->input('nim'),
                'nik' => $request->input('nik'),
                'home_address' => $request->input('home_address'),
                'phone' => $request->input('phone'),
                'socmed_username' => $request->input('socmed_username'),
                'biological_father_name' => $request->input('biological_father_name'),
                'biological_mother_name' => $request->input('biological_mother_name'),
                'biological_father_phone' => $request->input('biological_father_phone'),
                'biological_mother_phone' => $request->input('biological_mother_phone'),
                'origin_school' => $request->input('origin_school'),
                'major_origin_school' => $request->input('major_origin_school'),
                'district_origin_school' => $request->input('district_origin_school'),
                'province_origin_school' => $request->input('province_origin_school'),
                'family_card_file' => $request->input('family_card_file'),
                'id_card_file' => $request->input('id_card_file'),
                'statement_letter_file' => $request->input('statement_letter_file'),
                'certificate_last_education_file' => $request->input('certificate_last_education_file'),
            ];

            $arr['socmed_instagram'] = 0;
            $arr['socmed_twitter'] = 0;
            $arr['socmed_other'] = 0;

            if ($request->input('socmed') == "instagram") {
                $arr['socmed_instagram'] = 1;
            } else if ($request->input('socmed') == "twitter") {
                $arr['socmed_twitter'] = 1;
            } else if ($request->input('socmed') == "other") {
                $arr['socmed_other'] = 1;
            }

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $dirUpload = 'avatar';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['avatar'] = $fileName;
            }

            if ($request->input('full_name') != "") {
                $user = [
                    'name' => $request->input('full_name'),
                ];
                if (isset($arr['avatar'])) {
                    $user['picture'] = $arr['avatar'];
                }
                User::where('id', $request->input('id'))->update($user);
            }

            if ($request->hasFile('family_card_file')) {
                $file = $request->file('family_card_file');
                $dirUpload = 'family_card_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['family_card_file'] = asset($dirUpload . '/' . $fileName);
            }

            if ($request->hasFile('id_card_file')) {
                $file = $request->file('id_card_file');
                $dirUpload = 'id_card_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['id_card_file'] = asset($dirUpload . '/' . $fileName);
            }

            if ($request->hasFile('statement_letter_file')) {
                $file = $request->file('statement_letter_file');
                $dirUpload = 'statement_letter_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['statement_letter_file'] = asset($dirUpload . '/' . $fileName);
            }

            if ($request->hasFile('certificate_last_education_file')) {
                $file = $request->file('certificate_last_education_file');
                $dirUpload = 'certificate_last_education_file';
                $fileName = $file->getClientOriginalName();
                $file->move($dirUpload, $fileName);
                $arr['certificate_last_education_file'] = asset($dirUpload . '/' . $fileName);
            }

            Students::where('id', $request->input('id'))->update($arr);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Students  $students
     * @return \Illuminate\Http\Response
     */
    public function destroy(Students $students)
    {
        //
    }
}
