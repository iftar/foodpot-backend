<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactInfoToCharities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charities', function (Blueprint $table) {
            $table->string("company_website")->nullable();
            $table->string("contact_telephone")->nullable();
            $table->string("logo")->nullable();
            $table->string("personal_email")->nullable();
            $table->string("personal_number")->nullable();
            $table->boolean("has_food_hygiene_cert")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charities', function (Blueprint $table) {
            $table->dropColumns(["company_website", "contact_telephone", "logo" ,"personal_email", "personal_number", "has_food_hygiene_cert"]);
        });
    }
}
