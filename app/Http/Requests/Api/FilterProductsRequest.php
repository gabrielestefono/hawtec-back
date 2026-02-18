<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FilterProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:product_categories,id'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0', 'gte:price_min'],
            'ratings' => ['nullable', 'array'],
            'ratings.*' => ['integer', 'min:1', 'max:5'],
            'only_offers' => ['nullable', 'boolean'],
            'in_stock' => ['nullable', 'boolean'],
            'sort_by' => [
                'nullable',
                'string',
                'in:newest,biggest_discount,most_reviewed,best_rating,highest_price,lowest_price,most_relevant',
            ],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'search' => 'busca',
            'categories' => 'categorias',
            'categories.*' => 'categoria',
            'price_min' => 'preço mínimo',
            'price_max' => 'preço máximo',
            'ratings' => 'avaliações',
            'ratings.*' => 'avaliação',
            'only_offers' => 'apenas promoções',
            'in_stock' => 'em estoque',
            'sort_by' => 'ordenação',
            'per_page' => 'itens por página',
            'page' => 'página',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'categories.*.exists' => 'A categoria selecionada não existe.',
            'price_max.gte' => 'O preço máximo deve ser maior ou igual ao preço mínimo.',
            'ratings.*.min' => 'A avaliação deve ser entre 1 e 5.',
            'ratings.*.max' => 'A avaliação deve ser entre 1 e 5.',
            'sort_by.in' => 'Ordenação inválida. Valores permitidos: newest, biggest_discount, most_reviewed, best_rating, highest_price, lowest_price, most_relevant.',
            'per_page.max' => 'Máximo de 100 itens por página.',
        ];
    }
}
