<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'item_name' => 'required',
            'description' => 'required|max:255',
            'item_image' => 'required|mimes:jpeg,png',
            'categories' => 'required|array|min:1',
            'condition' => 'required',
            'price' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名は必須です。',
            'description.required' => '商品説明は必須です。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'item_image.required' => '商品画像をアップロードしてください。',
            'item_image.mimes' => '商品画像は.jpegまたは.png形式でアップロードしてください。',
            'categories.required' => '商品のカテゴリーを選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '商品価格は必須です。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
