<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchPedidoRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     * No Yii2, isso costuma ser feito em behaviors/access.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aqui é onde você define as "rules", equivalente ao método rules() do seu Model no Yii2.
     * O Laravel valida os dados ANTES de chegar no seu Controller.
     */
    public function rules(): array
    {
        return [
            'numero'      => 'nullable|integer|max:50',
            'cliente'     => 'nullable|string|max:100',
            'fornecedor'  => 'nullable|string|max:100',
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date|after_or_equal:data_inicio',
            'valor_min'   => 'nullable|string', // Validado como string pois pode vir com vírgula
            'valor_max'   => 'nullable|string',
        ];
    }

    /**
     * Personalização de mensagens de erro (opcional).
     */
    public function messages(): array
    {
        return [
            'data_fim.after_or_equal' => 'A data final deve ser maior ou igual à data inicial.',
            'data_inicio.date'        => 'A data inicial deve ser uma data válida.',
            'data_fim.date'           => 'A data final deve ser uma data válida.',
        ];
    }
}
