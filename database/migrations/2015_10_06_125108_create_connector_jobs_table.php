<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConnectorJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connector_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 64);
            $table->smallInteger('priority')->unsigned();
            $table->text('data');
            $table->string('status', 32);
            $table->string('reference', 64)->nullable();
            $table->timestamps();

            $table->index(['type', 'status'], 'idx_type_status');
            $table->index(['status', 'priority'], 'idx_status_priority');
            $table->index('reference', 'idx_reference');
        });

        DB::statement('ALTER TABLE `connector_jobs` ADD FULLTEXT idx_job_data(data)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('connector_jobs');
    }
}
