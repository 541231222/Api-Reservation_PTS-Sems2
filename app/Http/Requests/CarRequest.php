<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'required|string',
            'brand_name' => 'required|string|max:255',
            'price_per_day' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    /**
     * Define custom validation messages for the request.
     */
    public function messages(): array
    {
        return [
          'user_id.required' => 'User ID wajib diisi.',
            'user_id.exists' => 'User ID tidak valid.',
            'category_id.required' => 'Category ID wajib diisi.',
            'category_id.exists' => 'Category ID tidak valid.',
            'name.required' => 'Nama mobil wajib diisi.',
            'name.string' => 'Nama mobil harus berupa teks.',
            'image.required' => 'Gambar mobil wajib diunggah.',
            'brand_name.required' => 'Nama brand wajib diisi.',
            'price_per_day.required' => 'Harga per hari wajib diisi.',
            'price_per_day.integer' => 'Harga per hari harus berupa angka.',
            'price_per_day.min' => 'Harga per hari tidak boleh kurang dari 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
        ];
    }
}
