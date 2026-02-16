<?php

namespace App\Actions\Auth;

use App\Enums\Constants\StatusResponse;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function handle(LoginRequest $request): JsonResponse
    {
        /**
         * @var array{
         *      email: string,
         *      password: string,
         * } $validated
         */
        $validated = $request->validated();

        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::query()->where(column: 'email', operator: $email)->first();

        if (! $user || ! Hash::check(value: $password, hashedValue: $user->password)) {
            return response()->json(data: [
                'message' => 'Credenciais inválidas.',
            ], status: 422);
        }

        return response()->json(data: [
            "status" => StatusResponse::SUCCESS,
            "message" => "Login realizado com sucesso.",
            "data" => [
                'user' => $user,
                'token' => $user->createToken(name: 'api')->plainTextToken,
            ]
        ]);
    }
}
