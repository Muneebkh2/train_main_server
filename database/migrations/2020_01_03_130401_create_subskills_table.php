<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubskillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('subskills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subskills_title');
            $table->longText('subskills_desc');
            // $table->integer('skills_id');
            $table->unsignedBigInteger('skills_id');
            // $table->integer('skills_id')->unsigned();
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
        Schema::dropIfExists('subskills');
        // Schema::table('subskills', function (Blueprint $table) {
        //     //
        // });
    }
}
