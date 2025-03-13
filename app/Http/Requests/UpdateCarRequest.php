<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'required|string',
            'brand_name' => 'required|string|max:255',
            'price_per_day' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama mobil harus diisi',
            'name.string' => 'Nama mobil harus berupa string',
            'name.max' => 'Nama mobil maksimal 255 karakter',
            'image.required' => 'Gambar mobil harus diisi',
            'image.string' => 'Gambar mobil harus berupa string',
            'brand_name.required' => 'Nama brand harus diisi',
            'brand_name.string' => 'Nama brand harus berupa string',
            'brand_name.max' => 'Nama brand maksimal 255 karakter',
            'price_per_day.required' => 'Harga per hari harus diisi',
            'price_per_day.integer' => 'Harga per hari harus berupa angka',
            'price_per_day.min' => 'Harga per hari tidak boleh negatif',
            'stock.required' => 'Stok harus diisi',
            'stock.integer' => 'Stok harus berupa angka',
            'stock.min' => 'Stok tidak boleh negatif'
        ];
    }
}
