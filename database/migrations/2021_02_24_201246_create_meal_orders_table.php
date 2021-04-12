<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_order', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger("meal_id");
            $table->unsignedBigInteger("order_id");
            $table->integer("quantity");
            $table->timestamps();

            $table->foreign('meal_id')
                ->references('id')
                ->on('meals')
                ->onDelete("cascade");
            $table->foreign('order_id')
                ->references('id')
                ->on('orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_orders');
    }
}
