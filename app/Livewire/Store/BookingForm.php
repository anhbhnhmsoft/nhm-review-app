<?php

namespace App\Livewire\Store;

use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class BookingForm extends Component
{
    private BookingService $bookingService;

    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $note;
    public $store_id;

    public function boot(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
        if (auth()->guard('web')->check()){
            $user = auth()->user();
            $this->customer_name = $user->name;
            $this->customer_email = $user->email;
            $this->customer_phone = $user->phone;
        }
    }

    public function mount($store_id)
    {
        $this->store_id = $store_id;
    }

    public function submit()
    {
        try {
            $data = $this->validate(
                [
                    'customer_name'  => ['required','string','max:255'],
                    'customer_phone' => ['required','string','max:20'],
                    'customer_email' => ['nullable','email'],
                    'note'  => ['nullable','string'],
                ],
                [
                    'customer_name.required'  => 'Vui lòng nhập tên.',
                    'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
                ]
            );
            $result = $this->bookingService->createBooking($data);
            if ($result){
                flash()->success('Gửi thông tin thành công!');
            }else{
                flash()->error('Có lỗi vui lòng thử lại sau!');
            }
            $this->reset(['customer_name','customer_phone','customer_email','note']);
            $this->dispatch('close-modal-booking');
        } catch (ValidationException $e) {
            foreach ($e->validator->errors()->all() as $msg) {
                flash()->error($msg);
            }
            return;
        }
    }

    public function render()
    {
        return view('components.store.booking-form');
    }
}
