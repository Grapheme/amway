<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersFields extends Migration {

	public function up(){
		Schema::table('users', function(Blueprint $table){
			$table->string('location', 100)->after('active')->nullable();
			$table->string('age', 20)->after('location')->nullable();
			$table->string('phone', 20)->after('age')->nullable();
			$table->text('social')->after('phone')->nullable();
			$table->boolean('load_video')->after('social')->nullable();
			$table->text('video')->after('load_video')->nullable();
		});
	}

	public function down(){
		Schema::table('users', function(Blueprint $table){
			$table->dropColumn('location');
			$table->dropColumn('age');
			$table->dropColumn('phone');
			$table->dropColumn('social');
			$table->dropColumn('load_video');
			$table->dropColumn('video');
		});
	}
}
