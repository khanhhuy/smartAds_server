<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->string('id');
            $table->primary('id');
            $table->string('name');
            $table->string('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
            $table->boolean('is_leaf')->default(true);
			$table->boolean('is_suitable')->default(false);
            $table->timestamps();
		});

        Schema::create('category_minor', function(Blueprint $table)
        {
            $table->string('category_id')->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');

            $table->smallInteger('beacon_minor')->unsigned()->index();
            $table->foreign('beacon_minor')->references('minor')->on('beacon_minors')->onDelete('cascade');

            $table->primary(['beacon_minor','category_id']);

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('category_minor');
        Schema::drop('categories');
	}

}
