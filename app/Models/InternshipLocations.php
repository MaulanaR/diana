<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class InternshipLocations extends Model
{
    use HasFactory;
    use SoftDeletes;
    const DELETED_AT = 'delete';
    public $timestamps = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'internship_locations';
    protected $primaryKey = 'id';
    public $incrementing = true;
	protected $fillable = [
		'id',
        'name',
        'address',
        'pic_contact',
        'pic_position',
        'phone',
        'legal_file',
        'created_date',
        'created_by',
        'modified_date',
        'modified_by',
        'delete',
	];
	protected $guarded = [];
}
