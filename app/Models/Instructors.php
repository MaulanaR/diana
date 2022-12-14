<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructors extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'instructors';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
        'name',
        'gender',
        'ttl',
        'nuptk',
        'status_perkawinan',
        'provinsi',
        'kecamatan',
        'kota',
        'kelurahan',
        'address',
        'rt',
        'rw',
        'kode_pos',
        'telp_rumah',
        'hp',
        'email',
        'npwp',
        'status_kepegawaian',
        'nip',
        'pangkat',
        'tmt_pns',
        'nama_pasangan',
        'pekerjaan',
        'nip_pasangan',
        'avatar',
    ];
    protected $guarded = [];
}
