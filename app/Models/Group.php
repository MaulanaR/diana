<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    use HasFactory;
    protected $table = 'alus_g';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'id',
        'description',
    ];
    public $timestamps = false;
    public $incrementing = false;


    public static function insert($data = array())
    {
        DB::table('alus_g')->insert($data);
        return true;
    }

    public function update($id = null, $data = array())
    {
        if ($id) {

            return DB::table('alus_g')
                ->where('id', $id)
                ->update($data);
            
        } else {
            return false;
        }
    }

    public function destroyx($id=null)
    {
        if($id)
        {
            $data = DB::table('alus_g')->where('id',$id)->first();
            if($data)
            {
                return DB::table('alus_g')
                    ->where('id', $id)
                    ->delete();
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
