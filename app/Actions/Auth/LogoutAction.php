<?php

namespace App\Actions\Auth;

use App\Enums\Constants\StatusResponse;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutAction
{
    public function handle(): JsonResponse
    {
        /**
         * @var Factory|Guard $auth
         */
        $auth = auth();

        /**
         * @var User|null $user
         */
        $user = $auth->user();

        if (! $user) {
            return response()->json(data: [
                'status' => StatusResponse::ERROR,
                'message' => 'Usuário não autenticado.',
                'data' => [],
            ], status: 401);
        }

        /**
         * @var PersonalAccessToken $accessToken
         */
        $accessToken = $user?->currentAccessToken();

        $accessToken?->delete();

        return response()->json([
            'status' => StatusResponse::SUCCESS,
            'message' => 'Logout realizado com sucesso.',
            'data' => [],
        ]);
    }
}
