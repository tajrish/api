<?php

namespace Tajrish\Services\V1\Auth;

use Tajrish\Helpers\TokenHelper;
use Tajrish\Models\User;
use Tajrish\Services\V1\AbstractService;
use Tajrish\Validators\V1\AuthValidator;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;

class ForgotPasswordService  extends AbstractService
{
    /**
     * @var AuthValidator
     */
    private $validator;
    /**
     * @var User
     */
    private $userModel;
    /**
     * @var TokenHelper
     */
    private $tokenHelper;
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @param AuthValidator $validator
     * @param User          $userModel
     * @param TokenHelper   $tokenHelper
     * @param Mailer        $mailer
     */
    public function __construct(AuthValidator $validator, User $userModel, TokenHelper $tokenHelper, Mailer $mailer)
    {

        $this->validator = $validator;
        $this->userModel = $userModel;
        $this->tokenHelper = $tokenHelper;
        $this->mailer = $mailer;
    }

    public function sendRecoveryEmail(array $data)
    {
        $this->validator->setScenario('forgotPassword')->validate($data);

        $user = $this->userModel->where('email', $data['email'])->first();

        return $this->sendEmail($user);

    }

    private function sendEmail($user)
    {
        $token = $this->tokenHelper->setModel($user)->create($user->id.'recovery');

        $mail_data = [
            'toName' => $user->username,
            'toEmail'=> $user->email,
            'token'=>$token->token
        ];

        $this->mailer->send('v1.emails.forgot_password', $mail_data, function(Message $message) use ($mail_data){

            $message->from(config('tezol.forgot_password.form', 'noreply@tezol.com'),
                config('tezol.forgot_password.fromName', 'Tezol'));

            $message->subject(config('tezol.forgot_password.subject'));

            $message->to($mail_data['toName'], $mail_data['toEmail']);

        });

        return true;
    }
}