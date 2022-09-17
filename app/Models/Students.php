<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'student_details';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'full_name',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'nim',
        'nik',
        'home_address',
        'phone',
        'socmed_instagram',
        'socmed_twitter',
        'socmed_other',
        'socmed_other_name',
        'biological_father_name',
        'biological_mother_name',
        'biological_father_phone',
        'biological_mother_phone',
        'origin_school',
        'major_origin_school',
        'district_origin_school',
        'province_origin_school',
        'avatar',
        'family_card_file',
        'id_card_file',
        'statement_letter_file',
        'certificate_last_education_file',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
    ];
    protected $guarded = [];
}
