<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'locale_id' => 'required|exists:locales,id',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'device_type' => 'required',
            'group' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ];
        return $rules;
    }
} 