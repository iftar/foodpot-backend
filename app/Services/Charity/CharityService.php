<?php

namespace App\Services\Charity;

use App\Models\Charity;
use App\Events\Charity\Updated;
use App\Events\Charity\Created;

class CharityService
{
    public function get()
    {
        $user = auth()->user();

        return $user->charities->first();
    }

    public function create($data = [])
    {
        $charity = Charity::create([
            "name"                  => $data["name"],
            "registration_number"   => $data["registration_number"] ?? null,
            "max_delivery_capacity" => $data["max_delivery_capacity"] ?? 0,
            'company_website'       => $data['company_website'],
            'contact_telephone'     => $data['contact_telephone'],
            'personal_email'        => $data['personal_email'],
            'personal_number'       => $data['personal_number'],
            'has_food_hygiene_cert' => $data['has_food_hygiene_cert'],
            'logo'                  => $data['logo'],

        ]);

        event(new Created($charity));

        return $charity;
    }

    public function update(Charity $charity, $data)
    {
        $charity->update([
            'name'                  => $data['name'] ?? $charity->name,
            'registration_number'   => $data['registration_number'] ?? $charity->registration_number,
            'max_delivery_capacity' => $data['max_delivery_capacity'] ?? $charity->max_delivery_capacity,
            'company_website'       => $data['company_website'] ?? $charity->company_website,
            'contact_telephone'     => $data['contact_telephone'] ?? $charity->contact_telephone,
            'personal_email'        => $data['personal_email'] ?? $charity->personal_email,
            'personal_number'       => $data['personal_number'] ?? $charity->personal_number,
            'has_food_hygiene_cert' => $data['has_food_hygiene_cert'] ?? $charity->has_food_hygiene_cert,
            'logo'                  => $data['logo'] ?? $charity->logo,
        ]);

        event(new Updated($charity));

        return $charity->fresh();
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new Charity())->getFillable())
        );
    }
}
