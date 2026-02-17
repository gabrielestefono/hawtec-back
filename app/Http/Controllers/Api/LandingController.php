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
