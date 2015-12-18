<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlainesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes', function(Blueprint $t) {
            $t->increments('id');
            $t->integer('price');
            $t->integer('distance');
            $t->string('time_estimation')->nullable();
            $t->enum('type', ['systemic', 'operator']);
            $t->text('link')->nullable();
            $t->string('title');
            $t->string('provider')->nullable();
            $t->string('start_city');
            $t->string('destination_city');
            $t->integer('start_province_id')->unsigned();
            $t->integer('destination_province_id')->unsigned();
            $t->timestamp('starts_at');
            $t->timestamp('ends_at');
            $t->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('planes');
    }
}
