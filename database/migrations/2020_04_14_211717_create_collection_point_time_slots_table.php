<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionPointTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_point_time_slots', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedInteger('collection_point_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_capacity');
            $table->string('type')->default('user_pickup');
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
        Schema::dropIfExists('collection_point_time_slots');
    }
}
