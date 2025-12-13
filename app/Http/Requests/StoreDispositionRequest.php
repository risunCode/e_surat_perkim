<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'to' => 'required|string|max:255',
            'due_date' => 'required|date|after_or_equal:today',
            'content' => 'required|string|max:1000',
            'note' => 'nullable|string|max:500',
            'letter_status' => 'required|exists:letter_statuses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'to.required' => 'Tujuan disposisi wajib diisi.',
            'due_date.required' => 'Tanggal jatuh tempo wajib diisi.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum hari ini.',
            'content.required' => 'Isi disposisi wajib diisi.',
            'letter_status.required' => 'Status wajib dipilih.',
            'letter_status.exists' => 'Status tidak valid.',
        ];
    }
}
