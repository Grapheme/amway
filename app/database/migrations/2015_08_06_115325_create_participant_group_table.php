<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipantGroupTable extends Migration {

	public function up(){
		Schema::create('participant_group', function(Blueprint $table){
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('description')->nullable();
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('participant_group');
	}

}
