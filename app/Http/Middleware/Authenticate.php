<?php

namespace Tajrish\Http\Middleware;

use Closure;
use Tajrish\Services\UserTokenHandler;

class Authenticate
{
    /**\
     * @var \Tajrish\Services\UserTokenHandler\UserTokenHandler
     */
    protected $tokens;

    /**
     * Authenticate constructor.
     *
     * @param \Tajrish\Services\UserTokenHandler\UserTokenHandler $tokenHandler
     */
    public function __construct(UserTokenHandler $tokenHandler)
    {
        $this->tokens = $tokenHandler;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->has('token') ? (string)$request->input('token') : null;

        if (null === $token) {
            $token = $request->header('token');
        }

        if (null == $token) {
            return response()->json([
                'messages' => trans('messages.token_not_provided'),
                'code' => 'token_not_provided'
            ], 401);
        }

        $token = $this->tokens->getUserFromToken($token);

        if (!$token) {
            return response()->json([
                'messages' => trans('messages.invalid_token'),
                'code' => 'invalid_token'
            ], 401);
        }

        return $next($request);
    }
}
