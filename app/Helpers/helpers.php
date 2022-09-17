<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Narasi;
use Illuminate\Support\Facades\Session;

function groups($group_id = null)
{
    if($group_id)
    {
        //maka cari berdasarkan group idnya
        $group = DB::table('alus_g')->select('id as group_id','name as group_name', 'description as group_description')->where('id', $group_id)->get();
        return $group;
    }else{
        //maka cari berdasarkan yg sedang login
        if (Auth::id() !== '') {
            //berarti sudah login,maka buat session untuk menunya 
            $id_user = Auth::id();
            //ambil groupnya dulu
            $group = DB::table('alus_ug')->select('user_id','group_id','name as group_name', 'description as group_description')->leftJoin('alus_g','alus_g.id','=','alus_ug.group_id')->where('user_id', $id_user)->get();
            return $group;
        }else{
            return abort(401);
        }
    }
}

function get_permission($menu = null, $user_id = null)
{
    if($user_id)
    {
        $id_user = $user_id;
    }else{
        $id_user = Auth::id();
    }
        $group = DB::table('alus_ug')->where('user_id', $id_user)->get();
        if(!$menu)
        {
            $menu = request()->segment(2);
        }

        $menus = DB::table('alus_mga')
            ->select(DB::raw('MAX(can_add) as can_add') , DB::raw('MAX(can_edit) as can_edit'), DB::raw('MAX(can_delete) as can_delete') ,DB::raw('MAX(can_view) as can_view'))
            ->where('can_view', 1)
            ->where('menu_uri', $menu);
        $menus->where(function ($menus) use ($group) {
            foreach ($group as $value) {
                $menus->orWhere('id_group', $value->group_id);
            }
        });
        $menus->leftJoin('alus_mg', 'alus_mg.menu_id', '=', 'alus_mga.id_menu');
        $hasil = $menus->first();
        
        if($hasil)
        {
            return ['can_view' => $hasil->can_view, 'can_add' => $hasil->can_add, 'can_edit' => $hasil->can_edit, 'can_delete' => $hasil->can_delete];
        }else{
            abort(401);
        }

}

function narasi($key=null,$lang=null) {
    $data = Narasi::where('tn_key',$key)->first();
    if ($data) {
        //ada datanya
        //jika lang diisi maka ambil pilihannya, jika tidak keluarkan sesuai session, jika session tidak ada, maka keluarkan ID
        if ($lang) {
            $keyLang = 'tn_value_'.$lang;
        } else if ( Session::get('bahasa') !== null ) {
            $keyLang = 'tn_value_'.Session::get('bahasa');
        } else {
            $keyLang = 'tn_value_id';
        }
        return $data[$keyLang];
    }else {
        return '';
    }
}

