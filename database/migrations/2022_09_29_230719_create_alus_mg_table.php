<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlusMgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alus_mg', function (Blueprint $table) {
            $table->integer('menu_id', true);
            $table->integer('menu_parent');
            $table->string('menu_nama');
            $table->string('menu_uri');
            $table->string('menu_target')->nullable();
            $table->string('menu_icon', 25)->nullable();
            $table->integer('order_num')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alus_mg');
    }
}
