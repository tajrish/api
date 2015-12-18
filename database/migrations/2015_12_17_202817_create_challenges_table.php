<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function(Blueprint $t) {
            $t->increments('id');
            $t->string('title');
            $t->string('description')->nullable();
            $t->integer('pin_id')->unsigned();
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
        Schema::drop('challenges');
    }
}
