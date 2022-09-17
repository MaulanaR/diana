<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Roles;
use App\Models\Group;
use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\ManajemenApi;



class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
    }

    public function index()
    {
        $data['title'] = 'Manajemen Roles';
        return view('group', $data);
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
        // $validated = $request->validate([
        //     'Nama' => 'required|max:50|min:10',
        //     'Deskripsi' => 'required',
        // ]);

        $validated = Validator::make($request->all(), [
            'name' => 'required|max:50|min:5',
            'description' => 'required|min:10',
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

            $data = array(
                'name' => $request->post('name'),
                'description' => $request->post('description')
            );

            Group::insert($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Input!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = DB::table('alus_g')
            ->select('id as id', 'name as name', 'description as description');

        if ($request->input('id') != '') {
            $data->where('id', 'like', '%' . $request->input('id') . '%');
        }
        if ($request->input('name') != '') {
            $data->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('description') != '') {
            $data->where('description', 'like', '%' . $request->input('description') . '%');
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function permission(Request $request)
    {
        $ada = DB::table('alus_g')->where('id', $request->input('id'));
        if ($ada->count() > 0) {
            //ada
            $data['id'] = $request->input('id');
            $sql = DB::table('alus_mga')->where('id_group', $request->input('id'))->get();
            foreach ($sql as $oo) {
                $data['menus'][] = $oo->id_menu;
                $data['canad'][] = $oo->can_add;
                $data['canedit'][] = $oo->can_edit;
                $data['candelet'][] = $oo->can_delete;
                $data['canview'][] = $oo->can_view;
            }
            $data['result'] = (new Menus)->all_tree();
            return view('group.permission', $data);
        } else {
            //tidak ada
            return "Tidak ada data";
        }
    }

    public function updatepermission(Request $request)
    {
        $id_group = $request->input('id_group');
        $mlist = $request->input('bot');
        $result = array();
        $i = 0;
        $sum = 0;
        foreach ($mlist as $key => $val) {
            if ($val) {
                //delete hak sebelumnya clear all 
                DB::table('alus_mga')->where('id_group', $request->input('id_group'))->delete();
                //buat baru
                $result[] = array(
                    "id_group"      => $id_group,
                    "id_menu"      => $request->input('menu')[$val],
                    "can_view"      => $request->input('canview')[$val],
                    "can_edit"      => $request->input('canedit')[$val],
                    "can_add"      => $request->input('canadd')[$val],
                    "can_delete" => $request->input('candelete')[$val]
                );
            }
        }

        DB::table('alus_mga')->insert($result);
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Update!'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:50|min:5',
            'description' => 'required|min:10',
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

            $data = array(
                'name' => $request->post('name'),
                'description' => $request->post('description')
            );

            $group = new Group();
            $group->update($request->post('id'), $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Update!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Group $group)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required'
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

            $group = new Group();
            $group->destroyx($request->post('id'));

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil!'
            ]);
        }
    }
}
