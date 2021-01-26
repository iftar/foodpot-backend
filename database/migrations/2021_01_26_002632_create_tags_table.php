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
            $table->timestamps();
        });

        Schema::create('collection_point_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("collection_point_id");
            $table->unsignedBigInteger("tags_id");
            $table->timestamps();

            $table->unique([ "collection_point_id", "tags_id"]);
            $table->foreign("collection_point_id")
                ->references("id")
                ->on("collection_points")
                ->onDelete("cascade");
            $table->foreign("tags_id")
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
    }
}
