<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourseGrade extends Model
{
    use HasFactory;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'student_course_grade';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'course_id',
        'student_id',
        'uts',
        'uas',
        'final',
        'grade',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
    ];
    protected $guarded = [];
}
