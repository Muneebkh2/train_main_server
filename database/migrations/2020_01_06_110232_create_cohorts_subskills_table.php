<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortsSubskillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohorts_subskills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cohorts_id');
            $table->unsignedBigInteger('subskills_id');
            $table->foreign('cohorts_id')->references('id')->on('cohorts');
            $table->foreign('subskills_id')->references('id')->on('subskills');
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
        Schema::dropIfExists('cohorts_subskills');
    }
}
