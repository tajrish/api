<?php
namespace Tajrish\Validators\V1;

class AuthValidator extends AbstractValidator {


    protected $customAttributes = [
        'email' => 'ایمیل',
        'password' => 'رمز عبور'
    ];

    /**
     * @var array
     */
    protected $registerRules = [
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:5', 'max:60']
    ];


    protected $loginRules = [
        'email' => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'min:5', 'max:60'],
    ];


    protected $forgotPasswordRules = [
        'email' => ['required', 'email', 'exists:users,email'],
    ];

    protected $recoverPasswordRules = [
        'password' => ['required', 'min:5', 'max:60'],
    ];
}