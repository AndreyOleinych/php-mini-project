<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Action\Auth\ForgotPasswordAction;
use App\Action\Auth\ForgotPasswordRequest;
use App\Action\Auth\GetAuthenticatedUserAction;
use App\Action\Auth\LoginAction;
use App\Action\Auth\LoginRequest;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\Auth\RegisterRequest;
use App\Action\Auth\ResetPasswordAction;
use App\Action\Auth\ResetPasswordRequest;
use App\Action\Auth\UpdateProfileAction;
use App\Action\Auth\UpdateProfileRequest;
use App\Action\Auth\UploadProfileImageAction;
use App\Action\Auth\UploadProfileImageRequest;
use App\Http\Controllers\ApiController;
use App\Http\Presenter\AuthenticationResponseArrayPresenter;
use App\Http\Presenter\UserArrayPresenter;
use App\Http\Request\Api\Auth\ForgotPasswordHttpRequest;
use App\Http\Request\Api\Auth\RegisterHttpRequest;
use App\Http\Request\Api\Auth\LoginHttpRequest;
use App\Http\Request\Api\Auth\ResetPasswordHttpRequest;
use App\Http\Request\Api\Auth\UpdateProfileHttpRequest;
use App\Http\Request\Api\Auth\UploadProfileImageHttpRequest;
use App\Http\Response\ApiResponse;

final class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(
        RegisterHttpRequest $httpRequest,
        RegisterAction $action,
        AuthenticationResponseArrayPresenter $authenticationResponseArrayPresenter
    ): ApiResponse {
        $request = new RegisterRequest(
            $httpRequest->get('email'),
            $httpRequest->get('password'),
            $httpRequest->get('first_name'),
            $httpRequest->get('last_name'),
            $httpRequest->get('nickname')
        );
        $response = $action->execute($request);

        return $this->createSuccessResponse($authenticationResponseArrayPresenter->present($response));
    }

    public function forgotPassword(
        ForgotPasswordHttpRequest $httpRequest,
        ForgotPasswordAction $action,
        AuthenticationResponseArrayPresenter $authenticationResponseArrayPresenter
    ): ApiResponse {
        $request = new ForgotPasswordRequest(
            $httpRequest->email
        );
        $response = $action->execute($request);

        return $this->createSuccessResponse($authenticationResponseArrayPresenter->present($response));
    }

    public function resetPassword(
        ResetPasswordHttpRequest $httpRequest,
        ResetPasswordAction $action,
        AuthenticationResponseArrayPresenter $authenticationResponseArrayPresenter
    ): ApiResponse {
        $request = new ResetPasswordRequest(
            $httpRequest->token,
            $httpRequest->email,
            $httpRequest->password,
            $httpRequest->password_confirmation,
        );
        $response = $action->execute($request);

        return $this->createSuccessResponse($authenticationResponseArrayPresenter->present($response));
    }

    public function login(
        LoginHttpRequest $httpRequest,
        LoginAction $action,
        AuthenticationResponseArrayPresenter $authenticationResponseArrayPresenter
    ): ApiResponse {
        $request = new LoginRequest(
            $httpRequest->email,
            $httpRequest->password
        );
        $response = $action->execute($request);

        return $this->createSuccessResponse($authenticationResponseArrayPresenter->present($response));
    }

    public function me(GetAuthenticatedUserAction $action, UserArrayPresenter $userArrayPresenter): ApiResponse
    {
        $response = $action->execute();

        return $this->createSuccessResponse($userArrayPresenter->present($response->getUser()));
    }

    public function logout(LogoutAction $action): ApiResponse
    {
        $action->execute();

        return $this->createEmptyResponse();
    }

    public function update(
        UpdateProfileHttpRequest $httpRequest,
        UpdateProfileAction $action,
        UserArrayPresenter $userArrayPresenter
    ): ApiResponse {
        $response = $action->execute(
            new UpdateProfileRequest(
                $httpRequest->get('email'),
                $httpRequest->get('first_name'),
                $httpRequest->get('last_name'),
                $httpRequest->get('nickname')
            )
        );

        return $this->createSuccessResponse($userArrayPresenter->present($response->getUser()));
    }

    public function uploadProfileImage(
        UploadProfileImageHttpRequest $httpRequest,
        UploadProfileImageAction $action,
        UserArrayPresenter $userArrayPresenter
    ): ApiResponse {
        $response = $action->execute(
            new UploadProfileImageRequest(
                $httpRequest->file('image')
            )
        );

        return $this->createSuccessResponse($userArrayPresenter->present($response->getUser()));
    }
}
