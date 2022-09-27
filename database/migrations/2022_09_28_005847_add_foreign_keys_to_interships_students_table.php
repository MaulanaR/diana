<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToIntershipsStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('interships_students', function (Blueprint $table) {
            $table->foreign(['mentor_instructor_id'], 'in_mentor_instructor_id')->references(['id'])->on('instructors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['examiner_instructor_id'], 'in_examiner_instructor_id')->references(['id'])->on('instructors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['internship_period_id'], 'in_internship_period_id')->references(['id'])->on('internship_periods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['student_id'], 'in_student_id')->references(['id'])->on('student_details')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['internship_location_id'], 'in_internship_location_id')->references(['id'])->on('internship_locations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('interships_students', function (Blueprint $table) {
            $table->dropForeign('in_mentor_instructor_id');
            $table->dropForeign('in_examiner_instructor_id');
            $table->dropForeign('in_internship_period_id');
            $table->dropForeign('in_student_id');
            $table->dropForeign('in_internship_location_id');
        });
    }
}
