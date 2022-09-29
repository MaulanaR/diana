<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlusMgaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alus_mga', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedMediumInteger('id_group')->index('fk_groups_deleted');
            $table->integer('id_menu')->index('fk_menu_deleted');
            $table->integer('can_view')->nullable();
            $table->integer('can_edit')->default(0);
            $table->integer('can_add')->default(0);
            $table->integer('can_delete')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alus_mga');
    }
}
