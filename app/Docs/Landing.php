<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: '/api/landing'
)]
#[OA\Get(
    path: '/api/landing',
    tags: ['Landing'],
    summary: 'Obter dados completos da landing page',
    description: 'Retorna todos os dados necessários para montar a página inicial, incluindo banners ativos, categorias, produtos em destaque, ofertas ativas e produtos mais vendidos. Todos os dados retornados respeitam as configurações de atividade (is_active) e períodos de validade (starts_at/ends_at).',
    responses: [
        new OA\Response(
            response: 200,
            description: 'Dados da landing page retornados com sucesso',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'message',
                        type: 'string',
                        example: 'Welcome to the API landing page!'
                    ),
                    new OA\Property(
                        property: 'status',
                        type: 'string',
                        example: 'success'
                    ),
                    new OA\Property(
                        property: 'data',
                        description: 'Container com todos os dados da landing',
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: 'banners',
                                description: 'Banners ativos ordenados por sort (apenas banners com is_active=true dentro do período starts_at/ends_at)',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'title', type: 'string', example: 'Super Oferta de Verão'),
                                        new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: 'Até 50% de desconto'),
                                        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Aproveite descontos incríveis em produtos selecionados'),
                                        new OA\Property(property: 'button_label', type: 'string', nullable: true, example: 'Ver Ofertas'),
                                        new OA\Property(property: 'button_url', type: 'string', nullable: true, example: '/ofertas'),
                                        new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                        new OA\Property(property: 'sort', type: 'integer', example: 1, description: 'Ordem de exibição: valor menor = maior prioridade'),
                                        new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-15T00:00:00.000000Z', description: 'Quando o banner começa a ser exibido'),
                                        new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-28T23:59:59.000000Z', description: 'Quando o banner deixa de ser exibido'),
                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-17T10:30:00.000000Z'),
                                        new OA\Property(
                                            property: 'images',
                                            description: 'Imagens do banner (relação polimórfica um-para-muitos)',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Banner', description: 'Tipo do modelo proprietário (polimórfico)'),
                                                    new OA\Property(property: 'imageable_id', type: 'integer', example: 1, description: 'ID do modelo proprietário'),
                                                    new OA\Property(property: 'path', type: 'string', example: 'banners/banner-verao-2026.jpg', description: 'Caminho relativo da imagem'),
                                                    new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'Banner de ofertas de verão', description: 'Texto alternativo para acessibilidade'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                                ]
                                            )
                                        ),
                                    ]
                                )
                            ),
                            new OA\Property(
                                property: 'categories',
                                description: 'Todas as categorias de produtos ordenadas por nome, com contador de produtos ativos em cada categoria',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Produtos eletrônicos e tecnologia'),
                                        new OA\Property(property: 'icon', type: 'string', nullable: true, example: 'heroicon-o-device-phone-mobile', description: 'Ícone CSS para exibição na UI'),
                                        new OA\Property(property: 'href', type: 'string', nullable: true, example: '/categorias/eletronicos', description: 'URL para navegar até a categoria'),
                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                        new OA\Property(property: 'products_count', type: 'integer', example: 142, description: 'Quantidade total de produtos nesta categoria com estoque > 0'),
                                    ]
                                )
                            ),
                            new OA\Property(
                                property: 'products',
                                description: 'Produtos em destaque ordenados por prioridade. Apenas produtos com estoque > 0 são retornados.',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'iPhone 15 Pro Max'),
                                        new OA\Property(property: 'slug', type: 'string', example: 'iphone-15-pro-max', description: 'URL-friendly identifier do produto'),
                                        new OA\Property(property: 'description', type: 'string', example: 'Smartphone Apple com chip A17 Pro', description: 'Descrição curta do produto'),
                                        new OA\Property(property: 'long_description', type: 'string', example: 'O iPhone 15 Pro Max possui tela Super Retina XDR de 6.7 polegadas...', description: 'Descrição detalhada do produto'),
                                        new OA\Property(property: 'brand', type: 'string', example: 'Apple', description: 'Marca/fabricante do produto'),
                                        new OA\Property(property: 'sku', type: 'string', example: 'IPHONE-15-PM-256-TIT', description: 'Stock Keeping Unit - código único do produto'),
                                        new OA\Property(property: 'price', type: 'string', example: '9999.00', description: 'Preço base/original do produto'),
                                        new OA\Property(property: 'badge', type: 'string', nullable: true, example: 'Lançamento', description: 'Badge de destaque (Lançamento, Oferta, Popular, etc)'),
                                        new OA\Property(property: 'stock_quantity', type: 'integer', example: 45, description: 'Quantidade em estoque'),
                                        new OA\Property(property: 'product_category_id', type: 'integer', example: 1, description: 'ID da categoria do produto'),
                                        new OA\Property(property: 'colors', type: 'array', items: new OA\Items(type: 'string'), example: ['Titânio Natural', 'Titânio Azul', 'Titânio Preto'], description: 'Variantes de cores disponíveis'),
                                        new OA\Property(property: 'specs', type: 'object', example: ['Tela' => '6.7"', 'Memória' => '256GB', 'Chip' => 'A17 Pro'], description: 'Especificações técnicas do produto (pares chave-valor)'),
                                        new OA\Property(property: 'current_price', type: 'string', example: '8999.00', description: 'Preço atual: com oferta se houver oferta ativa, senão igual a price'),
                                        new OA\Property(property: 'sale_price', type: 'string', example: '8999.00', description: 'Sinônimo de current_price'),
                                        new OA\Property(property: 'has_offer', type: 'boolean', example: true, description: 'Indica se o produto possui oferta ativa neste momento'),
                                        new OA\Property(property: 'discount_percentage', type: 'integer', nullable: true, example: 10, description: 'Percentual de desconto calculado da oferta ativa (null se sem oferta)'),
                                        new OA\Property(property: 'reviews_rating', type: 'number', format: 'float', example: 4.7, description: 'Média de avaliações com 1 casa decimal'),
                                        new OA\Property(property: 'reviews_count', type: 'integer', example: 89, description: 'Total de avaliações/reviews do produto'),
                                        new OA\Property(property: 'reviews_avg_rating', type: 'integer', example: 4, description: 'Média de avaliações arredondada (versão inteira)'),
                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                        new OA\Property(
                                            property: 'images',
                                            description: 'Imagens do produto (relação polimórfica um-para-muitos)',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 10),
                                                    new OA\Property(property: 'imageable_type', type: 'string', example: 'App\\Models\\Product'),
                                                    new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'path', type: 'string', example: 'products/iphone-15-pro-max-titanium.jpg'),
                                                    new OA\Property(property: 'alt', type: 'string', nullable: true, example: 'iPhone 15 Pro Max cor Titânio'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                                ]
                                            )
                                        ),
                                        new OA\Property(
                                            property: 'category',
                                            description: 'Categoria do produto (relação muitos-para-um, obrigatória)',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                                new OA\Property(property: 'name', type: 'string', example: 'Eletrônicos'),
                                                new OA\Property(property: 'description', type: 'string', nullable: true),
                                                new OA\Property(property: 'icon', type: 'string', nullable: true),
                                                new OA\Property(property: 'href', type: 'string', nullable: true),
                                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'offers',
                                            description: 'Ofertas associadas ao produto. Array vazio se não houver ofertas ativas no período',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 5),
                                                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'offer_price', type: 'string', example: '8999.00', description: 'Preço especial durante a oferta'),
                                                    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-15T00:00:00.000000Z', description: 'Quando a oferta começa a valer'),
                                                    new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true, example: '2026-02-28T23:59:59.000000Z', description: 'Quando a oferta deixa de valer'),
                                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                                ]
                                            )
                                        ),
                                    ]
                                )
                            ),
                            new OA\Property(
                                property: 'offers',
                                description: 'Produtos com ofertas ativas no momento. Retorna com a estrutura completa de produto (igual ao array "products")',
                                type: 'array',
                                items: new OA\Items(type: 'object')
                            ),
                            new OA\Property(
                                property: 'bestsellers',
                                description: 'Produtos mais vendidos (top 8). Retorna com a estrutura completa de produto (igual ao array "products")',
                                type: 'array',
                                items: new OA\Items(type: 'object')
                            ),
                        ]
                    ),
                ]
            )
        ),
    ]
)]
class Landing {}
