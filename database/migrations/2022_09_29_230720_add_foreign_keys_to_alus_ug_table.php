<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAlusUgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alus_ug', function (Blueprint $table) {
            $table->foreign(['group_id'], 'alus_ug_ibfk_1')->references(['id'])->on('alus_g')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'alususer')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alus_ug', function (Blueprint $table) {
            $table->dropForeign('alus_ug_ibfk_1');
            $table->dropForeign('alususer');
        });
    }
}
