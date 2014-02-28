<?php

use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function($table)
                {
			$table->engine = 'InnoDB';

                        $table->increments('id');
                        $table->string('title');
                        $table->text('corps');
			$table->integer('author')->unsigned();
			$table->boolean('published');
			$table->timestamp('posted_at');
                        $table->timestamps();

			$table->foreign('author')->references('id')->on('users');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
