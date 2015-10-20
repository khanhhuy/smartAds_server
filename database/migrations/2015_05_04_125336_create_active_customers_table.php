<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_customers', function(Blueprint $table)
		{
			$table->string('id');
			$table->timestamp('last_mining');
			$table->decimal('min_entrance_value',20,2)->nullable();
            $table->decimal('min_entrance_rate',5,2)->nullable();
			$table->decimal('min_aisle_value',20,2)->nullable();
            $table->decimal('min_aisle_rate',5,2)->nullable();
			$table->rememberToken();

			$table->timestamps();
            $table->primary('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('active_customers');
	}

}
