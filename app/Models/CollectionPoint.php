<?php

namespace App\Models;

use Carbon\Carbon;
use App\Services\All\SmsService;
use App\Notifications\SmsMessage;
use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    protected $table = 'collection_points';

    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'city',
        'county',
        'post_code',
        'max_daily_capacity',
        'delivery_radius',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'max_daily_capacity' => 'integer',
        'delivery_radius' => 'integer',
    ];

    protected $appends = [
        'available_capacity',
        'accepting_orders',
        'accepting_collections',
        'accepting_deliveries',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'collection_point_users');
    }

    public function collectionPointUsers()
    {
        return $this->hasMany(CollectionPointUser::class);
    }

    public function charity()
    {
        return $this->belongsToMany(Charity::class, 'charity_collection_points');
    }

    public function collectionPointTimeSlots()
    {
        return $this->hasMany(CollectionPointTimeSlot::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function getAvailableCapacityAttribute()
    {
        return $this->max_daily_capacity - $this->orders()->whereDate('created_at', today('Europe/London'))->sum('quantity');
    }

    public function getAcceptingOrdersAttribute()
    {
        return $this->available_capacity > 0;
    }

    public function getAcceptingCollectionsAttribute()
    {
        return $this->collectionPointTimeSlots->where('type', 'user_pickup')->count() > 0;
    }

    public function getAcceptingDeliveriesAttribute()
    {
        return $this->collectionPointTimeSlots->where('type', 'charity_pickup')->count() > 0;
    }

    public function notifyAllUsers($notification)
    {
        foreach ($this->collectionPointUsers as $collectionPointUser) {
            $collectionPointUser->user->notify($notification);
        }
    }

    public function smsAllUsers(SmsMessage $smsMessage)
    {
        $smsService = new SmsService();

        foreach ($this->collectionPointUsers as $collectionPointUser) {
            if ( ! empty($collectionPointUser->user->phone_number)) {
                $smsMessage->addPhoneNumber($collectionPointUser->user->phone_number);
            }
        }

        return $smsService->sendMessage($smsMessage);
    }
    public function meals() {
        return $this->hasMany(Meal::class);
    }
}
