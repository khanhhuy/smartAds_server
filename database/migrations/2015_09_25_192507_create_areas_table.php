<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->string('name');
            $table->string('parent_id')->index()->nullable();
            $table->foreign('parent_id')->references('id')->on('areas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->string('area_id');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });

        Schema::create('ads_area', function (Blueprint $table) {
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('area_id')->index();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('ads_area');
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign('stores_area_id_foreign');
        });
        Schema::drop('areas');
    }
}
