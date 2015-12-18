<?php

namespace Tajrish\Services;

use Tajrish\Events\UserFoundFromToken;
use Tajrish\Models\User;
use Tajrish\Models\UserToken;
use Tajrish\Repositories\UserTokenRepository;
use Tajrish\Services\Tosan\Helpers\TokenGenerator;

class UserTokenHandler
{
    /**
     * @var \Tajrish\Repositories\UserTokenRepository
     */
    protected $repo;

    /**
     * UserTokenHandler constructor.
     *
     * @param \Tajrish\Repositories\UserTokenRepository $repo
     */
    public function __construct(UserTokenRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Create and get token for user
     *
     * @param \Tajrish\Models\User $user
     * @return UserToken
     */
    public function createAndGetTokenForUser(User $user)
    {
        $tokenIsNotAvailable = true;
        while ($tokenIsNotAvailable) {
            $generatedToken = TokenGenerator::generate();
            $tokenIsNotAvailable = $this->repo->tokenExists($user, $generatedToken);
        }

        $tokenEntity = $this->repo->makeUniqueTokenForUser($user, $generatedToken);

        $tokenEntity->setHidden(['id', 'user_id', 'created_at', 'updated_at']);

        return $tokenEntity;
    }

    /**
     * Get user from token
     *
     * @param      $token
     * @param bool $hard
     * @return null|\Tajrish\Models\User
     */
    public function getUserFromToken($token, $hard = false)
    {
        $user = $this->repo->getUserFromToken($token, $hard);

        if ($user) {
            event(new UserFoundFromToken($user));
        }

        return $user;
    }
}