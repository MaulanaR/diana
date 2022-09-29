<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlusUgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alus_ug', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index('fk_users_groups_users1_idx');
            $table->unsignedMediumInteger('group_id')->index('fk_users_groups_groups1_idx');

            $table->unique(['user_id', 'group_id'], 'uc_users_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alus_ug');
    }
}
