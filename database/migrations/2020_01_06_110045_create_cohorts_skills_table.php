<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortsSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohorts_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cohorts_id');
            $table->unsignedBigInteger('skills_id');
            $table->foreign('cohorts_id')->references('id')->on('cohorts');
            $table->foreign('skills_id')->references('id')->on('skills');
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
        Schema::dropIfExists('cohorts_skills');
    }
}
