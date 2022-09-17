<?php

namespace App\Http\Controllers;

use App\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = 'Manajemen Menus';
        return view('menus',$data);
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
        $validated = Validator::make($request->all(), [
            'name'    => 'required|min:5',
            'uri'     => 'required',
            'order'   => 'numeric|required',
            'parent'  => 'numeric',
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
                'menu_parent'  => $request->input('parent'),
                'menu_nama'    => $request->input('name'),
                'menu_uri'     => $request->input('uri'),
                'menu_target'  => $request->input('target'),
                'menu_icon'    => $request->input('icon'),
                'order_num'    => $request->input('order'),
            );

            Menus::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Update!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menus  $menus
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = Menus::query();
        $data->select('alus_mg.*', DB::raw('IFNULL( parent.menu_nama, \'Ini Parent Menu\') as menu_parent_nama'));
        $data->leftJoin('alus_mg as parent', 'parent.menu_id', '=', 'alus_mg.menu_parent');
        
        if($request->input('menu_nama'))
        {
            $data->where('alus_mg.menu_nama', 'like' , '%'.$request->input('menu_nama').'%');
        }
        
        if($request->input('menu_parent_nama'))
        {
            $data->where('parent.menu_nama', 'like' , '%'.$request->input('menu_parent_nama').'%');
        }
        
        if($request->input('menu_uri'))
        {
            $data->where('alus_mg.menu_uri', 'like' , '%'.$request->input('menu_uri').'%');
        }
        
        if($request->input('order_num'))
        {
            $data->where('alus_mg.order_num', 'like' , '%'.$request->input('order_num').'%');
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
     * @param  \App\Models\Menus  $menus
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //validasi dulu id yang dikirim ada atau tidak
        $ada = DB::table('alus_mg')->where('menu_id', $request->input('menu_id'));
        if($ada->count() > 0)
        {
            //ada
            $data['data'] = $ada->first();
            $data['tree'] = (new Menus)->all_tree();
            return view('menus.edit',$data);
        }else{
            //tidak ada
            return "Tidak ada data";
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menus  $menus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menus $menus)
    {
        $validated = Validator::make($request->all(), [
            'id'      => 'required',
            'name'    => 'required|min:5',
            'uri'     => 'required',
            'order'   => 'numeric|required',
            'parent'  => 'numeric',
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
                'menu_parent'  => $request->input('parent'),
                'menu_nama'    => $request->input('name'),
                'menu_uri'     => $request->input('uri'),
                'menu_target'  => $request->input('target'),
                'menu_icon'    => $request->input('icon'),
                'order_num'    => $request->input('order'),
            );

            Menus::where('menu_id', $request->post('id'))
            ->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Update!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menus  $menus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Menus::destroy($request->input('menu_id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus!'
        ]);
    }

    public function modal()
    {
        $data['tree'] = (new Menus)->all_tree();
        return view('menus.add',$data);
    }
}
