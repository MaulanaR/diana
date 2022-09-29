<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('major_id')->nullable();
            $table->string('full_name')->nullable();
            $table->string('birth_place', 200)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable()->default('male');
            $table->string('religion', 20)->nullable();
            $table->string('nim', 25)->nullable();
            $table->string('nik', 17)->nullable();
            $table->longText('home_address')->nullable();
            $table->string('phone', 15)->nullable();
            $table->boolean('socmed_instagram')->nullable();
            $table->boolean('socmed_twitter')->nullable();
            $table->tinyInteger('socmed_other')->nullable();
            $table->string('socmed_username')->nullable();
            $table->string('biological_father_name')->nullable();
            $table->string('biological_mother_name')->nullable();
            $table->string('biological_father_phone', 20)->nullable();
            $table->string('biological_mother_phone', 20)->nullable();
            $table->text('origin_school')->nullable();
            $table->text('major_origin_school')->nullable();
            $table->longText('district_origin_school')->nullable();
            $table->text('province_origin_school')->nullable();
            $table->text('avatar')->nullable();
            $table->text('family_card_file')->nullable();
            $table->text('id_card_file')->nullable();
            $table->text('statement_letter_file')->nullable();
            $table->text('certificate_last_education_file')->nullable();
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
        Schema::dropIfExists('student_details');
    }
}
