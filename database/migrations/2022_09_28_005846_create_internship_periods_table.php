<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternshipPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internship_periods', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('internship_periods');
    }
}
