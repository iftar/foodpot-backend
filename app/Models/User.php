<?php

namespace App\Models;

use App\Services\All\SmsService;
use App\Notifications\SmsMessage;
use Laravel\Passport\HasApiTokens;
use App\Notifications\User\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $statuses = [
        'approved' => 'Approved',
        'disabled' => 'Disabled'
    ];

    protected $types    = [
        'user'             => 'User',
        'charity'          => 'Charity User',
        'collection-point' => 'Collection Point User'
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'phone_number',
        'password',
        'remember_token',
        'type',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'tokens'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function charity()
    {
        return $this->charities->first();
    }

    public function charities()
    {
        return $this->belongsToMany(Charity::class, 'charity_users');
    }

    public function collectionPoint()
    {
        return $this->collectionPoints->first();
    }

    public function collectionPoints()
    {
        return $this->belongsToMany(CollectionPoint::class, 'collection_point_users');
    }

    public function sms(SmsMessage $smsMessage)
    {
        $smsService = new SmsService();

        if ( ! empty($this->phone_number)) {
            $smsMessage->addPhoneNumber($this->phone_number);
        }

        return $smsService->sendMessage($smsMessage);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function isApproved()
    {
        return $this->status == 'approved';
    }

    public function isType($type)
    {
        return $this->type == $type;
    }
}
