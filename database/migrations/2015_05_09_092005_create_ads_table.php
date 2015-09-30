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
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date');
            $table->string('title');
            $table->boolean('is_promotion')->default(false);
            $table->boolean('is_whole_system')->default(true);
            $table->decimal('discount_value',20,5)->nullable();
            $table->decimal('discount_rate',5,4)->nullable();
			$table->timestamps();
		});
        Schema::create('ads_item', function(Blueprint $table)
        {
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
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
        Schema::drop('ads');
	}
}
