<?php

namespace App\Http\Controllers\Api;

use App\Actions\Product\FilterProductsAction;
use App\Actions\Product\ShowProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FilterProductsRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
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
                                                    new OA\Property(property: 'name', type: 'string', example: 'iPhone 15 Pro Max'),
                                                    new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro-max'),
                                                    new OA\Property(property: 'description', type: 'string', example: 'Smartphone Apple com chip A17 Pro'),
                                                    new OA\Property(property: 'long_description', type: 'string', example: 'O iPhone 15 Pro Max possui tela Super Retina XDR de 6.7 polegadas...'),
                                                    new OA\Property(property: 'brand', type: 'string', example: 'Apple'),
                                                    new OA\Property(property: 'sku', type: 'string', example: 'IPHONE-15-PM-256-TIT'),
                                                    new OA\Property(property: 'price', type: 'string', example: '9999.00', description: 'Preço original do produto'),
                                                    new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'Lançamento', description: 'Badge de destaque (Lançamento, Oferta, etc)'),
                                                    new OA\Property(property: 'stock_quantity', type: 'integer', example: 45),
                                                    new OA\Property(property: 'product_category_id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'colors', type: 'array', items: new OA\Items(type: 'string'), example: ['Titânio Natural', 'Titânio Azul', 'Titânio Preto']),
                                                    new OA\Property(property: 'specs', type: 'object', example: ['Tela' => '6.7"', 'Memória' => '256GB', 'Chip' => 'A17 Pro']),
                                                    new OA\Property(property: 'current_price', type: 'string', example: '8999.00', description: 'Preço atual (com oferta se houver, senão price)'),
                                                    new OA\Property(property: 'sale_price', type: 'string', example: '8999.00', description: 'Preço de venda (com oferta se houver, senão price)'),
                                                    new OA\Property(property: 'has_offer', type: 'boolean', example: true, description: 'Indica se possui oferta ativa'),
                                                    new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: 10, description: 'Percentual de desconto da oferta'),
                                                    new OA\Property(property: 'reviews_rating', type: 'number', format: 'float', example: 4.7, description: 'Média de avaliações arredondada'),
                                                    new OA\Property(property: 'reviews_count', type: 'integer', example: 89, description: 'Total de avaliações'),
                                                    new OA\Property(property: 'reviews_avg_rating', type: 'integer', example: 4, description: 'Média de avaliações (versão int)'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-15T09:15:00.000000Z'),
                                                    new OA\Property(
                                                        property: 'category',
                                                        description: 'Categoria do produto (obrigatória)',
                                                        type: 'object',
                                                        properties: [
                                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                                            new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos e tecnologia'),
                                                            new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'heroicon-o-device-phone-mobile'),
                                                            new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: 'images',
                                                        description: 'Imagens do produto',
                                                        type: 'array',
                                                        items: new OA\Items(
                                                            properties: [
                                                                new OA\Property(property: 'id', type: 'integer', example: 10),
                                                                new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Product'),
                                                                new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                                                new OA\Property(property: 'path', type: 'string', example: 'products/iphone-15-pro-max-titanium.jpg'),
                                                                new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'iPhone 15 Pro Max cor Titânio'),
                                                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                                                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                                                            ],
                                                            type: 'object'
                                                        )
                                                    ),
                                                    new OA\Property(
                                                        property: 'offers',
                                                        description: 'Ofertas associadas ao produto (pode estar vazio se não houver ofertas)',
                                                        type: 'array',
                                                        items: new OA\Items(
                                                            properties: [
                                                                new OA\Property(property: 'id', type: 'integer', example: 5),
                                                                new OA\Property(property: 'product_id', type: 'integer', example: 1),
                                                                new OA\Property(property: 'offer_price', type: 'string', example: '8999.00'),
                                                                new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-15T00:00:00.000000Z'),
                                                                new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-28T23:59:59.000000Z'),
                                                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-14T16:00:00.000000Z'),
                                                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-14T16:00:00.000000Z'),
                                                            ],
                                                            type: 'object'
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
        summary: 'Obter detalhes completos de um produto pelo slug',
        description: 'Retorna todos os dados de um produto específico incluindo imagens, categoria, ofertas e avaliações',
        parameters: [
            new OA\Parameter(
                name: 'slug',
                in: 'path',
                description: 'Slug único do produto',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'iphone-15-pro-max')
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'Detalhes completos do produto',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'iPhone 15 Pro Max'),
                        new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro-max'),
                        new OA\Property(property: 'description', type: 'string', example: 'Smartphone Apple com chip A17 Pro'),
                        new OA\Property(property: 'long_description', type: 'string', example: 'O iPhone 15 Pro Max possui tela Super Retina XDR de 6.7 polegadas...'),
                        new OA\Property(property: 'brand', type: 'string', example: 'Apple'),
                        new OA\Property(property: 'sku', type: 'string', example: 'IPHONE-15-PM-256-TIT'),
                        new OA\Property(property: 'price', type: 'string', example: '9999.00', description: 'Preço original do produto'),
                        new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'Lançamento', description: 'Badge de destaque (Lançamento, Oferta, etc)'),
                        new OA\Property(property: 'stock_quantity', type: 'integer', example: 45),
                        new OA\Property(property: 'product_category_id', type: 'integer', example: 1),
                        new OA\Property(property: 'colors', type: 'array', items: new OA\Items(type: 'string'), example: ['Titânio Natural', 'Titânio Azul', 'Titânio Preto']),
                        new OA\Property(property: 'specs', type: 'object', example: ['Tela' => '6.7"', 'Memória' => '256GB', 'Chip' => 'A17 Pro']),
                        new OA\Property(property: 'current_price', type: 'string', example: '8999.00', description: 'Preço atual (com oferta se houver, senão price)'),
                        new OA\Property(property: 'sale_price', type: 'string', example: '8999.00', description: 'Preço de venda (com oferta se houver, senão price)'),
                        new OA\Property(property: 'has_offer', type: 'boolean', example: true, description: 'Indica se possui oferta ativa'),
                        new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: 10, description: 'Percentual de desconto da oferta'),
                        new OA\Property(property: 'reviews_rating', type: 'number', format: 'float', example: 4.7, description: 'Média de avaliações arredondada'),
                        new OA\Property(property: 'reviews_count', type: 'integer', example: 89, description: 'Total de avaliações'),
                        new OA\Property(property: 'reviews_avg_rating', type: 'integer', example: 4, description: 'Média de avaliações (versão int)'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-15T09:15:00.000000Z'),
                        new OA\Property(
                            property: 'category',
                            description: 'Categoria do produto (obrigatória)',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos e tecnologia'),
                                new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'heroicon-o-device-phone-mobile'),
                                new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                            ]
                        ),
                        new OA\Property(
                            property: 'images',
                            description: 'Imagens do produto',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 10),
                                    new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Product'),
                                    new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'path', type: 'string', example: 'products/iphone-15-pro-max-titanium.jpg'),
                                    new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'iPhone 15 Pro Max cor Titânio'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-20T14:30:00.000000Z'),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(
                            property: 'offers',
                            description: 'Ofertas associadas ao produto',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 5),
                                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'offer_price', type: 'string', example: '8999.00'),
                                    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-15T00:00:00.000000Z'),
                                    new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-28T23:59:59.000000Z'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-14T16:00:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-14T16:00:00.000000Z'),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(
                            property: 'reviews',
                            description: 'Avaliações do produto com informações do usuário',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 15),
                                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'user_id', type: 'integer', example: 42),
                                    new OA\Property(property: 'rating', type: 'integer', example: 5, description: 'Nota de 1 a 5'),
                                    new OA\Property(property: 'title', type: 'string', example: 'Excelente produto!'),
                                    new OA\Property(property: 'comment', type: 'string', example: 'Superou todas as expectativas, recomendo!'),
                                    new OA\Property(property: 'verified', type: 'boolean', example: true, description: 'Indica se a compra foi verificada'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-10T16:45:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-10T16:45:00.000000Z'),
                                    new OA\Property(
                                        property: 'user',
                                        description: 'Dados do usuário que fez a avaliação',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 42),
                                            new OA\Property(property: 'name', type: 'string', example: 'João Silva'),
                                            new OA\Property(property: 'email', type: 'string', example: 'joao@example.com'),
                                        ]
                                    ),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: '404',
                description: 'Produto não encontrado'
            ),
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        return (new ShowProductAction)->handle(product: $product);
    }
}
