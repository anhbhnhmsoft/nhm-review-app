<?php

namespace App\Services;

use App\Models\Booking;

class BookingService
{


    public function createBooking($data)
    {
        try {
            Booking::create([
                'store_id'  => $data['store_id'] ?? null,
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'] ?? null,
                'note'  => trim($data['note'] ?? null),
            ]);
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }
}
