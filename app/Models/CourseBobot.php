<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseBobot extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'course_bobot';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'course_id',
        'a_min',
        'a_max',
        'b_min',
        'b_max',
        'bplus_min',
        'bplus_max',
        'c_min',
        'c_max',
        'cplus_min',
        'cplus_max',
        'd_min',
        'd_max',
        'e_min',
        'e_max',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
    ];
    protected $guarded = [];
}
