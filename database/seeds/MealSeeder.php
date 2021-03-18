<?php

use App\Models\CollectionPoint;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Meal::class, 40)->create();
        $collection_point = CollectionPoint::all();

        $meals = Meal::all()
            ->each(function ($meal) use ($collection_point) {
                $collection_point_ids = $collection_point->random(3)->pluck('id')->toArray();
                foreach($collection_point_ids as $collection_point_id) {
                    $meal->collection_point_id = $collection_point_id;
                    $meal->save();
                }
            });

    }
}
