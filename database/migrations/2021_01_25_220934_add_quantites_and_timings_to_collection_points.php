<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantitesAndTimingsToCollectionPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_points', function (Blueprint $table) {
            $table->time("cut_off_point");
            $table->integer("set_quantity_per_person")->default(1);
            $table->string("logo")->nullable();
            $table->string("slug")->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_points', function (Blueprint $table) {
            $table->dropColumn([ "cut_off_point", "set_quantity_per_person", "logo", "slug"]);
        });
    }
}
