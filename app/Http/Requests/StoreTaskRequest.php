<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Função para verificar se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Função para definir as regras de validação para a requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Função para definir as mensagens de erro para a validação.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O campo título é obrigatório.',
            'title.string' => 'O campo título deve ser um texto.',
            'title.max' => 'O campo título não pode ter mais de 255 caracteres.',
            'description.string' => 'O campo descrição deve ser um texto.',
        ];
    }
}
