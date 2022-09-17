<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
// use Illuminate\Support\Facades\URL;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::id() !== '') {

            //berarti sudah login,maka buat session untuk menunya 
            $id_user = Auth::id();
            //cek apakah sudah ada group aktif ?
            if (Session::has('current_roles')) {
                //jika ada, maka gunakan group yg aktif
                $group = Session::get('current_roles');
            } else {
                //maka gunakan group yg ada dari database aktif
                //ambil groupnya dulu
                $group = DB::table('alus_ug')->where('user_id', $id_user)->join('alus_g', 'alus_ug.group_id', '=', 'alus_g.id')->limit(1)->get();
                //save session jenis roles, agar nanti bisa ganti
                $request->session()->put('current_roles', $group);
            }

            $listpermission = $this->canview($id_user, $group);
            if (!$listpermission['can_view']) {
                abort(403, 'Anda tidak memiliki permission untuk akses menu');
            }
            //dd($listpermission);
            //cek , jika dia request(show,store,destroy,update,edit,permission), maka skip cek session menu, cek
            if (request()->segment(3) == 'show' || request()->segment(3) == 'store' || request()->segment(3) == 'destroy' || request()->segment(3) == 'update' || request()->segment(3) == 'edit' || request()->segment(3) == 'permission') {
                if (request()->segment(3) == 'show') {
                    if (!$listpermission['can_view']) {
                        abort(403, 'Anda tidak memiliki permission lihat data');
                    }
                }

                if (request()->segment(3) == 'store') {
                    if (!$listpermission['can_add']) {
                        abort(403, 'Anda tidak memiliki permission tambah data');
                    }
                }

                if (request()->segment(3) == 'destroy') {
                    if (!$listpermission['can_delete']) {
                        abort(403, 'Anda tidak memiliki permission hapus data');
                    }
                }

                if (request()->segment(3) == 'update') {
                    if (!$listpermission['can_edit']) {
                        abort(403, 'Anda tidak memiliki permission update/edit data');
                    }
                }
                // return $next($request);
            }
            //mencari dari parent dulu
            $menus = DB::table('alus_mga')
                ->select('menu_id as id', 'menu_parent as parent_id', 'menu_nama as nama', 'menu_uri as uri', 'menu_target as target', 'menu_icon as icon', 'order_num as order_num', DB::raw('MAX(can_add) as can_add'), DB::raw('MAX(can_edit) as can_edit'), DB::raw('MAX(can_delete) as can_delete'))
                ->where('can_view', 1)
                ->where('menu_parent', 0);
            $menus->where(function ($menus) use ($group) {
                foreach ($group as $value) {
                    $menus->orWhere('id_group', $value->group_id);
                }
            });

            $menus->join('alus_mg', 'alus_mg.menu_id', '=', 'alus_mga.id_menu');
            $menus->groupBy('alus_mg.menu_id', 'alus_mg.menu_parent', 'alus_mg.menu_nama', 'alus_mg.menu_uri', 'alus_mg.menu_target', 'alus_mg.menu_icon', 'alus_mg.order_num');
            $hasil = $menus->get();

            $html = '';
            //keluarkan dari parent dulu
            foreach ($hasil as $key => $menu) {
                if ($request->segment(5) != '') {
                    $rec = $request->segment(2) . '/' . $request->segment(3) . '/' . $request->segment(4) . '/' . $request->segment(5);
                } else if ($request->segment(4) != '') {
                    $rec = $request->segment(2) . '/' . $request->segment(3) . '/' . $request->segment(4);
                } else if ($request->segment(3) != '') {
                    $rec = $request->segment(2) . '/' . $request->segment(3);
                } else {
                    $rec = $request->segment(2);
                }
                //cari apakah ada childnya
                $html .= $this->cek_anak($menu->id, $menu, $group, $rec);
            }

            $request->session()->put('menu', $html);
        } else {
            //delete session 
            $request->session()->remove('menu');
            $request->session()->remove('current_roles');
        }
        return $next($request);
    }

    public function canview($id_user = 0, $group = array())
    {
        $menu_uri = request()->segment(2);
        if ($menu_uri == null || request()->segment(2) == 'ajax' || request()->segment(2) == 'logbook') {
            if (request()->segment(1) == 'app') {
                return [
                    'can_view' => 1,
                    'can_add' => 1,
                    'can_edit' => 1,
                    'can_delete' => 1
                ];
            } else {
                return ['can_view' => 0];
            }
        }
        //cek apakah menu sudah di daftarkan
        $cek = DB::table('alus_mg')->where('menu_uri', $menu_uri)->first();
        if (!$cek) {
            abort(403, 'Menu belum didaftarkan ! Hubungi Administrator');
        }

        $menus = DB::table('alus_mga')
            ->select(DB::raw('MAX(can_add) as can_add'), DB::raw('MAX(can_edit) as can_edit'), DB::raw('MAX(can_delete) as can_delete'), DB::raw('MAX(can_view) as can_view'))
            ->where('can_view', 1)
            ->where('menu_uri', $menu_uri);
        $menus->where(function ($menus) use ($group) {
            foreach ($group as $value) {
                $menus->orWhere('id_group', $value->group_id);
            }
        });
        $menus->leftJoin('alus_mg', 'alus_mg.menu_id', '=', 'alus_mga.id_menu');
        $hasil = $menus->first();
        if ($hasil) {
            return ['can_view' => $hasil->can_view, 'can_add' => $hasil->can_add, 'can_edit' => $hasil->can_edit, 'can_delete' => $hasil->can_delete];
        } else {
            abort(403, 'Tidak memiliki permission untuk akses menu.');
        }
    }

    protected function cek_anak($idmenu = null, $datamenu = array(), $group = array(), $request = null)
    {
        $build = '';
        $menus = DB::table('alus_mga')
            ->select('menu_id as id', 'menu_parent as parent_id', 'menu_nama as nama', 'menu_uri as uri', 'menu_target as target', 'menu_icon as icon', 'order_num as order_num', DB::raw('MAX(can_add) as can_add'), DB::raw('MAX(can_edit) as can_edit'), DB::raw('MAX(can_delete) as can_delete'))
            ->where('can_view', 1)
            ->where('menu_parent', $idmenu);
        $menus->where(function ($menus) use ($group) {
            foreach ($group as $value) {
                $menus->orWhere('id_group', $value->group_id);
            }
        });
        $menus->join('alus_mg', 'alus_mga.id_menu', '=', 'alus_mg.menu_id');
        $menus->groupBy('alus_mg.menu_id', 'alus_mg.menu_parent', 'alus_mg.menu_nama', 'alus_mg.menu_uri', 'alus_mg.menu_target', 'alus_mg.menu_icon', 'alus_mg.order_num');
        $menus->orderBy('order_num', 'ASC');
        $hasil = $menus->get();
        if ($hasil->count() > 0) {
            //ada child
            //foreach untuk buka induk
            $ur = $hasil->pluck('uri')->toArray();
            if (in_array($request, $ur)) {
                $build .= '<li class="nav-item has-treeview menu-open" id="prnih">';
            } else {
                $build .= '<li class="nav-item has-treeview">';
            }
            $build .= '
            <a href="#" class="nav-link">
              <i class="nav-icon ' . $datamenu->icon . '"></i>
              <p>
                ' . $datamenu->nama . '
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview menu-open">';

            //foreach anaknya
            foreach ($hasil as $key => $menuanak) {
                $build .= $this->cek_anak($menuanak->id, $menuanak, $group, $request);
            }

            $build .= '</ul></li>';
        } else {
            //tidak ada child
            $build .= '<li class="nav-item">';
            if ($request == $datamenu->uri) {
                $build .= '<a href="' . url('app/' . $datamenu->uri) . '" class="nav-link active" target="' . $datamenu->target . '">';

                session()->put('can_add', $datamenu->can_add);
                session()->put('can_edit', $datamenu->can_edit);
                session()->put('can_delete', $datamenu->can_delete);
            } else {
                $build .= '<a href="' . url('app/' . $datamenu->uri) . '" class="nav-link" target="' . $datamenu->target . '">';
            }
            $build .= '<i class="' . $datamenu->icon . ' nav-icon"></i>
                  <p>' . $datamenu->nama . '</p>
                </a>
              </li>';
        }

        return $build;
    }
}
