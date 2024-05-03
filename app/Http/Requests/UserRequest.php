<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    public function filters()
    {
        return [
            'company.cnpj'  =>  'digit',
            'people.cpf'  =>  'digit',
            'people.phone' => 'digit',
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
            'user.user_type_id' => [
                'required',
            ],
            'user.email' => [
                'required',
                'email:rfc,dns',
                'unique:users,email'
            ],
            'user.password' => [
                'required',
                'min:6'
            ],
            'people.full_name' => [
                'required',
                'min:6'
            ],
            'people.cpf' => [
                'required',
                'min:11',
                'cpf|unique:people,cpf'
            ],
            'people.phone' => [
                'required',
                'min:11'
            ],
            'company.cnpj' => [
                'min:14',
                'sometimes|cnpj|unique:companies,cnpj'
            ],
        ];
    }
}
