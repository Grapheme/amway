<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipantLikesTable extends Migration {

	public function up(){
		Schema::create('participant_likes', function(Blueprint $table){
			$table->increments('id');
			$table->integer('participant_id')->unsigned()->nullable()->default(0);
			$table->integer('user_id')->unsigned()->nullable()->default(0);
			$table->timestamps();
		});
	}

	public function down(){
		Schema::drop('participant_likes');
	}

}
