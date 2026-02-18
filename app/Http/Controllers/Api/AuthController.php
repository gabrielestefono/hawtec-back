<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\ForgotPasswordAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        summary: 'Cadastrar usuário',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation', 'accept_terms'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gabriel'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'secret123'),
                    new OA\Property(property: 'accept_terms', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: '200', description: 'Usuário criado', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Usuário criado com sucesso.'),
                    new OA\Property(property: 'data', type: 'object', example: []),
                ]
            )),
            new OA\Response(response: '422', description: 'Dados inválidos'),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        return (new RegisterAction)->handle(request: $request);
    }

    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        summary: 'Autenticar usuário',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: '200', description: 'Login efetuado', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Login realizado com sucesso.'),
                    new OA\Property(property: 'data', type: 'object', example: [
                        'user' => [
                            'id' => 1,
                            'name' => 'Gabriel',
                            'email' => 'gabriel@example.com',
                            'created_at' => '2024-01-01T00:00:00.000000Z',
                            'updated_at' => '2024-01-01T00:00:00.000000Z',
                        ],
                        'token' => '1|abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890',
                    ]),
                ]
            )),
            new OA\Response(response: '422', description: 'Credenciais inválidas'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        return (new LoginAction)->handle(request: $request);
    }

    #[OA\Post(
        path: '/api/auth/forgot-password',
        tags: ['Auth'],
        summary: 'Solicitar reset de senha',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: '200', description: 'Link enviado', content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string', example: 'success'),
                    new OA\Property(property: 'message', type: 'string', example: 'Link de reset de senha enviado para o e-mail informado.'),
                    new OA\Property(property: 'data', type: 'object', example: []),
                ]
            )),
            new OA\Response(response: '422', description: 'E-mail inválido'),
        ]
    )]
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return (new ForgotPasswordAction)->handle(request: $request);
    }

    #[OA\Post(
        path: '/api/auth/reset-password',
        tags: ['Auth'],
        summary: 'Resetar senha',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['token', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'token', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: '200', description: 'Senha atualizada'),
            new OA\Response(response: '422', description: 'Dados inválidos'),
        ]
    )]
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return (new ResetPasswordAction)->handle(request: $request);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        tags: ['Auth'],
        summary: 'Logout do usuário',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: '200', description: 'Logout realizado'),
            new OA\Response(response: '401', description: 'Não autenticado'),
        ]
    )]
    public function logout(): JsonResponse
    {
        return (new LogoutAction)->handle();
    }
}
