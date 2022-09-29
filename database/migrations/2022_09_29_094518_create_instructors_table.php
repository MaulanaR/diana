<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('name')->nullable();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('modified_date')->useCurrentOnUpdate()->nullable();
            $table->bigInteger('modified_by')->nullable();
            $table->timestamp('delete')->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('ttl')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('address')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('telp_rumah')->nullable();
            $table->string('hp')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('nip')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('tmt_pns')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('nip_pasangan')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructors');
    }
}
