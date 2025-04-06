<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chat_message' => 'required|max:400',
            'message_image' => 'nullable|mimes:jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'chat_message.required' => '本文を入力してください。',
            'chat_message.max' => '本文は400文字以内で入力してください。',
            'message_image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください。',
        ];
    }
}
