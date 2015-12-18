<?php

namespace Tajrish\Services\V1\Auth;

use Dingo\Api\Routing\Helpers;
use Tajrish\Helpers\TokenHelper;
use Tajrish\Services\V1\AbstractService;
use Tajrish\Validators\V1\AuthValidator;
use Illuminate\Contracts\Hashing\Hasher;

class RecoverPasswordService  extends AbstractService
{
    use Helpers;

    /**
     * @var AuthValidator
     */
    private $validator;
    /**
     * @var TokenHelper
     */
    private $tokenHelper;
    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * @param AuthValidator $validator
     * @param TokenHelper   $tokenHelper
     * @param Hasher        $hasher
     */
    public function __construct(AuthValidator $validator, TokenHelper $tokenHelper, Hasher $hasher)
    {

        $this->validator = $validator;
        $this->tokenHelper = $tokenHelper;
        $this->hasher = $hasher;
    }

    public function fire(array $data)
    {
        $this->validator->setScenario('recoverPassword')->validate($data);

        $token = $this->tokenHelper->validate($data['token']);


        if($token === false) {
            $this->response()->errorBadRequest(trans('messages.token_invalid'));
        }

        $user = $token->tokenable->first();

        if($user === NULL)
        {
            $this->response()->errorBadRequest(trans('messages.token_invalid'));
        }

        $user->password = $this->hasher->make($data['password']);

        $user->save();

        $token->delete();

        return $user;
    }
}