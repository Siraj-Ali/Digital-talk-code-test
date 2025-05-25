<?php

namespace App\DTOs;

use App\Http\Requests\TranslationRequest;

class TranslationDTO extends BaseDTO
{
    public function __construct(
        public ?int $id = null,
        public int $locale_id,
        public string $key,
        public string $value,
        public string $device_type = 'desktop',
        public string $group = 'general',
        public bool $is_active = true,
    ) {}

    public static function fromRequest(TranslationRequest $request, ?int $id = null): self
    {
        return new self(
            id: $id,
            locale_id: $request->input('locale_id'),
            key: $request->input('key'),
            value: $request->input('value'),
            device_type: $request->input('device_type', 'desktop'),
            group: $request->input('group', 'general'),
            is_active: $request->boolean('is_active', true)
        );
    }
} 