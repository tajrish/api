<?php

namespace Tajrish\Exceptions;

use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;


class LoginFailedException extends HttpException
{

    public function __construct()
    {

        $this->errors =  new MessageBag([
            'password' => 'password is wrong'
        ]);


        parent::__construct(Response::HTTP_UNAUTHORIZED, trans('messages.login_failed'), null);
    }

}