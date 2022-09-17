<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menus extends Model
{
    use HasFactory;
    //definisikan nama tabel dan kawan2
    protected $table = 'alus_mg';
    protected $primaryKey = 'menu_id';
    public $incrementing = true;
	protected $fillable = ['menu_parent','menu_nama','menu_uri','menu_target','menu_icon','order_num'];
	protected $guarded = [];

    public function all_tree()
	{
		$nodes = DB::table('alus_mg')->get();
		return $this->getChildren($nodes, 0, 0);
	}

	public function getChildren($nodes ,$pid = 0, $depth = 0)
	{
		$tree = [];

		foreach($nodes as $node) {

			if ($node->menu_parent == $pid) {
				
				$node->menu_nama = str_repeat('---', $depth) .$node->menu_nama;
				$children   = $this->getChildren($nodes, $node->menu_id, ($depth + 1));
				$tree[]     = $node;

				if (count($children) > 0) {
					$tree   = array_merge($tree, $children);
				}
			}

		}

		return $tree;
	}
}
