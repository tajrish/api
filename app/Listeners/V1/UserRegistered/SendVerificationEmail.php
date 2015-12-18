<?php

namespace Tajrish\Listeners\V1\UserRegistered;

use Carbon\Carbon;
use Tajrish\Events\V1\UserRegistered;
use Tajrish\Helpers\TokenHelper;
use Tajrish\Models\Token;
use Tajrish\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmail implements ShouldQueue
{

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var User
     */
    protected $userModel;

    /**
     * @var TokenHelper
     */
    protected $tokenHelper;

    /**
     * Create the event listener.
     *
     * @param Mailer      $mailer
     * @param User        $userModel
     * @param TokenHelper $tokenHelper
     */
    public function __construct(Mailer $mailer, User $userModel, TokenHelper $tokenHelper)
    {
        $this->mailer = $mailer;
        $this->userModel = $userModel;
        $this->tokenHelper = $tokenHelper;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user;

        if(!$user['email'])
            return;

        $token = $this->tokenHelper->setModel($user)->create($user->id.'verification');

        $mail_data = [
            'token'=>$token->token,
            'toName'=>$user->username,
            'toEmail'=>$user->email
        ];

        $this->mailer->send('v1.emails.verification', $mail_data, function(Message $message) use($mail_data){

            $message->from(config('tezol.register_verification.form', 'noreply@tezol.com'),
                config('tezol.register_verification.fromName', 'Tezol')
                );

            $message->to($mail_data['toEmail'], $mail_data['toName']);
            $message->subject(config('tezol.register_verification.subject', 'Tezol'));

        });
    }



}
