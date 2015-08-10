<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersFields extends Migration {

	public function up(){
		Schema::table('users', function(Blueprint $table){
			$table->integer('participant_group_id')->nullable()->unsigned()->default(0);
			$table->tinyInteger('status')->nullable()->unsigned()->default(0);
			$table->string('yad_name', 100)->after('status')->nullable();
			$table->string('location', 100)->after('yad_name')->nullable();
			$table->string('age', 20)->after('location')->nullable();
			$table->string('phone', 20)->after('age')->nullable();
			$table->text('social')->after('phone')->nullable();
			$table->boolean('load_video')->after('social')->nullable();
			$table->string('local_video',100)->after('load_video')->nullable();
			$table->timestamp('local_video_date')->after('local_video')->nullable();
			$table->text('video')->after('local_video_date')->nullable();

			$table->boolean('in_main_page')->after('video')->nullable()->unsigned()->default(0);
			$table->boolean('winner')->after('in_main_page')->nullable()->unsigned()->default(0);
			$table->boolean('top_week_video')->after('winner')->nullable()->unsigned()->default(0);
			$table->boolean('top_video')->after('top_week_video')->nullable()->unsigned()->default(0);
			$table->boolean('top_video')->after('video_thumb')->nullable()->unsigned()->default(0);
			$table->text('way')->nullable();
			$table->integer('guest_likes')->default(0)->unsigned()->nullable();
		});
	}

	public function down(){
		Schema::table('users', function(Blueprint $table){
			$table->dropColumn('location');
			$table->dropColumn('age');
			$table->dropColumn('phone');
			$table->dropColumn('social');
			$table->dropColumn('load_video');
			$table->dropColumn('local_video');
			$table->dropColumn('local_video_date');
			$table->dropColumn('video');
			$table->dropColumn('in_main_page');
			$table->dropColumn('winner');
			$table->dropColumn('top_week_video');
			$table->dropColumn('top_video');
			$table->dropColumn('video_thumb');
			$table->dropColumn('way');
		});
	}
}
