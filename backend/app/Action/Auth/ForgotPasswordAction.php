<?php

declare(strict_types=1);

namespace App\Action\Auth;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

final class ForgotPasswordAction
{
    public function execute(ForgotPasswordRequest $request): AuthenticationResponse
    {
        $response = Password::sendResetLink([
            'email' => $request ->getEmail()
        ]);

        $message = $response == Password::RESET_LINK_SENT ? 'Mail send successfully' : GLOBAL_SOMETHING_WANTS_TO_WRONG;

        $response = ['data'=>'','message' => $message];

        $token = $response;
        return new AuthenticationResponse(
            $token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }
}
