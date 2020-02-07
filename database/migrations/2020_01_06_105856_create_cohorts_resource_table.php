<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortsResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohorts_resource', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cohorts_id');
            $table->unsignedBigInteger('resource_id');
            $table->foreign('cohorts_id')->references('id')->on('cohorts');
            $table->foreign('resource_id')->references('id')->on('resource');
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
        Schema::dropIfExists('cohorts_resource');
    }
}
