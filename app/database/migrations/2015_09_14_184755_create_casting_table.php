<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCastingTable extends Migration {

    public function up() {
        Schema::create('casting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('time', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('skype', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::drop('casting');
    }

}
