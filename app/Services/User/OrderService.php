<?php

namespace App\Services\User;

use App\Models\Meal;
use App\Models\MealOrder;
use Carbon\Carbon;
use App\Models\Order;
use App\Events\Order\Created;
use App\Events\Order\Updated;
use App\Models\CollectionPointTimeSlot;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function list($filters)
    {
        return Order::with(['collectionPoint', 'collectionPointTimeSlot'])
                    ->where('user_id', auth()->user()->id)
                    ->when(array_key_exists('required_date', $filters), function ($query) use ($filters) {
                        $query->whereDate('required_date', $filters['required_date']);
                    })
                    ->get();
    }

    public function get(Order $order)
    {
        return Order::with(['collectionPoint', 'collectionPointTimeSlot'])
                    ->where('user_id', auth()->user()->id)
                    ->where('id', $order->id)
                    ->first();
    }

    public function create($data, array $meals)
    {
        $user = auth()->user();
        $meals = collect($meals);
        $data['required_date'] = now('Europe/London');

        DB::beginTransaction();

        $order = $user->orders()->create($data);

        foreach ($meals as $meal) {
            try {
                $existing_meal = Meal::findOrFail($meal["meal_id"]);
                // quantity
                if ($meal["quantity"] <= $existing_meal->quantity) {
                    $order->meals()->attach($existing_meal->id, [
                        "quantity" => $meal["quantity"]
                    ]);
                    $existing_meal->quantity = $existing_meal->quantity - $meal["quantity"];
                    $existing_meal->save();
                }

            } catch (\Exception $e) {
                DB::rollBack();
                return [
                    'status'  => 'error',
                    'message' => "The meal does not exist",
                ];
            }

        }

        DB::commit();



        event(new Created($order));

        return $order;
    }

    public function update(Order $order, $data)
    {
        $data['required_date'] = $order->required_date;

        $order->update($data);

        event(new Updated($order));

        return $order->fresh();
    }

    public function delete(Order $order)
    {
        $order->delete();
    }

    public function timeSlotBelongsToCollectionPoint($timeSlotId, $collectionPointId)
    {
        return CollectionPointTimeSlot::where('id', $timeSlotId)
                                      ->where('collection_point_id', $collectionPointId)
                                      ->first();
    }

    public function CollectionPointAcceptingOrders(CollectionPointTimeSlot $collectionPointTimeSlot)
    {
        if( $collectionPointTimeSlot->accepting_orders && 
            $collectionPointTimeSlot->collectionPoint->accepting_orders ) return true;
        else return false;
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new Order())->getFillable())
        );
    }

    public function canOrder(CollectionPointTimeSlot $collectionPointTimeSlot)
    {
        $result = [
            'user_can_order'             => false,
            'user_has_ordered_today'     => true,
            'time_passed_daily_deadline' => true,
            'messages' => [],
        ];

        if (!config('shareiftar.enabled')) {
            $result['messages'][] = "Share iftar platform is currently disabled.";
            return $result;
        }

        $user = auth()->user();

        // check if user has already ordered today
        $todaysOrderCount = $user->orders()
                                 ->whereDate('created_at', Carbon::today('Europe/London'))
                                 ->count();

        if ( $todaysOrderCount == 0 ) $result['user_has_ordered_today'] = false;
        else $result['messages'][] = "User has already ordered today.";

        // check if between 12am and 3pm
        $now   = Carbon::now('Europe/London');
        $start = Carbon::createFromTimeString('00:00', 'Europe/London');
        $end   = Carbon::createFromTimeString($collectionPointTimeSlot->collectionPoint->cut_off_point, 'Europe/London');

        if ( $now->between($start, $end) || !config('shareiftar.enable_timeout') ) $result['time_passed_daily_deadline'] = false;
        else $result['messages'][] = "Today's deadline time has passed.";

        // update can order status
        $result['user_can_order'] = ! $result['user_has_ordered_today'] && ! $result['time_passed_daily_deadline'];

        return $result;
    }
}
