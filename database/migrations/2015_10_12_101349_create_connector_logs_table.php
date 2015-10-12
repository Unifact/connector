<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnectorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connector_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message', 128);
            $table->smallInteger('level');
            $table->text('data');
            $table->integer('job_id');
            $table->integer('stage_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('connector_logs');
    }
}
