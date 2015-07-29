<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhotosTable extends Migration {

    public $table = 'photos';

	public function up() {
        if (!Schema::hasTable($this->table)) {
        	Schema::create($this->table, function(Blueprint $table) {			
    			$table->increments('id');
    			$table->string('name');
    			$table->string('gallery_id')->index();
    			$table->text('title');
    			$table->integer('order')->unsigned()->default(999)->index();
    			$table->timestamps();
    		});
            echo(' + ' . $this->table . PHP_EOL);
        } else {
            echo('...' . $this->table . PHP_EOL);
        }
	}

	public function down(){
		Schema::dropIfExists($this->table);
        echo(' - ' . $this->table . PHP_EOL);
	}

}