<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('categories')->nullable();
            $table->string('semester')->nullable();
            $table->bigInteger('academic_period_id')->nullable();
            $table->bigInteger('major_id')->nullable();
            $table->bigInteger('class_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('sks')->nullable();
            $table->integer('total_unit')->nullable();
            $table->text('description_unit')->nullable();
            $table->bigInteger('instructor_id')->nullable();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('modified_date')->useCurrentOnUpdate()->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamp('delete')->nullable();
            $table->bigInteger('head_instructor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
