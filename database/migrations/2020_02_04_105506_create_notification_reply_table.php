<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_reply', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('notification_reply');
            
            $table->unsignedBigInteger('notification_id')->nullable();
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->foreign('notification_id')->references('id')->on('notification');
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
        Schema::dropIfExists('notification_reply');
    }
}
