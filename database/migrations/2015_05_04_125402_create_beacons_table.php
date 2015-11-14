<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeaconsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('beacon_minors', function(Blueprint $table)
        {
            $table->smallInteger('minor', true, true);
            $table->timestamps();
        });

        /*Schema::create('beacons', function(Blueprint $table)
		{
            $table->smallInteger('minor')->unsigned();
            $table->foreign('minor')->references('minor')->on('beacon_minors')->onDelete('cascade');
            $table->smallInteger('major',true,true);
            $table->string('color')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE  `beacons` DROP PRIMARY KEY , ADD PRIMARY KEY (  `major` ,  `minor` ) ;');*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('beacon_minors');
	}

}
