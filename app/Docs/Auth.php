<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: '/api/auth/register'
)]
#[OA\PathItem(
    path: '/api/auth/login'
)]
#[OA\PathItem(
    path: '/api/auth/forgot-password'
)]
#[OA\PathItem(
    path: '/api/auth/reset-password'
)]
#[OA\PathItem(
    path: '/api/auth/logout'
)]
class Auth {}
