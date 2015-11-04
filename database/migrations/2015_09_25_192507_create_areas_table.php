<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->foreign('parent_id')->references('id')->on('areas')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->string('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
        });

        Schema::create('ads_area', function (Blueprint $table) {
            $table->unsignedInteger('ads_id')->index();
            $table->foreign('ads_id')->references('id')->on('ads')->onDelete('cascade');
            $table->string('area_id')->index();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('restrict');
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
