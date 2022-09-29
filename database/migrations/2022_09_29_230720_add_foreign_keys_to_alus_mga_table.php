<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAlusMgaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alus_mga', function (Blueprint $table) {
            $table->foreign(['id_group'], 'alus_mga_ibfk_1')->references(['id'])->on('alus_g')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['id_menu'], 'alus_mga_ibfk_2')->references(['menu_id'])->on('alus_mg')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alus_mga', function (Blueprint $table) {
            $table->dropForeign('alus_mga_ibfk_1');
            $table->dropForeign('alus_mga_ibfk_2');
        });
    }
}
