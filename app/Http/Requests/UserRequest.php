<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class UserRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function filters()
    {
        return [
            'cnpj'  =>  'digit',
            'cpf'  =>  'digit',
            'phone' => 'digit',
            'user_type_id' => 'digit',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'O campo :attribute deve ser um e-mail válido.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'unique' => 'O campo :attribute já está em uso.',
            'cpf' => 'O campo :attribute deve ser um CPF válido.',
            'cnpj' => 'O campo :attribute deve ser um CNPJ válido.',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8',
            'full_name' => 'required|min:6',
            'cpf' => 'required|min:11|cpf|unique:people,cpf',
            'phone' => 'required|min:10',
            'cnpj' => 'min:14|sometimes|cnpj|unique:companies,cnpj',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
