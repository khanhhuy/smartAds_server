<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
			$table->double('latitude', 11, 7)->nullable();
			$table->double('longitude', 11, 7)->nullable();
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
