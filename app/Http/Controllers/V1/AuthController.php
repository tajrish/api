<?php

namespace Tajrish\Http\Controllers\V1;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Tajrish\Models\User;
use Tajrish\Services\UserTokenHandler;

class AuthController extends ApiController
{
    /**
     * Register user
     *
     * @param \Illuminate\Http\Request           $request
     * @param \Tajrish\Services\UserTokenHandler $tokenService
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request, UserTokenHandler $tokenService)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|max:255',
            'name' => 'required|string'
        ]);

        $user = User::create([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'name' => $request->input('name')
        ]);

        $token = $tokenService->createAndGetTokenForUser($user);

        return response()->json(['created' => true, 'token_entity' => $token, 'user' => $user]);
    }

    /**
     * Login user
     *
     * @param \Illuminate\Http\Request           $request
     * @param \Tajrish\Services\UserTokenHandler $tokenHandler
     * @param \Illuminate\Auth\Guard             $auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request, UserTokenHandler $tokenHandler, Guard $auth)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // @FIXME Remove extra user call
        if ($auth->attempt($request->only(['email', 'password']))) {
            $user = User::where('email', $request['email'])->first();
            $token = $tokenHandler->createAndGetTokenForUser($user);
            return response()->json([
                'user' => $user,
                'found' => true,
                'token_entity' => $token,
                'message' => trans('messages.successful_login')
            ]);
        }

        return response()->json(['found' => false, 'message' => trans('messages.invalid_credentials')]);
    }
}