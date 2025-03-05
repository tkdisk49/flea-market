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
            'name' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'description' => ['required', 'max:255'],
            'condition' => ['required', 'integer', 'min:1', 'max:4'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '金額を入力してください',
            'image.required' => '商品画像を登録してください',
            'image.mimes' => '無効なファイル形式です',
            'categories.required' => 'カテゴリーを選択してください',
            'description.required' => '商品説明を入力してください',
            'condition.required' => '商品の状態を選択してください',
        ];
    }
}
