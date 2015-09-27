<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeaconMajorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beacon_majors', function(Blueprint $table)
		{
			$table->smallInteger('major',true,true);
			$table->string('store_id')->unique()->nullable()->index();
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
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
		Schema::drop('beacon_majors');
	}

}
