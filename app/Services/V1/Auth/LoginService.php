<?php

namespace Tajrish\Services\V1\Auth;

use Tajrish\Exceptions\LoginFailedException;
use Tajrish\Models\User;
use Tajrish\Services\V1\AbstractService;
use Tajrish\Validators\V1\AuthValidator;
use Illuminate\Contracts\Hashing\Hasher;

class LoginService  extends AbstractService
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var AuthValidator
     */
    protected $validator;
    /**
     * @var Hasher
     */
    private $hasher;

    public function __construct(User $user, AuthValidator $validator, Hasher $hasher)
    {

        $this->user      = $user;
        $this->validator = $validator;
        $this->hasher = $hasher;
    }

    public function fire(array $data)
    {
        $this->validator->setScenario('login')->validate($data);

        $user = $this->user->where('email', $data['email'])->first();

        if(!$this->hasher->check($data['password'], $user->password))
            throw new LoginFailedException();

        return $user;
    }
}