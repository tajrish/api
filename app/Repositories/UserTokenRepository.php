<?php

namespace Tajrish\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tajrish\Models\User;
use Tajrish\Models\UserToken;

class UserTokenRepository
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var \Tajrish\Models\UserToken
     */
    protected $model;

    /**
     * UserTokenRepository constructor.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Tajrish\Models\UserToken               $userToken
     */
    public function __construct(Repository $config, UserToken $userToken)
    {
        $this->config = $config;
        $this->model = $userToken;
    }

    /**
     * Make token for the given user
     *
     * @param \Tajrish\Models\User $user
     * @param                      $token
     * @param \Carbon\Carbon|null  $expiresAt
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function makeUniqueTokenForUser(User $user, $token, Carbon $expiresAt = null)
    {
        if ($expiresAt && $expiresAt->isPast()) {
            throw new \InvalidArgumentException('Token expiration can not be at past.');
        }

        $expiresAt = $expiresAt ?: Carbon::now()->addMinutes(config('tajrish.expires_at_minutes', 24 * 60 * 7));

        return $user->tokens()->create([
            'expires_at' => $expiresAt,
            'token' => $token
        ]);
    }

    /**
     * Get user from given token
     *
     * @param           $token
     * @param bool|true $hard
     * @return null
     */
    public function getUserFromToken($token, $hard = true)
    {
        $model = $this->model->whereToken($token)->where('expires_at', '>', Carbon::now())->first();

        if (!$model && !$hard) return null;

        if (!$model) throw new ModelNotFoundException('No model found with token : ['.$token.']');

        return $model->user;
    }

    /**
     * Indicates that given token exists
     *
     * @param $token
     * @return bool
     */
    public function tokenExists($token)
    {
        return $this->model->whereToken($token)->exists();
    }
}