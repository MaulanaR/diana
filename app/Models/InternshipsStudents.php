<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternshipsStudents extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'interships_students';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'student_id',
        'internship_location_id',
        'internship_period_id',
        'personal_choice',
        'approval_status',
        'status',
        'mentor_instructor_id',
        'examiner_instructor_id',
        'intership_file',
        'final_report_file',
        'internship_certification_file',
        'final_project_file',
        'evaluation',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete'
    ];
    protected $guarded = [];
}
