<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTargetedRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('targeted_rules', function(Blueprint $table)
		{	
			$table->increments('id');
			$table->unsignedInteger('ads_id')->index();
			$table->unsignedInteger('from_age')->nullable();
			$table->unsignedInteger('to_age')->nullable();
			$table->boolean('gender')->nullable();
			$table->unsignedInteger('from_family_members')->nullable();
			$table->unsignedInteger('to_family_members')->nullable();
			$table->string('jobs_desc')->nullable();

			$table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');

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
		Schema::drop('targeted_rules');
	}

}
