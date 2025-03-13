<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_status' => 'required|in:pending,waiting,success,failed',
            'status' => 'required|in:pending,on_the_road,completed'
        ];
    }

    public function messages()
    {
        return [
            'payment_status.required' => 'Status pembayaran harus diisi',
            'payment_status.in' => 'Status pembayaran tidak valid',
            'status.required' => 'Status reservasi harus diisi',
            'status.in' => 'Status reservasi tidak valid'
        ];
    }
}
