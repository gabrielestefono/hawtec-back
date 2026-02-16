<?php

namespace App\Actions\Auth;

use App\Enums\Constants\StatusResponse;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordAction
{
    public function handle(ForgotPasswordRequest $request): JsonResponse
    {
        /**
         * @var array{email: string} $validated
         */
        $validated = $request->validated();

        $email = $validated['email'];

        $status = Password::sendResetLink(credentials: ['email' => $email]);

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(data: [
                'status' => StatusResponse::ERROR,
                'message' => 'Não foi possível enviar o link de reset de senha para o e-mail informado.',
                'data' => [],
            ], status: 422);
        }

        return response()->json(data: [
            'status' => StatusResponse::SUCCESS,
            'message' => 'Link de reset de senha enviado para o e-mail informado.',
            'data' => [],
        ]);
    }
}
