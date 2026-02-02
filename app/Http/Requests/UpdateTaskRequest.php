<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
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
            'title.string' => 'O campo título deve ser um texto.',
            'title.max' => 'O campo título não pode ter mais de 255 caracteres.',
            'description.string' => 'O campo descrição deve ser um texto.',
            'completed.boolean' => 'O campo concluído deve ser verdadeiro ou falso.',
        ];
    }
}
