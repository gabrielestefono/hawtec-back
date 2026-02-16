<?php

namespace App\Actions\Auth;

use App\Enums\Constants\StatusResponse;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    public function handle(ResetPasswordRequest $request): JsonResponse
    {
        /**
         * @var array{
         *      email: string,
         *      password: string,
         *      password_confirmation: string,
         *      token: string,
         * } $validated
         */
        $validated = $request->validated();

        $status = Password::reset(
            credentials: $validated,
            callback: function (User $user, string $password): void {
                $user->forceFill(attributes: [
                    'password' => $password,
                ])->setRememberToken(value: Str::random(length: 60));

                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(data: [
                'status' => StatusResponse::ERROR,
                'message' => 'Não foi possível resetar a senha. Verifique os dados informados e tente novamente.',
                'data' => [],
            ], status: 422);
        }

        return response()->json(data: [
            'status' => StatusResponse::SUCCESS,
            'message' => 'Senha resetada com sucesso.',
            'data' => [],
        ]);
    }
}
