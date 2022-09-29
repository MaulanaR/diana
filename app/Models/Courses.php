<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courses extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'courses';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'categories',
        'semester',
        'academic_period_id',
        'major_id',
        'class_id',
        'name',
        'file',
        'sks',
        'total_unit',
        'description_unit',
        'instructor_id',
        'head_instructor_id',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
    ];
    protected $guarded = [];
}
