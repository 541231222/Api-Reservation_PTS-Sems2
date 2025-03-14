<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'car_id' => 'required|integer|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'proof_of_payment' => 'required|string',
            'payment_status' => 'required|string',
            'status' => 'required|string',
        ];
    }

    public function messages(): array
{
    return [
        'user_id.exists' => 'User ID tidak valid.',
        'user_id.required' => 'User ID wajib diisi.',
        'user_id.integer' => 'User ID harus berupa angka.',

        'car_id.exists' => 'Car ID tidak valid.',
        'car_id.required' => 'Car ID wajib diisi.',
        'car_id.integer' => 'Car ID harus berupa angka.',

        'start_date.required' => 'Tanggal mulai wajib diisi.',
        'start_date.date' => 'Tanggal mulai harus berupa format tanggal yang valid.',

        'end_date.required' => 'Tanggal selesai wajib diisi.',
        'end_date.date' => 'Tanggal selesai harus berupa format tanggal yang valid.',
        'end_date.after' => 'Tanggal selesai harus lebih dari tanggal mulai.',

        'proof_of_payment.required' => 'Bukti pembayaran wajib diisi.',
        'proof_of_payment.string' => 'Bukti pembayaran harus berupa string.',

        'payment_status.required' => 'Status pembayaran wajib diisi.',

        'status.required' => 'Status wajib diisi.',
    ];
}

}

//ERROR GAMUNCUL MESSAGENYA DI CONTROLLER AWOEKDADAJFPJAEPGJEPGJFEPFEJQFJQEFIJ PADAHAL 200 OK NGAKAK
//UDAH DIBENERIN GUSY TERNYATA NAMBAH HEADER DI POSTMAN (authorization dan json)
