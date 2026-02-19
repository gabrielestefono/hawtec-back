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
        summary: 'Obter dados completos da landing page',
        description: 'Retorna todos os dados necessários para montar a página inicial: banners ativos, categorias, produtos em destaque, ofertas e mais vendidos.',
        responses: [
            new OA\Response(
                response: '200',
                description: 'Dados da landing page retornados com sucesso',
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
                                    description: 'Banners ativos ordenados por sort (apenas banners dentro do período starts_at/ends_at e is_active=true)',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'title', type: 'string', example: 'Super Oferta de Verão'),
                                            new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: 'Até 50% de desconto'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Aproveite descontos incríveis em produtos selecionados'),
                                            new OA\Property(property: 'button_label', type: 'string', nullable: true, example: 'Ver Ofertas'),
                                            new OA\Property(property: 'button_url', type: 'string', nullable: true, example: '/ofertas'),
                                            new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                            new OA\Property(property: 'sort', type: 'integer', example: 1, description: 'Ordem de exibição (menor valor = maior prioridade)'),
                                            new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-15T00:00:00.000000Z'),
                                            new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-28T23:59:59.000000Z'),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
                                            new OA\Property(
                                                property: 'images',
                                                description: 'Imagens do banner (via relacionamento polimórfico)',
                                                type: 'array',
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Banner'),
                                                        new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'path', type: 'string', example: 'banners/banner-verao-2026.jpg'),
                                                        new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Banner de ofertas de verão'),
                                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
                                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
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
                                    description: 'Todas as categorias de produtos ordenadas por nome, com contador de produtos',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                            new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos e tecnologia'),
                                            new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'heroicon-o-device-phone-mobile'),
                                            new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos'),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-01-10T08:00:00.000000Z'),
                                            new OA\Property(property: 'products_count', type: 'integer', example: 142, description: 'Quantidade de produtos nesta categoria'),
                                        ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'products',
                                    description: 'Produtos em destaque (apenas produtos com estoque > 0)',
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
                                new OA\Property(
                                    property: 'offers',
                                    description: 'Produtos com ofertas ativas no momento (mesma estrutura completa do array "products" acima)',
                                    type: 'array',
                                    items: new OA\Items(type: 'object', description: 'Cada item segue a mesma estrutura dos objetos no array "products"')
                                ),
                                new OA\Property(
                                    property: 'bestsellers',
                                    description: 'Produtos mais vendidos (top 8, mesma estrutura completa do array "products" acima)',
                                    type: 'array',
                                    items: new OA\Items(type: 'object', description: 'Cada item segue a mesma estrutura dos objetos no array "products"')
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
