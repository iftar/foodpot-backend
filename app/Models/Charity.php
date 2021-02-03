<?php

namespace App\Models;

use App\Services\All\SmsService;
use App\Notifications\SmsMessage;
use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    protected $table = 'charities';

    protected $fillable = [
        'name',
        'registration_number',
        'max_delivery_capacity',
        'company_website',
        'contact_telephone',
        'personal_email',
        'personal_number',
        'has_food_hygiene_cert',
        'logo'
    ];

    protected $casts = [
        'max_delivery_capacity' => 'integer',
    ];

    public function collectionPoints()
    {
        return $this->belongsToMany(CollectionPoint::class, 'charity_collection_points');
    }

    public function charityUsers()
    {
        return $this->hasMany(CharityUser::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function notifyAllUsers($notification)
    {
        foreach ($this->charityUsers as $charityUser) {
            $charityUser->user->notify($notification);
        }
    }

    public function smsAllUsers(SmsMessage $smsMessage)
    {
        $smsService = new SmsService();

        foreach ($this->charityUsers as $charityUser) {
            if ( ! empty($charityUser->user->phone_number)) {
                $smsMessage->addPhoneNumber($charityUser->user->phone_number);
            }
        }

        return $smsService->sendMessage($smsMessage);
    }
}
