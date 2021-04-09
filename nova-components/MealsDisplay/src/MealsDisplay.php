<?php

namespace Dashboard\MealsDisplay;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Card;

class MealsDisplay extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'meals-display';
    }
    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function withTodaysMeals()
    {
        $user = auth()->user();
        $orders = $user->charities->first()->collectionPoints->first()->orders;
        $filtered_orders =  $orders->filter(function($order) {
            $created_at = Carbon::parse($order->created_at);
            return $created_at->isToday();
        });
        $filtered_orders = $filtered_orders->map->meals->flatten();

        $meals = $user->charities
            ->map
            ->collectionPoints
            ->flatten()
            ->map
            ->meals
            ->flatten();

        return $this->withMeta([
            'orders' => $filtered_orders,
            'meals' => $meals->toArray()
        ]);
    }


}
