<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			$table->string('id');
            $table->primary('id');
			$table->timestamps();
		});

        Schema::create('watching_lists', function(Blueprint $table)
        {
            $table->string('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('active_customers')->onDelete('cascade');
            $table->string('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->primary(['customer_id','item_id']);
        });

        Schema::create('black_lists', function (Blueprint $table) {
            $table->string('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('active_customers')->onDelete('cascade');
            $table->string('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->primary(['customer_id','item_id']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('black_lists');
        Schema::drop('watching_lists');
        Schema::drop('items');
	}

}
