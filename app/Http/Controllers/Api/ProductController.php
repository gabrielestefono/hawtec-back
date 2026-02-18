<?php

namespace App\Http\Controllers\Api;

use App\Actions\Product\FilterProductsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterProductsRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Listar e filtrar produtos',
        parameters: [
            new OA\Parameter(
                name: 'search',
                in: 'query',
                description: 'Termo de busca para nome e descrição do produto',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'notebook')
            ),
            new OA\Parameter(
                name: 'categories[]',
                in: 'query',
                description: 'IDs das categorias para filtrar (pode enviar múltiplos)',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer')),
                style: 'form',
                explode: true
            ),
            new OA\Parameter(
                name: 'price_min',
                in: 'query',
                description: 'Preço mínimo',
                required: false,
                schema: new OA\Schema(type: 'number', format: 'float', example: 100.00)
            ),
            new OA\Parameter(
                name: 'price_max',
                in: 'query',
                description: 'Preço máximo',
                required: false,
                schema: new OA\Schema(type: 'number', format: 'float', example: 5000.00)
            ),
            new OA\Parameter(
                name: 'ratings[]',
                in: 'query',
                description: 'Notas de avaliação para filtrar (1-5, pode enviar múltiplas)',
                required: false,
                schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer', enum: [1, 2, 3, 4, 5])),
                style: 'form',
                explode: true
            ),
            new OA\Parameter(
                name: 'only_offers',
                in: 'query',
                description: 'Filtrar apenas produtos em promoção',
                required: false,
                schema: new OA\Schema(type: 'boolean', example: true)
            ),
            new OA\Parameter(
                name: 'in_stock',
                in: 'query',
                description: 'Filtrar apenas produtos em estoque',
                required: false,
                schema: new OA\Schema(type: 'boolean', example: true)
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                description: 'Critério de ordenação',
                required: false,
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['newest', 'biggest_discount', 'most_reviewed', 'best_rating', 'highest_price', 'lowest_price', 'most_relevant'],
                    example: 'newest'
                )
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Número de itens por página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 15, default: 15)
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                description: 'Número da página',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1, default: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Lista de produtos filtrados',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Produtos recuperados com sucesso'),
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'products',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                        new OA\Property(
                                            property: 'data',
                                            type: 'array',
                                            items: new OA\Items(
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Notebook Dell Inspiron'),
                                                    new OA\Property(property: 'description', type: 'string', example: 'Notebook potente para trabalho'),
                                                    new OA\Property(property: 'price', type: 'string', example: '3500.00'),
                                                    new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'Lançamento'),
                                                    new OA\Property(property: 'stock_quantity', type: 'integer', example: 10),
                                                    new OA\Property(property: 'product_category_id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'current_price', type: 'string', example: '2999.00'),
                                                    new OA\Property(property: 'sale_price', type: 'string', nullable: true, example: '2999.00'),
                                                    new OA\Property(property: 'has_offer', type: 'boolean', example: true),
                                                    new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: 14),
                                                    new OA\Property(property: 'average_rating', type: 'number', format: 'float', nullable: true, example: 4.5),
                                                    new OA\Property(property: 'reviews_count', type: 'integer', example: 25),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                                    new OA\Property(
                                                        property: 'category',
                                                        type: 'object',
                                                        properties: [
                                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                                            new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: 'images',
                                                        type: 'array',
                                                        items: new OA\Items(
                                                            properties: [
                                                                new OA\Property(property: 'id', type: 'integer'),
                                                                new OA\Property(property: 'path', type: 'string'),
                                                                new OA\Property(property: 'url', type: 'string'),
                                                                new OA\Property(property: 'alt', type: 'string', nullable: true),
                                                            ]
                                                        )
                                                    ),
                                                ],
                                                type: 'object'
                                            )
                                        ),
                                        new OA\Property(property: 'first_page_url', type: 'string'),
                                        new OA\Property(property: 'from', type: 'integer'),
                                        new OA\Property(property: 'last_page', type: 'integer'),
                                        new OA\Property(property: 'last_page_url', type: 'string'),
                                        new OA\Property(property: 'next_page_url', type: 'string', nullable: true),
                                        new OA\Property(property: 'path', type: 'string'),
                                        new OA\Property(property: 'per_page', type: 'integer'),
                                        new OA\Property(property: 'prev_page_url', type: 'string', nullable: true),
                                        new OA\Property(property: 'to', type: 'integer'),
                                        new OA\Property(property: 'total', type: 'integer'),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: '422',
                description: 'Erro de validação',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            additionalProperties: new OA\AdditionalProperties(type: 'array', items: new OA\Items(type: 'string'))
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(FilterProductsRequest $request): JsonResponse
    {
        return (new FilterProductsAction)->handle(request: $request);
    }

    #[OA\Get(
        path: '/api/products/{slug}',
        tags: ['Products'],
        summary: 'Obter detalhes de um produto pelo slug',
        parameters: [
            new OA\Parameter(
                name: 'slug',
                in: 'path',
                description: 'Slug único do produto',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'hawtec-pro-x1')
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Detalhes do produto',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'slug', type: 'string'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'description', type: 'string'),
                        new OA\Property(property: 'longDescription', type: 'string'),
                        new OA\Property(property: 'price', type: 'number', format: 'float'),
                        new OA\Property(property: 'originalPrice', type: 'number', format: 'float'),
                        new OA\Property(property: 'discountPercent', type: 'integer', nullable: true),
                        new OA\Property(property: 'images', type: 'array', items: new OA\Items(type: 'string')),
                        new OA\Property(property: 'rating', type: 'number', format: 'float'),
                        new OA\Property(property: 'reviewCount', type: 'integer'),
                        new OA\Property(property: 'badge', type: 'string', nullable: true),
                        new OA\Property(property: 'category', type: 'string'),
                        new OA\Property(property: 'brand', type: 'string'),
                        new OA\Property(property: 'sku', type: 'string'),
                        new OA\Property(property: 'inStock', type: 'boolean'),
                        new OA\Property(property: 'stockCount', type: 'integer'),
                        new OA\Property(property: 'colors', type: 'array'),
                        new OA\Property(property: 'specs', type: 'array'),
                        new OA\Property(property: 'reviews', type: 'array'),
                    ]
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Produto não encontrado'
            ),
        ]
    )]
    public function show(Product $product): JsonResource
    {
        $product->loadMissing(['images', 'category', 'reviews.user']);

        return new ProductResource(resource: $product);
    }
}
