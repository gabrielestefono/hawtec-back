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
                                            new OA\Property(property: 'subtitle', type: 'string', example: 'Subtítulo do banner'),
                                            new OA\Property(property: 'description', type: 'string', example: 'Descrição do banner'),
                                            new OA\Property(property: 'button_label', type: 'string', example: 'Clique aqui'),
                                            new OA\Property(property: 'button_url', type: 'string', format: 'uri', example: 'https://example.com'),
                                            new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                            new OA\Property(property: 'sort', type: 'integer', example: 1),
                                            new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(
                                                property: 'images',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'https://example.com/image.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Descrição da imagem'),
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
                                            new OA\Property(property: 'href', type: 'string', format: 'uri', nullable: true, example: '/categorias/eletronicos'),
                                            new OA\Property(property: 'products_count', type: 'integer', example: 25),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
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
                                            new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'new'),
                                            new OA\Property(property: 'stock_quantity', type: 'integer', example: 50),
                                            new OA\Property(property: 'current_price', type: 'string', example: '1899.99'),
                                            new OA\Property(property: 'product_category_id', type: 'integer', nullable: true, example: 1),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                            new OA\Property(
                                                property: 'images',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'https://example.com/product.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Imagem do produto'),
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
                                                ],
                                                nullable: true
                                            ),
                                            new OA\Property(
                                                property: 'offers',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'offer_price', type: 'string', format: 'decimal', example: '1899.99'),
                                                        new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', example: '2026-02-17T00:00:00Z'),
                                                        new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', example: '2026-02-24T00:00:00Z'),
                                                    ],
                                                    type: 'object'
                                                )
                                            ),
                                            new OA\Property(property: 'reviews_count', type: 'integer', example: 15),
                                            new OA\Property(property: 'reviews_avg_rating', type: 'number', format: 'float', example: 4.5),
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
