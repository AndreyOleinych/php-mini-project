<?php

declare(strict_types=1);

namespace App\Action\Auth;

use App\Repository\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class ResetPasswordAction
{
    public function __construct(private UserRepository $userRepository, private Mailer $mailer)
    {
    }

    public function execute(ResetPasswordRequest $request): AuthenticationResponse
    {
        $reset_password_status = Password::reset([
            'email' => $request->getEmail(),
            'password' => $request->getPassword(),
            'password_confirmation' => $request->getPasswordConfirmation(),
            'token' => $request->getToken(),
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

//        if ($reset_password_status == Password::INVALID_TOKEN) {
//            return response()->json(["msg" => "Invalid token provided"], 400);
//        }
//
//        return response()->json(["msg" => "Password has been successfully changed"]);

        return new AuthenticationResponse(
            $reset_password_status,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }
}
