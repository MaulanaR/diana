<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_visitors', function (Blueprint $table) {
            $table->mediumInteger('id', true);
            $table->longText('url')->nullable();
            $table->longText('fullurl')->nullable();
            $table->string('ip')->nullable()->index('ip_visitor');
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->dateTime('date')->nullable()->index('date_visitor');
            $table->longText('user_agent')->nullable();
            $table->string('browser')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_visitors');
    }
}
