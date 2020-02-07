<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('surname');
            $table->string('email');
            $table->string('phone');
            $table->string('street');
            $table->string('town');
            $table->string('city');
            $table->string('postcode');
            $table->float('rating', 2, 1);
            $table->integer('rates_per_hour');
            $table->longText('notes')->nullable();
            $table->string('image')->default('{"file":{},"path":"default.jpg"}');
            $table->rememberToken('token_key')->default(null);
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_invited')->default(1);
            $table->timestamp('last_login');
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
        Schema::dropIfExists('resource');
    }
    
}
