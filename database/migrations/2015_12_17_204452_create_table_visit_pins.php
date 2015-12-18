<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVisitPins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_pin', function(Blueprint $t) {
            $t->bigIncrements('id');
            $t->integer('visit_id')->unsigned();
            $t->integer('pin_id')->unsigned();
            $t->text('comment')->nullable();
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
        Schema::drop('visit_pin');
    }
}
