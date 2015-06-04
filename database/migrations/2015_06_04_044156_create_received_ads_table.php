<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceivedAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('received_ads', function(Blueprint $table)
		{
            $table->string('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('active_customers')->onDelete('cascade');
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->primary(['customer_id','ads_id']);
            $table->timestamp('last_received')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('received_ads');
	}

}
