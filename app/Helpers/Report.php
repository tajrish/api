<?php

namespace Tajrish\Helpers;

use Illuminate\Support\Facades\Mail;

class Report
{

    /**
     * @param \Illuminate\Http\Request $request
     */
    public static function send($request, $caller=null)
    {

        $text= 'User With This Id : '. $request->user()->id. ' Is Going to attack : '.'<br>';

        if($caller !== NULL)
        {
            $text.= 'Caller: '. $caller.'<br>';
        }

        $text.= 'IP: '. $request->getClientIp().'<br>';
        $text.= 'Headers: '.http_build_query($request->header()).'<br>';
        $text.= 'Headers: '.http_build_query($request->headers).'<br>';
        $text.= 'Server: '.http_build_query($request->server()).'<br>';

        $data = [
            'text' => $text
        ];

        Mail::queue('v1.emails.report', $data, function ($message) {
            $message
                ->subject(config('tezol.attack_report.subject'))
                ->to(config('tezol.attack_report.to'), config('tezol.attack_report.toName'));
        });
    }

}