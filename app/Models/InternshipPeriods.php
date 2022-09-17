<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class InternshipPeriods extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'internship_periods';
    protected $primaryKey = 'id';
    public $incrementing = true;
	protected $fillable = [
		'id',
        'name',
        'start_date',
        'end_date',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
	];
	protected $guarded = [];
}
