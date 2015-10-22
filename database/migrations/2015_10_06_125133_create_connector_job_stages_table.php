<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnectorJobStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connector_job_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id');
            $table->string('stage',64);
            $table->text('data');
            $table->string('status', 32);
            $table->timestamps();

            $table->foreign('job_id', 'fk_stage_jobId')
                ->references('id')->on('connector_jobs');

            $table->index(['job_id', 'stage'], 'idx_jobId_stage');
            $table->index(['job_id', 'status'], 'idx_jobId_status');
        });

        DB::statement('ALTER TABLE `connector_job_stages` ADD FULLTEXT idx_stage_data(data)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connector_job_stages', function(Blueprint $table){
            $table->dropForeign('fk_stage_jobId');
        });

        Schema::drop('connector_job_stages');
    }
}
