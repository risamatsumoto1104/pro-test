<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        $rules = [
            'postal_code' => 'required|regex:/\A[0-9]{3}-[0-9]{4}\z/',
            'address' => 'required',
            'building' => 'required'
        ];

        // ビューやリクエストパラメータに基づいて条件を設定
        if (request()->routeIs('mypage.profile.edit')) {
            // 'mypage.profile.edit' からのリクエストには name を必須にする
            $rules['name'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの形式（例: 123-4567）で入力してください。',
            'address.required' => '住所を入力してください。',
            'building.required' => '建物名を入力してください。',
        ];
    }
}
