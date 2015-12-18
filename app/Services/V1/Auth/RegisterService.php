<?php
namespace Tajrish\Services\V1\Auth;

use Tajrish\Events\V1\UserRegistered;
use Tajrish\Models\User;
use Tajrish\Services\V1\AbstractService;
use Tajrish\Validators\V1\AuthValidator;
use Illuminate\Contracts\Hashing\Hasher;

class RegisterService extends AbstractService
{
    /**
     * @var AuthValidator
     */
    protected $validator;
    /**
     * @var User
     */
    protected $userModel;
    /**
     * @var Hasher
     */
    protected $hasher;

    /**
     * @param AuthValidator $validator
     * @param User          $userModel
     * @param Hasher        $hasher
     */
    public function __construct(AuthValidator $validator, User $userModel, Hasher $hasher)
    {
        $this->validator = $validator;
        $this->userModel = $userModel;
        $this->hasher = $hasher;
    }

    public function fire(array $data)
    {
        $this->validator->setScenario('register')->validate($data);

        $data['password'] = $this->hasher->make($data['password']);

        $user = $this->userModel->create($data);

        event(new UserRegistered($user));

        return $user;
    }
}