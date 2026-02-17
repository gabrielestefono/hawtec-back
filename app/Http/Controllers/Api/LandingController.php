<?php

namespace App\Http\Controllers\Api;

use App\Actions\Landing\LandingAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LandingController extends Controller
{
    #[OA\Get(
        path: '/api/landing',
        tags: ['Landing'],
        summary: 'Obter dados da landing page',
        responses: [
            new OA\Response(
                response: '200',
                description: 'Dados da landing page',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Welcome to the API landing page!'),
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'banners',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'title', type: 'string', example: 'Banner Principal'),
                                            new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: 'Subtítulo do banner'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Descrição do banner'),
                                            new OA\Property(property: 'button_label', type: 'string', nullable: true, example: 'Clique aqui'),
                                            new OA\Property(property: 'button_url', type: 'string', nullable: true, example: 'https://example.com'),
                                            new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                            new OA\Property(property: 'sort', type: 'integer', example: 1),
                                            new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-17T00:00:00.000000Z'),
                                            new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                            new OA\Property(
                                                property: 'images',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Banner'),
                                                        new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'path', type: 'string', example: 'banners/image.jpg'),
                                                        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'https://example.com/storage/banners/image.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Descrição da imagem'),
                                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                    ],
                                                    type: 'object'
                                                )
                                            ),
                                        ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'categories',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos em geral'),
                                            new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'icon-electronics'),
                                            new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                            new OA\Property(property: 'products_count', type: 'integer', example: 25),
                                        ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'products',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'Smartphone XYZ'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Smartphone de última geração'),
                                            new OA\Property(property: 'price', type: 'string', format: 'decimal', example: '1999.99'),
                                            new OA\Property(property: 'sale_price', type: 'string', format: 'decimal', nullable: true, example: '1899.99'),
                                            new OA\Property(property: 'has_offer', type: 'boolean', example: true),
                                            new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: 5),
                                            new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'new'),
                                            new OA\Property(property: 'stock_quantity', type: 'integer', example: 50),
                                            new OA\Property(
                                                property: 'images',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Product'),
                                                        new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'path', type: 'string', example: 'products/image.jpg'),
                                                        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'https://example.com/storage/products/image.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Imagem do produto'),
                                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                    ],
                                                    type: 'object'
                                                )
                                            ),
                                            new OA\Property(
                                                property: 'category',
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos'),
                                                    new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'icon-electronics'),
                                                    new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                ],
                                                nullable: true
                                            ),
                                            new OA\Property(property: 'reviews_count', type: 'integer', example: 15),
                                            new OA\Property(property: 'reviews_avg_rating', type: 'number', format: 'float', nullable: true, example: 4.5),
                                        ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'offers',
                                    type: 'array',
                                    description: 'Produtos em oferta (mesma estrutura de products)',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 2),
                                            new OA\Property(property: 'name', type: 'string', example: 'Tablet ABC'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Tablet com tecnologia avançada'),
                                            new OA\Property(property: 'price', type: 'string', format: 'decimal', example: '799.99'),
                                            new OA\Property(property: 'sale_price', type: 'string', format: 'decimal', example: '599.99'),
                                            new OA\Property(property: 'has_offer', type: 'boolean', example: true),
                                            new OA\Property(property: 'discount_percentage', type: 'integer', example: 25),
                                            new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'sale'),
                                            new OA\Property(property: 'stock_quantity', type: 'integer', example: 30),
                                            new OA\Property(
                                                property: 'images',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 2),
                                                        new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Product'),
                                                        new OA\Property(property: 'imageable_id', type: 'integer', example: 2),
                                                        new OA\Property(property: 'path', type: 'string', example: 'products/tablet.jpg'),
                                                        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'https://example.com/storage/products/tablet.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Tablet em oferta'),
                                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                    ],
                                                    type: 'object'
                                                )
                                            ),
                                            new OA\Property(
                                                property: 'category',
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos'),
                                                    new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'icon-electronics'),
                                                    new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00.000000Z'),
                                                ],
                                                nullable: true
                                            ),
                                            new OA\Property(property: 'reviews_count', type: 'integer', example: 8),
                                            new OA\Property(property: 'reviews_avg_rating', type: 'number', format: 'float', nullable: true, example: 4.2),
                                        ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'bestsellers',
                                    type: 'array',
                                    description: 'Produtos mais vendidos (mesma estrutura de products)',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 3),
                                            new OA\Property(property: 'name', type: 'string', example: 'Notebook Premium'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Notebook potente para trabalho'),
                                            new OA\Property(property: 'price', type: 'string', format: 'decimal', example: '3999.99'),
                                            new OA\Property(property: 'sale_price', type: 'string', format: 'decimal', nullable: true, example: null),
                                            new OA\Property(property: 'has_offer', type: 'boolean', example: false),
                                            new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: null),
                                            new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'hot'),
                                            new OA\Property(property: 'stock_quantity', type: 'integer', example: 15),
                                            new OA\Property(property: 'reviews_count', type: 'integer', example: 42),
                                            new OA\Property(property: 'reviews_avg_rating', type: 'number', format: 'float', nullable: true, example: 4.8),
                                        ],
                                        type: 'object'
                                    )
                                ),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return (new LandingAction)->handle();
    }
}
