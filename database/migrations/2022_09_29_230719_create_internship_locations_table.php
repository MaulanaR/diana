<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternshipLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internship_locations', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name')->nullable()->default('');
            $table->longText('address')->nullable();
            $table->string('pic_contact', 15)->nullable()->default('');
            $table->string('pic_position', 100)->nullable()->default('');
            $table->string('phone', 15)->nullable()->default('');
            $table->text('legal_file')->nullable();
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
        Schema::dropIfExists('internship_locations');
    }
}
