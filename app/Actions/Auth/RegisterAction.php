<?php

namespace App\Actions\Auth;

use App\Enums\Constants\StatusResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterAction
{
    public function handle(RegisterRequest $request): JsonResponse
    {
        /**
         * @var array{
         *      name: string,
         *      email: string,
         *      password: string,
         *      password_confirmation: string,
         *      accept_terms: bool,
         * } $validated
         */
        $validated = $request->validated();

        User::query()->create(attributes: $validated);

        return response()->json(
            data: [
                "status" => StatusResponse::SUCCESS,
                "message" => "Usuário criado com sucesso.",
                "data" => []
            ]
        );
    }
}
