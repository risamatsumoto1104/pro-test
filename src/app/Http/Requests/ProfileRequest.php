<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile-image' => 'mimes:jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'profile-image.mimes' => 'プロフィール画像は.jpegまたは.png形式でアップロードしてください。',
        ];
    }
}
