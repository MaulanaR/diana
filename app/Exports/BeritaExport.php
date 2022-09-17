<?php

namespace App\Exports;

// use App\Models\Berita;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class BeritaExport implements FromView
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        $sql = "SELECT
        t_berita.tb_id,
        t_berita.tb_slug,
        t_berita.tb_judul_id,
        t_berita.tb_judul_en,
        t_berita.tb_teaser_id,
        t_berita.tb_teaser_en,
        t_berita.tb_content_id,
        t_berita.tb_content_en,
        t_berita.tb_thumbnail,
        t_berita.tb_image,
        t_berita.tb_comment,
        t_berita.tb_tanggal_tayang,
        t_berita.tb_banner,
        users.name,
        t_berita.tb_create_date,
        t_berita.tb_modified_date,
        t_berita.tb_delete,
        t_berita.tb_view_increased_at,
        (
            SELECT
                GROUP_CONCAT(t_relasi_berita.trb_value)
            FROM
                t_relasi_berita
            WHERE
                trb_kategori = 'gallery_berita'
            AND trb_id_berita = t_berita.tb_id
        ) AS gallery_berita,
        (
            SELECT
                GROUP_CONCAT(t_file_berita.tfb_tf_link)
            FROM
                t_file_berita
            WHERE
                tfb_id_berita = t_berita.tb_id
        ) AS file_berita,
        (
            SELECT
                GROUP_CONCAT(mkb_nama)
            FROM
                t_relasi_berita
            LEFT JOIN m_kategori_berita ON m_kategori_berita.mkb_id = t_relasi_berita.trb_value
            WHERE
                (trb_kategori = 'kategori_berita' AND trb_id_berita = t_berita.tb_id)
        ) AS kategori_berita,
        (
            SELECT
                GROUP_CONCAT(mt_nama)
            FROM
                t_relasi_berita
            LEFT JOIN m_tag ON m_tag.mt_id = t_relasi_berita.trb_value
            WHERE
                (trb_kategori = 'tags' AND trb_id_berita = t_berita.tb_id)
        ) AS tag_berita
    FROM
        t_berita
    LEFT JOIN users ON users.id = t_berita.tb_create_by ";
    if($this->id)
    {
        // ada
        $SpecifiedId = explode(",",$this->id);
        $sql .= "WHERE t_berita.tb_id IN";
        $sql .= "(";
        foreach($SpecifiedId as $IdSpec)
        {
            if(end($SpecifiedId) == $IdSpec)
            {
                $sql .= "?)";
            }else{
                $sql .= "?,";
            }
        }
        // dd($sql);
        $hasil = DB::select($sql, $SpecifiedId);
    }else{
        $hasil = DB::select($sql);
    }
        return view('berita.export', [
            'berita' => $hasil
        ]);
    }
}
