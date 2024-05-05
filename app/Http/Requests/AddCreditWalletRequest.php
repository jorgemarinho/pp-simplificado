<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;


class AddCreditWalletRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize()
    {
        return true;
    }

    public function filters()
    {
        return [
            'amount' => 'capitalize'
        ];
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'O campo :attribute é obrigatório',
            'amount.required' => 'O campo :attribute é obrigatório',
            'amount.numeric' => 'O campo :attribute tem que ser numérico.',
            'amount.min' => 'O campo :attribute tem que ser maior que 0.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
