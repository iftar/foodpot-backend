<?php

namespace App\Http\Requests\API\User\Order;

use App\Http\Requests\API\User\AuthenticatedRequest;

class StoreRequest extends AuthenticatedRequest
{
    public function rules()
    {
        return [
            'collection_point_id'           => 'required|exists:collection_points,id',
            'collection_point_time_slot_id' => 'required|exists:collection_point_time_slots,id',
            'first_name'                    => 'required',
            'last_name'                     => 'required',
            'email'                         => 'required|email',
        ];
    }
}
