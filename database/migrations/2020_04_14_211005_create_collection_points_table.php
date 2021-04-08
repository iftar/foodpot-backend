<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_points', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('post_code');
            $table->integer('max_daily_capacity');
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
        Schema::dropIfExists('collection_points');
    }
}
