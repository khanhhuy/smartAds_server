<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stores', function(Blueprint $table)
		{
			$table->string('id');
			$table->primary('id');
			$table->string('name');
			$table->string('address');
            $table->string('display_area')->nullable();
			$table->timestamps();
		});
		Schema::create('ads_store', function(Blueprint $table)
		{
			$table->unsignedInteger('ads_id')->index();
			$table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
			$table->string('store_id')->index();
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ads_store');
		Schema::drop('stores');
	}

}
