<?php

namespace App\Exports;

// use App\Models\Berita;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use app\Http\Controllers\BeritaController;

class BeritaExportAll implements FromView
{
    protected $req;

    function __construct($req) {
            $this->request = json_decode($req);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $hasil = BeritaController::show_exp($this->request);        
        return view('berita.export', [
            'berita' => $hasil
        ]);
    }
}
