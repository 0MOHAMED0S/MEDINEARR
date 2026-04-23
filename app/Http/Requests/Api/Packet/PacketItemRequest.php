<?php

namespace App\Http\Requests\Api\Packet;

use Illuminate\Foundation\Http\FormRequest;

class PacketItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:image,note,medicine',
            'note' => 'nullable|string',
            'image' => 'nullable|image',
            'medicine_id' => 'nullable|exists:medicines,id'
        ];
    }
}
