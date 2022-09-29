<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseBobotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_bobot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->integer('a_min')->nullable();
            $table->integer('a_max')->nullable();
            $table->integer('b_min')->nullable();
            $table->integer('b_max')->nullable();
            $table->integer('bplus_min')->nullable();
            $table->integer('bplus_max')->nullable();
            $table->integer('c_min')->nullable();
            $table->integer('c_max')->nullable();
            $table->integer('cplus_min')->nullable();
            $table->integer('cplus_max')->nullable();
            $table->integer('d_min')->nullable();
            $table->integer('d_max')->nullable();
            $table->integer('e_min')->nullable();
            $table->integer('e_max')->nullable();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('modified_date')->useCurrentOnUpdate()->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamp('delete')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_bobot');
    }
}
