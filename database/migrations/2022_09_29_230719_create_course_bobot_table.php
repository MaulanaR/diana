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
            $table->float('a_min', 11, 0)->nullable();
            $table->float('a_max', 11, 0)->nullable();
            $table->float('b_min', 11, 0)->nullable();
            $table->float('b_max', 11, 0)->nullable();
            $table->float('bplus_min', 11, 0)->nullable();
            $table->float('bplus_max', 11, 0)->nullable();
            $table->float('c_min', 11, 0)->nullable();
            $table->float('c_max', 11, 0)->nullable();
            $table->float('cplus_min', 11, 0)->nullable();
            $table->float('cplus_max', 11, 0)->nullable();
            $table->float('d_min', 11, 0)->nullable();
            $table->float('d_max', 11, 0)->nullable();
            $table->float('e_min', 11, 0)->nullable();
            $table->float('e_max', 11, 0)->nullable();
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
