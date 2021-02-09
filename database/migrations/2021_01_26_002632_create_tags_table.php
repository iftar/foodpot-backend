<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("type");
            $table->string("image_url")->nullable();
            $table->timestamps();
        });

        Schema::create('collection_point_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("collection_point_id");
            $table->unsignedBigInteger("tag_id");
            $table->timestamps();

            $table->unique([ "collection_point_id", "tag_id"]);
            $table->foreign("collection_point_id")
                ->references("id")
                ->on("collection_points")
                ->onDelete("cascade");
            $table->foreign("tag_id")
                ->references("id")
                ->on("tags")
                ->onDelete("cascade");
        });
        Schema::create('meal_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("meal_id");
            $table->unsignedBigInteger("tag_id");
            $table->timestamps();

            $table->unique([ "meal_id", "tag_id"]);
            $table->foreign("meal_id")
                ->references("id")
                ->on("meals")
                ->onDelete("cascade");
            $table->foreign("tag_id")
                ->references("id")
                ->on("tags")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists("collection_point_tags");
        Schema::dropIfExists("meal_tags");
    }
}
