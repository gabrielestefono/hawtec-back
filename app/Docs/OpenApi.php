<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'Hawtec API',
        version: '1.0.0',
        description: 'Documentação OpenAPI da API.'
    ),
    servers: [
        new OA\Server(
            url: '/',
            description: 'Servidor padrão'
        ),
    ],
    security: [
        ['sanctum' => []],
    ]
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token'
)]
class OpenApi {}
