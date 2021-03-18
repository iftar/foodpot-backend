<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OauthClientSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(MealSeeder::class);
        factory("App\Models\User")->create([ "email" => "afmire877@gmail.com", "password" => \Illuminate\Support\Facades\Hash::make("ahmed123")]);
    }
}
