<?php

namespace App\Actions\Landing;

use Illuminate\Http\JsonResponse;

class LandingAction
{
    public function handle(): JsonResponse
    {
        return response()->json(
            data: [
                'message' => 'Welcome to the API landing page!',
                'status' => 'success',
                'data' => [
                    'banners' => (new BannerAction)->handle(),
                ],
            ]
        );
    }
}
