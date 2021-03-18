<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');

            // order details
            $table->unsignedInteger('quantity')->default(0);
            $table->date('required_date');
            $table->unsignedInteger('collection_point_id');
            $table->unsignedInteger('collection_point_time_slot_id');

            // user details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');

            // only needed for delivery
            $table->string('phone')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('post_code')->nullable();

            $table->text('notes')->nullable();
            $table->string('status')->default('accepted');
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
        Schema::dropIfExists('orders');
    }
}
