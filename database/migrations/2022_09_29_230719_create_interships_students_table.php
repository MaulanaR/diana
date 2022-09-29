<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntershipsStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interships_students', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('student_id')->nullable()->index('in_student_id');
            $table->bigInteger('internship_location_id')->nullable()->index('in_internship_location_id');
            $table->bigInteger('internship_period_id')->nullable()->index('in_internship_period_id');
            $table->boolean('personal_choice')->nullable()->default(false);
            $table->string('approval_status', 20)->nullable()->default('')->comment('1.Menunggu Persetujuan 
2.Disetujui
3.Ditolak');
            $table->string('status', 20)->nullable()->default('')->comment('Aktif
Selesai');
            $table->unsignedBigInteger('mentor_instructor_id')->nullable()->index('in_mentor_instructor_id');
            $table->unsignedBigInteger('examiner_instructor_id')->nullable()->index('in_examiner_instructor_id');
            $table->text('intership_file')->nullable();
            $table->text('final_report_file')->nullable();
            $table->text('internship_certification_file')->nullable();
            $table->text('final_project_file')->nullable();
            $table->double('evaluation')->nullable();
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
        Schema::dropIfExists('interships_students');
    }
}
