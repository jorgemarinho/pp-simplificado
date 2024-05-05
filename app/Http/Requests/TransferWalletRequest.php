<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;


class TransferWalletRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize()
    {
        return true;
    }

    public function filters()
    {
        return [
            'value' => 'capitalize'
        ];
    }

    public function rules()
    {
        return [
            'payer_user_id' => 'required',
            'payee_user_id' => 'required',
            'value' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'payer_user_id.required' => 'O campo :attribute é obrigatório',
            'payee_user_id.required' => 'O campo :attribute é obrigatório',
            'value.required' => 'O campo :attribute é obrigatório',
            'value.numeric' => 'O campo :attribute tem que ser numérico.',
            'value.min' => 'O campo :attribute tem que ser maior que 0.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}