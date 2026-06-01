<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator as ValidationValidator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Conversation;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return false;
        }

        return Conversation::where('id', $this->conversation_id)
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->exists();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'message' => $this->message ? trim($this->message) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'conversation_id' => 'required|exists:conversations,id',
            'type' => 'required|in:text,image,video,audio,file',
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:20480',
        ];
    }

    public function withValidator(ValidationValidator $validator)
    {
        $validator->after(function (ValidationValidator $validator) {

            $type = $this->type;
            $file = $this->file('file');
            $message = $this->message;

            if (!$message && !$file) {
                $validator->errors()->add('message', 'Message or file is required.');
            }

            if ($type === 'text' && !$message) {
                $validator->errors()->add('message', 'Text message is required.');
            }

            if ($type !== 'text' && !$file) {
                $validator->errors()->add('file', 'File is required.');
            }

            if ($file) {
                $mime = $file->getMimeType();

                match ($type) {
                    'image' => !str_starts_with($mime, 'image/') && $validator->errors()->add('file', 'Must be image'),
                    'video' => !str_starts_with($mime, 'video/') && $validator->errors()->add('file', 'Must be video'),
                    'audio' => !str_starts_with($mime, 'audio/') && $validator->errors()->add('file', 'Must be audio'),
                    default => null
                };
            }
        });
    }

    protected function failedValidation(ValidatorContract $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403));
    }
}