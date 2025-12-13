<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'reference_number' => 'required|unique:letters,reference_number',
            'agenda_number' => 'nullable|string|max:100',
            'letter_date' => 'required|date',
            'classification_code' => 'required|exists:classifications,code',
            'description' => 'nullable|string|max:1000',
            'note' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array|max:10',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
        ];

        // Incoming letter
        if ($this->routeIs('incoming.*')) {
            $rules['from'] = 'required|string|max:255';
            $rules['received_date'] = 'required|date';
        }

        // Outgoing letter
        if ($this->routeIs('outgoing.*')) {
            $rules['to'] = 'required|string|max:255';
            $rules['reference_to'] = 'nullable|exists:letters,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'reference_number.required' => 'Nomor surat wajib diisi.',
            'reference_number.unique' => 'Nomor surat sudah digunakan.',
            'from.required' => 'Pengirim wajib diisi.',
            'to.required' => 'Tujuan wajib diisi.',
            'letter_date.required' => 'Tanggal surat wajib diisi.',
            'received_date.required' => 'Tanggal diterima wajib diisi.',
            'classification_code.required' => 'Klasifikasi wajib dipilih.',
            'classification_code.exists' => 'Klasifikasi tidak valid.',
            'attachments.max' => 'Maksimal 10 file lampiran.',
            'attachments.*.max' => 'Ukuran file maksimal 15MB.',
            'attachments.*.mimes' => 'Format file tidak didukung.',
        ];
    }
}
