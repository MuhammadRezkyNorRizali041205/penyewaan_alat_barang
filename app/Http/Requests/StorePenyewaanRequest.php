<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenyewaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'items' => 'required|array|min:1',
            'items.*.alat_id' => 'required|integer|exists:alats,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai sewa wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai sewa wajib diisi.',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            'items.required' => 'Minimal 1 alat harus dipilih.',
            'items.*.alat_id.required' => 'ID alat wajib diisi.',
            'items.*.alat_id.exists' => 'Alat yang dipilih tidak ditemukan.',
            'items.*.jumlah.required' => 'Jumlah alat wajib diisi.',
            'items.*.jumlah.min' => 'Jumlah alat minimal 1.',
        ];
    }
}
