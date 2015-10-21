<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferenceRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('preference_rules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('from_age')->nullable();
			$table->unsignedInteger('to_age')->nullable();
			$table->boolean('gender')->nullable();
			$table->unsignedInteger('from_family_members')->nullable();
			$table->unsignedInteger('to_family_members')->nullable();
			$table->string('jobs_desc')->nullable();
			$table->timestamps();
		});

		Schema::create('ads_rules', function(Blueprint $table) {
			$table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->unsignedInteger('rule_id')->index();
            $table->foreign('rule_id')->references('id')->on('preference_rules')->onDelete('cascade');

            $table->primary(['ads_id','rule_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ads_rules');
		Schema::drop('preference_rules');
	}

}
