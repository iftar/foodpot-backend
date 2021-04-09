<?php

namespace Dashboard\Orders;

use App\Models\CollectionPoint;
use Carbon\Carbon;
use Laravel\Nova\Card;

class Orders extends Card
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
        return 'orders';
    }

    /* Indicates that the analytics should show current visitors.
    *
    * @return $this
    */
    public function currentVisitors()
    {
        return $this->withMeta(['currentVisitors' => true]);
    }


    /* Indicates that the analytics should show current visitors.
    *
    * @return $this
    */
    public function withOrders()
    {
        $user = auth()->user();
        $orders = $user->charities[0]->collectionPoints[0]->orders;
        $filtered_orders =  $orders->filter(function($order) {
            $created_at = Carbon::parse($order->created_at);
           return $created_at->isToday();
        });

        return $this->withMeta(['orders' => $filtered_orders ]);
    }
}
