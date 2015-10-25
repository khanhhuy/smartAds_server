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
			$table->increments('id')->index();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date');
            $table->string('title');
            $table->boolean('is_promotion')->default(true);
            $table->boolean('is_whole_system')->default(false);
            $table->decimal('discount_value',20,2)->nullable();
            $table->decimal('discount_rate',5,2)->nullable();
            $table->boolean('image_display')->default(true);
            $table->boolean('provide_image_link')->nullable();
            $table->string('image_url',2083)->nullable();
            $table->string('web_url',2083)->nullable();
            $table->boolean('auto_thumbnail')->default(false);
            $table->boolean('provide_thumbnail_link')->nullable();
            $table->string('thumbnail_url',2083)->nullable();
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
