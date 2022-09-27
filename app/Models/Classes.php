<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'classes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
        'academic_period_id',
        'major_id',
    ];
    protected $guarded = [];
}
