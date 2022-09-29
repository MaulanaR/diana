<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Instructors;
use App\Models\Majors;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index()
    {
        $data['title'] = 'Manajemen Users';
        return view('users', $data);
    }

    public function show(Request $request)
    {
        $data = User::query();
        $data->select('users.id', 'users.name', 'users.username', 'users.picture', 'users.email', DB::raw('GROUP_CONCAT(alus_g.name SEPARATOR " , ") as "group"'));
        $data->leftJoin('alus_ug', 'alus_ug.user_id', '=', 'users.id');
        $data->leftJoin('alus_g', 'alus_g.id', '=', 'alus_ug.group_id');

        if ($request->input('id')) {
            $data->where('users.id', $request->input('id'));
        }

        if ($request->input('name')) {
            $data->where('users.name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if ($request->input('username')) {
            $data->where('users.username', 'LIKE', '%' . $request->input('username') . '%');
        }

        if ($request->input('email')) {
            $data->where('users.email', 'LIKE', '%' . $request->input('email') . '%');
        }

        if ($request->input('group')) {
            $data->where('alus_g.name', 'LIKE', '%' . $request->input('group') . '%');
        }


        if ($request->input('sortField') != '') {
            $data->orderBy($request->input('sortField'), $request->input('sortOrder'));
        }

        $data->groupByRaw('users.id,users.name,users.username,users.email,users.picture');
        $paging = $data->count();

        $request->input('pageIndex') != 1 ? $data->offset($request->input('pageSize') * ($request->input('pageIndex') - 1)) : 0;
        $data->limit($request->input('pageSize'));
        $hasil = $data->get();
        //dd($hasil);
        foreach ($hasil as $key => $value_hasil) {
            if (!file_exists(public_path('avatar/' . $value_hasil->picture))) {
                $hasil[$key]['picture'] = 'user.png';
            }
        }

        return response()->json([
            'data' => $hasil,
            'itemsCount' => $paging
        ]);
    }

    public function modal()
    {
        $data['list'] = DB::table('alus_g')->get();
        $data['majors'] = Majors::all();
        return view('users.add', $data);
    }

    public function store(Request $request)
    {
        $arrVal = [
            'name' => 'required|min:1|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:5',
            'avatar' => 'image',
            'group.*' => 'required',
        ];

        foreach ($request->input('group') as $key) {
            //Jika group student / instructor maka insert ke table detailnya
            $g = Group::where('id', $key)->first();
            if ($g->name == "student") {
                $arrVal['major_id'] = 'required';
            }
        }

        $validated = Validator::make($request->all(), $arrVal);

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


            $data = array(
                'username'           => $request->input('name'),
                'name'           => $request->input('name'),
                'email'              => $request->input('email'),
                'major_id'              => $request->input('major_id'),
                'password'           => bcrypt($request->input('password'))
            );

            if ($request->file('avatar')) {
                $file = $request->file('avatar');
                //upload proses
                $data['picture'] = $file->getClientOriginalName();

                $file->move('avatar', $file->getClientOriginalName());
            } else {
                $data['picture'] = 'user.png';
            }

            $user = User::create($data);

            //insert to selected group
            if ($request->input('group')) {
                $gr = array();
                foreach ($request->input('group') as $key) {
                    $gr[] = array(
                        'user_id' => $user->id,
                        'group_id' => $key,
                    );
                    //Jika group student / instructor maka insert ke table detailnya
                    $g = Group::where('id', $key)->first();
                    if ($g->name == "student") {
                        Students::firstOrCreate(['id' => $user->id, 'full_name' => $request->input('name'), 'major_id' => $request->input('major_id')]);
                    } else if ($g->name == "instructor") {
                        Instructors::firstOrCreate(['id' => $user->id, 'name' => $request->input('name'), 'email' => $request->input('email')]);
                    }
                }

                DB::table('alus_ug')->insert($gr);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function edit(Request $request)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = User::find($request->input('id'));
        if ($ada->count() > 0) {
            //ada
            $data['data'] = User::where('id', $request->input('id'))->first();
            $data['groups'] = DB::table('users')->where('users.id', $request->input('id'))->select('alus_ug.group_id')->leftJoin('alus_ug', 'alus_ug.user_id', '=', 'users.id')->get();
            $data['list'] = DB::table('alus_g')->get();
            $data['majors'] = Majors::all();

            return view('users.edit', $data);
        } else {
            //tidak ada
            return "Tidak ada data";
        }
    }

    public function updatex(Request $request)
    {
        $cek = '';
        $cekpw = '';
        if ($request->input('email_lama') != $request->input('email')) {
            $cek = '|unique:users,email';
        }

        if ($request->input('password') != '') {
            $cekpw = '|required|confirmed|min:5';
        }

        $arrVal = [
            'name'      => 'required|max:50',
            'id'        => 'required',
            'email'     => 'required|email' . $cek,
            'password'  => $cekpw,
            'avatar'    => 'image',
            'group.*'   => 'required',
        ];

        foreach ($request->input('group') as $key) {
            //Jika group student / instructor maka insert ke table detailnya
            $g = Group::where('id', $key)->first();
            if ($g->name == "student") {
                $arrVal['major_id'] = 'required';
            }
        }

        $validated = Validator::make($request->all(), $arrVal);

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

            $data = array(
                'username' => $request->input('name'),
                'name'     => $request->input('name'),
                'major_id' => $request->input('major_id'),
            );

            if ($request->input('email_lama') != $request->input('email')) {
                $data['email'] = $request->input('email');
            }

            if ($request->input('password') != '') {
                $data['password'] = bcrypt($request->input('password'));
            }

            if ($request->file('avatar')) {
                $file = $request->file('avatar');
                //upload proses
                $data['picture'] = $file->getClientOriginalName();

                $file->move('avatar', $file->getClientOriginalName());

                if ($request->input('avatar_lama') != 'user.png') {
                    //remove old avatar file 
                    File::delete(public_path("avatar/" . $request->input('avatar_lama')));
                }
            }

            User::where('id', $request->input('id'))->update($data);

            //insert to selected group
            if ($request->input('group')) {
                $gr = array();
                foreach ($request->input('group') as $key) {
                    $gr[] = array(
                        'user_id' => $request->input('id'),
                        'group_id' => $key,
                    );

                    //Jika group student / instructor maka insert ke table detailnya
                    $g = Group::where('id', $key)->first();
                    if ($g->name == "student") {
                        $ada = Students::findOrFail($request->input('id'));
                        if ($ada) {
                            $ada->full_name = $request->input('name');
                            $ada->major_id = $request->input('major_id');
                            $ada->save();
                        } else {
                            Students::create(['id' => $request->input('id'), 'full_name' => $request->input('name'), 'major_id' => $request->input('major_id')]);
                        }
                    } else if ($g->name == "instructor") {
                        Instructors::firstOrCreate(['id' => $request->input('id'), 'name' => $request->input('name')]);
                    }
                }

                //hapus data lama di alus_ug terkait user ini
                DB::table('alus_ug')->where('user_id', $request->input('id'))->delete();

                DB::table('alus_ug')->insert($gr);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $data = User::where('id', $request->input('id'))->first();

        if ($data->picture != 'user.png') {
            //delete avatar
            File::delete(public_path("avatar/" . $data->picture));
        }

        //delete from db
        User::destroy($request->input('id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }
}
