<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMajorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->nullable();
            $table->string('name', 200)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('modified_date')->useCurrentOnUpdate()->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamp('delete')->nullable();
            $table->bigInteger('academic_period_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('majors');
    }
}
