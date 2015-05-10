<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ads', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title');
			$table->timestamps();
		});
        Schema::create('ads_item', function(Blueprint $table)
        {
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
        Schema::create('ads_category', function(Blueprint $table)
        {
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('category_id')->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('ads_item');
        Schema::drop('ads_category');
		Schema::drop('ads');
	}

}
